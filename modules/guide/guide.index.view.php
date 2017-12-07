<?php

namespace Tbmt\view;

class GuideIndex extends Base {

  public function render(array $params = array()) {
    $this->member = isset($params['member']) && ($params['member'] instanceof \Member)
      ? $params['member']
      : null;

    $this->purchaseFailedMessage = !isset($_REQUEST['purchase_failed']) || $_REQUEST['purchase_failed']
      ? ''
      : $this->i18nView['popup_purchase_cancel_text'];

    $this->formData = null;
    if ( $this->member && !$this->member->isMarkedAsPaid() ) {
      $this->formData = \Tbmt\Payu::prepareFormData($this->member, \Propel::getConnection());

      if ( $this->formData && $this->formData instanceof \Payment && $this->formData->getStatus() === \Payment::STATUS_EXECUTED ) {

        Session::set(Session::KEY_PAYMENT_MSG, true);
        return new ControllerActionRedirect(Router::toAccountTab('index'));
      }
    }

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.guide.html',
      $params
    );
  }

}