<?php

namespace Tbmt\view;

class Factory {

  static function buildMemberFullNameString(\Member $member) {
    return $member->getTitle(). ' '.$member->getFirstName(). ' '.$member->getLastName();
  }

  static function buildPageTitle($title) {
    return <<<END
<!--  Page Title -->
<div id="page-title">

  <!-- 960 Container Start -->
  <div class="container">

    <div class="eight columns">
      <h2>$title</h2>
    </div>

    <!--
    <div class="eight columns">
      <nav id="breadcrumbs">
        <ul>
          <li>You are here:</li>
          <li><a href="#">Home</a></li>
          <li>Blog</li>
        </ul>
      </nav>
    </div>
    -->
  </div>
  <!-- 960 Container End -->

</div>
<!-- Page Title End -->
END;

  }

  static function buildNotification($text, $strong = '', $type = 'error') {
    if ( $strong )
      $strong = "<span>$strong</span> ";

    return <<<END
<div class="notification $type closeable" style="display: block;">
    <p>${strong}$text</p>
</div>
END;

  }

  static function buildFeature($title, $text, $icon, $iconColor = 'gray') {
    return <<<END
<div class="feature">
  <div class="feature-circle $iconColor"><i class="fa fa-$icon"></i></div>
  <div class="feature-description">
    <h4>$title</h4>
    <p>$text</p>
  </div>
</div>
END;
  }

  static function buildListItems($items) {
    $r = '';
    foreach ($items as $item) {
      $r .= '<li>'.$item.'</li>';
    }
    return $r;
  }

  static function buildInfoBox($title, $text, $content = '') {
    return <<<END
<div class="info-box">
  <div class="info-content">
    <h4>$title</h4>
    <p>$text</p>
  </div>
  $content
  <div class="clearfix"></div>
</div>
END;
  }

  static function buildLargeNotice($title, $text, $content = '') {
    return <<<END
<div class="large-notice">
  <h2>$title</h2>
  <p>$text</p>
  $content
</div>
END;
  }

  static function echoTableRows($arrTableRows) {
    foreach ($arrTableRows as $row) {
      echo '<tr>';
      foreach ($row as $value) {
        echo "<td>$value</td>";
      }

      echo '</tr>';
    }
  }

  static function currencyArrToString($arrCurrencies) {
    if ( empty($arrCurrencies) )
      return ' - ' ;

    $arr = [];
    foreach ($arrCurrencies as $currency => $amount) {
      $arr[] = \Tbmt\Localizer::currencyFormat($amount, $currency);
    }

    return implode(', ', $arr);
  }

}

?>