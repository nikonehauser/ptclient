<?php

namespace Tbmt\view;

class GuideIndex extends Base {

  public function render(array $params = array()) {
    $this->member = isset($params['member']) && ($params['member'] instanceof \Member)
      ? $params['member']
      : null;

    $this->cancelPPPUrl = \Tbmt\Router::toModule('guide', 'ajax_cancel_ppp');
    $this->createPPPUrl = \Tbmt\Router::toModule('guide', 'ajax_create_ppp');
    $this->executePPPUrl = \Tbmt\Router::toModule('guide', 'ajax_exec_ppp');
    $this->redirectSuccessUrl = \Tbmt\Router::toModule('account', 'index');

    $this->purchaseFailedMessage = !isset($_REQUEST['purchase_failed']) || $_REQUEST['purchase_failed']
      ? ''
      : $this->i18nView['popup_purchase_cancel_text'];

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.guide.html',
      $params
    );
  }

}