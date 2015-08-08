<?php

namespace Tbmt\view;

class AboutFaq extends Base {

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'faq.about.html',
      $params
    );
  }

}