<?php
namespace Tbmt;

class RestClient {

  /**
   * @var string
   */
  private $url = null;

  /**
   * @var array
   */
  private $header = array();

  /**
   * @var array
   */
  private $responseHeaders = array();

  /**
   * @var array
   */
  private $methods = array(
    'GET'    => true,
    'POST'   => true,
    'PUT'    => true,
    'DELETE' => true,
  );

  /**
   * @var string
   */
  private $method = 'GET';

  public $protocol_version = false; // Use the default protocol version
  public $additionHttpContextParams = false;

  public function __construct($url = null) {
    $this->setURL($url);
  }

  /* --------------- Setting functions --------------- */

  public function setAdditionalHttpContextParams(array $params) {
    $this->additionHttpContextParams = $params;
  }

  public function setURL($url) {
    $this->url = $url;
    return $this;
  }

  public function appendHeader($strHeader) {
    $this->header[] = preg_replace('#$#', '', $strHeader);
    return $this;
  }

  public function setHeaders(array $headers) {
    $this->header = $headers;
    return $this;
  }

  /**
   * @deprecated since 2013-01-10
   * @see setHeaders()
   */
  public function setHeader(array $headers) {
    $this->header = $headers;
    return $this;
  }

  public function setMethod($method) {
    $method = strtoupper($method);
    if ( empty($this->methods[$method]) )
      return false;

    $this->method = $method;
    return true;
  }

  /* --------------- Getting functions --------------- */

  public function getURL() {
    return $this->url;
  }

  public function getHeader() {
    return implode("\r\n", $this->header);
  }

  /**
   * @return array
   */
  public function getResponseHeaders() {
    return $this->responseHeaders;
  }

  /* --------------- Request functions --------------- */

  public function request($params = null, $url = null, $method = null, $contenttype = 'text/plain') {
    $this->responseHeaders = array();

    if ( !is_array($params) ) {
      $params = [];
    }

    $params['token'] = Cryption::getApiToken();

    // Initialize parameters
    $url = parse_url( $url === null ? $this->url : $url );

    $query = isset($url['query']) ? $url['query'] : null;

    $method = strtoupper( $method === null ? $this->method : $method );

    if ( $contenttype ) {
      // Only set content type if is given
      $this->appendHeader('Content-Type: '.$contenttype);
    }

    // Perform the request

    if ( empty($this->methods[$method]) )
      throw new \Exception('Invalid HTTP method: '.$method);

    // Add this to your script if you ever encounter an
    // "417 - Expectation Failed" error message.
    //$this->appendHeader('Expect:');

    $ctxHttpParams = [
      'method'  => $method,
      'ignore_errors' => true
    ];

    if ( $this->protocol_version )
      $ctxHttpParams['protocol_version'] =$this->protocol_version;

    if ( $this->additionHttpContextParams )
      $ctxHttpParams = array_merge($ctxHttpParams, $this->additionHttpContextParams);

    $strUrl = $url['scheme'].'://'.$url['host'].( isset($url['port']) ? ':'.$url['port'] : '' );
    if ( isset($url['path']) )
      $strUrl .= $url['path'];

    $contentLength = 0;
    if ( !empty($params) ) {
      if ( $method === 'GET' ) {
        if ( is_array($params) )
          $query = ( $query === null ? '' : '&').http_build_query($params, null, '&');
        else
          $query = ( $query === null ? '' : '&').$params;

      } else if ( strpos($contenttype, 'application/json') === 0 ) {
        $ctxHttpParams['content'] = (
          is_string($params) ?
          $params :
          json_encode($params)
        );
        $contentLength = strlen($ctxHttpParams['content']);

      } else {
        $ctxHttpParams['content'] = http_build_query($params, null, '&');
        $contentLength = strlen($ctxHttpParams['content']);
      }
    }

    if ( $query )
      $strUrl .= '?'.$query;

    $this->appendHeader('Content-Length: '.$contentLength);

    $ctxHttpParams['header'] = $this->getHeader();

    if ( isset($url['fragment']) )
      $strUrl .= '#'.$url['fragment'];

    $arrContentOptions = ['http' => $ctxHttpParams];

    $ctx = stream_context_create($arrContentOptions);
    $contents = file_get_contents($strUrl, false, $ctx);

    $this->responseHeaders = (
      isset($http_response_header)
      ? $http_response_header
      : array()
    );

    return new RestResult($contents, $http_response_header);
  }

  /**
   * Convenience method wrapping a commom POST call
   */
  public function post($params = null, $url = null, $contenttype = 'application/x-www-form-urlencoded') {
    return $this->request($params, $url, 'POST', $contenttype);
  }

  /**
   * Convenience method wrapping a commom PUT call
   */
  public function put($params = null, $url = null, $contenttype = 'application/x-www-form-urlencoded') {
    return $this->request($params, $url, 'PUT', $contenttype);
  }

  /**
   * Convenience method wrapping a commom GET call
   */
  public function get($params = null, $url = null, $contenttype = null) {
    return $this->request($params, $url, 'GET', $contenttype);
  }

  /**
   * Convenience method wrapping a commom delete call
   */
  public function delete($params = null, $url = null, $contenttype = 'application/x-www-form-urlencoded') {
    return $this->request($params, $url, 'DELETE', $contenttype);
  }

}

class RestResult {
  private $content;
  private $resultHeaders;
  private $resultStatusCode;
  private $resultStatusText;
  public function __construct($content, $resultHeaders) {
    $this->content = $content;
    $this->resultHeaders = $resultHeaders;
    $this->parseHeaders();
  }

  private function parseHeaders() {
    $status = isset($this->resultHeaders[0]) ? $this->resultHeaders[0] : '';
    $res = preg_match('/[^\s]*\s(\d{3,})\s(.*)/', $status, $matches);
    if ( $res !== false && $res > 0 ) {
      $this->resultStatusCode = $matches[1];
      $this->resultStatusText = $matches[2];
    }
  }

  public function openResultAsJson() {
    if ( $this->resultStatusCode != 200 ) {
      throw new \Exception('Invalid response status: '.$this->resultStatusCode.' '.$this->resultStatusText." \n");
    }

    $res = json_decode($this->content, true);
    if ( json_last_error() !== JSON_ERROR_NONE ) {
      print_r('<pre>');
      print_r([$this->resultHeaders, htmlentities($this->content)]);
      print_r('</pre>');

      throw new \Exception('Invalid result type. Can not parse JSON: '.json_last_error_msg()." \n");
    }

    return $res;
  }

}

?>