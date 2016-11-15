<?php

namespace Tbmt;

class ApiClient {

  private $url;
  private $client;
  public function __construct() {
    $this->url = Config::get('main_system_url').'api.php';
    $this->client = new RestClient($this->url);
  }

  public function pushRootAccounts($arrAccounts) {
    return $this->client->post([
      'api'  => 'accounts',
      'do'   => 'pushRootAccounts',
      'accounts' => json_encode($arrAccounts)
    ])->openResult();
  }
}

?>