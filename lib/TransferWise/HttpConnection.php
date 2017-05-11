<?php

namespace TransferWise;

use Exception;

/**
 * A wrapper class based on the curl extension.
 * Requires the PHP curl module to be enabled.
 * See for full requirements the PHP manual: http://php.net/curl
 */
class HttpConnection
{

    /**
     * Default Constructor
     *
     * @param PayPalHttpConfig $httpConfig
     * @param array            $config
     * @throws PayPalConfigurationException
     */
    public function __construct()
    {
        if (!function_exists("curl_init")) {
            throw new Exception("Curl module is not available on this system");
        }

    }

    /**
     * Gets all Http Headers
     *
     * @return array
     */
    public function getHttpHeaders()
    {
        $ret = array();
        foreach ($this->httpConfig->getHeaders() as $k => $v) {
            $ret[] = "$k: $v";
        }
        return $ret;
    }

    /**
     * Executes an HTTP request
     *
     * @param string $data query string OR POST content as a string
     * @return mixed
     * @throws PayPalConnectionException
     */
    public function execute($data, )
    {
        //Initialize the logger
        $this->logger->info($this->httpConfig->getMethod() . ' ' . $this->httpConfig->getUrl());

        //Initialize Curl Options
        $ch = curl_init($this->httpConfig->getUrl());
        $options = $this->httpConfig->getCurlOptions();
        if (empty($options[CURLOPT_HTTPHEADER])) {
            unset($options[CURLOPT_HTTPHEADER]);
        }
        curl_setopt_array($ch, $options);
        curl_setopt($ch, CURLOPT_URL, $this->httpConfig->getUrl());
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHttpHeaders());

        //Determine Curl Options based on Method
        switch ($this->httpConfig->getMethod()) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'PUT':
            case 'PATCH':
            case 'DELETE':
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
        }

        //Default Option if Method not of given types in switch case
        if ($this->httpConfig->getMethod() != null) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->httpConfig->getMethod());
        }

        //Logging Each Headers for debugging purposes
        foreach ($this->getHttpHeaders() as $header) {
            //TODO: Strip out credentials and other secure info when logging.
            // $this->logger->debug($header);
        }

        //Execute Curl Request
        $result = curl_exec($ch);
        //Retrieve Response Status
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        //Retry if Certificate Exception
        if (curl_errno($ch) == 60) {
            $this->logger->info("Invalid or no certificate authority found - Retrying using bundled CA certs file");
            curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
            $result = curl_exec($ch);
            //Retrieve Response Status
            $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }

        //Throw Exception if Retries and Certificates doenst work
        if (curl_errno($ch)) {
            $ex = new PayPalConnectionException(
                $this->httpConfig->getUrl(),
                curl_error($ch),
                curl_errno($ch)
            );
            curl_close($ch);
            throw $ex;
        }

        // Get Request and Response Headers
        $requestHeaders = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        //Using alternative solution to CURLINFO_HEADER_SIZE as it throws invalid number when called using PROXY.
        if (function_exists('mb_strlen')) {
            $responseHeaderSize = mb_strlen($result, '8bit') - curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
            $responseHeaders = mb_substr($result, 0, $responseHeaderSize, '8bit');
            $result = mb_substr($result, $responseHeaderSize, mb_strlen($result), '8bit');
        } else {
            $responseHeaderSize = strlen($result) - curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
            $responseHeaders = substr($result, 0, $responseHeaderSize);
            $result = substr($result, $responseHeaderSize);
        }

        $this->logger->debug("Request Headers \t: " . str_replace("\r\n", ", ", $requestHeaders));
        $this->logger->debug(($data && $data != '' ? "Request Data\t\t: " . $data : "No Request Payload") . "\n" . str_repeat('-', 128) . "\n");
        $this->logger->info("Response Status \t: " . $httpStatus);
        $this->logger->debug("Response Headers\t: " . str_replace("\r\n", ", ", $responseHeaders));

        //Close the curl request
        curl_close($ch);
        if ($httpStatus < 200 || $httpStatus >= 300) {
            $ex = new PayPalConnectionException(
                $this->httpConfig->getUrl(),
                "Got Http response code $httpStatus when accessing {$this->httpConfig->getUrl()}.",
                $httpStatus
            );
            $ex->setData($result);
            $this->logger->error("Got Http response code $httpStatus when accessing {$this->httpConfig->getUrl()}. " . $result);
            $this->logger->debug("\n\n" . str_repeat('=', 128) . "\n");
            throw $ex;
        }

        $this->logger->debug(($result && $result != '' ? "Response Data \t: " . $result : "No Response Body") . "\n\n" . str_repeat('=', 128) . "\n");

        //Return result object
        return $result;
    }
}