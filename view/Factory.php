<?php

namespace Tbmt\view;

class Factory {

  static function buildAttributes(array $attributes) {
    $attr = '';
    foreach ( $attributes as $name => $val ) {
      $attr .= ' '.$name.'="'.e($val).'"';
    }

    return $attr;
  }

  static $fmtMemberFeeStr;
  static function buildFmtMemberFeeStr() {
    if ( ! self::$fmtMemberFeeStr )
      self::$fmtMemberFeeStr = \Tbmt\Localizer::currencyFormatByCfg(\Transaction::$MEMBER_FEE);

    return self::$fmtMemberFeeStr;
  }

  static $fmtMemberFeeStrWithoutTax;
  static function buildFmtMemberFeeStrWithoutTax() {
    if ( ! self::$fmtMemberFeeStrWithoutTax )
      self::$fmtMemberFeeStrWithoutTax = \Tbmt\Localizer::currencyFormatByCfg((\Transaction::$MEMBER_FEE * 100) / 118);

    return self::$fmtMemberFeeStrWithoutTax;
  }

  static $fmtMemberFeeStrTaxAmount;
  static function buildFmtMemberFeeStrTaxAmount() {
    if ( ! self::$fmtMemberFeeStrTaxAmount )
      self::$fmtMemberFeeStrTaxAmount = \Tbmt\Localizer::currencyFormatByCfg((\Transaction::$MEMBER_FEE * 18) / 118);

    return self::$fmtMemberFeeStrTaxAmount;
  }

  /**
   *
   * @param  \Member $member
   * @return String
   */
  static function buildMemberFullNameString(\Member $member) {
    $name = '';
    $title = $member->getTitle();

    if ( $title !== '' )
      $name = $title.' ';

    return $name.$member->getFirstName().' '.$member->getLastName();
  }


  /**
   *
   * @return String
   */
  static function buildBankAccountStr() {
    return \Tbmt\Config::get('bankaccount');
  }


  /**
   * [buildHeadingArea description]
   * @param  [type]  $title
   * @param  string  $classes
   * @param  integer $size
   * @return [type]
   */
  static function buildButtonTag($text, $type = '', $icon = '', array $attributes = []) {
    if ( $icon !== '' )
      $icon = '<span class="icon"><i class="icon-right fa fa-'.$icon.'"></i></span>';

    if ( $attributes )
      $attributes = self::buildAttributes($attributes);
    else
      $attributes = '';

// <button id="submit" class="button"><span>Send your message</span><span class="icon"><i class="fa fa-send"></i></span></button>
    return <<<END
<button$attributes class="button $type"><span>$text</span>$icon</button>
END;
  }


  /**
   * [buildHeadingArea description]
   * @param  [type]  $title
   * @param  string  $classes
   * @param  integer $size
   * @return [type]
   */
  static function buildButton($text, $href, $type = '', $icon = '', $target = '') {
    if ( $icon !== '' )
      $icon = '<i class="icon-right fa fa-'.$icon.'"></i>';

    return <<<END
<a href="$href" $target class="button $type text-center"><span>$text $icon</span></a>
END;
  }


  /**
   * [buildHeadingArea description]
   * @param  [type]  $title
   * @param  string  $classes
   * @param  integer $size
   * @return [type]
   */
  static function buildLink($text, $href, $type = '', $target = '') {
    return <<<END
<a href="$href" $target class="$type">$text</a>
END;
  }


  /**
   * [buildHeadingArea description]
   * @param  [type]  $title
   * @param  string  $classes
   * @param  integer $size
   * @return [type]
   */
  static function buildSubmitBtn($text, $type = 'blue') {
    return <<<END
<input type="submit" class="button $type text-center" value="$text"/>
END;
  }


  /**
   * [buildHeadingArea description]
   * @param  [type]  $title
   * @param  string  $classes
   * @param  integer $size
   * @return [type]
   */
  static function buildHeadingArea($title, $classes = 'bottom-30 top-40', $subHeading = '', $size = 1) {
    if ( !empty($subHeading) ) {
      $subHeading = '<span class="sub-heading">'.$subHeading.'</span>';
    }
    return <<<END
<div class="heading-area $classes">
  <h$size class="heading">$title</h$size>
  $subHeading
</div>
END;
  }


  /**
   * [buildPageTitle description]
   * @param  [type] $title
   * @return [type]
   */
  static function buildPageTitle($title) {
    return <<<END

<div class="page-heading">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="page-title-area">
          <h2 class="bottom-0 page-title">$title</h2>
        </div>
      </div>
      <!--
      <div class="col-md-6 text-right">
        <div class="breadcrumbs">
          <ul class="bottom-0 list-unstyled">
            <li><a href="#"><i class="fa fa-home"></i></a></li>
            <li><a href="#">Page</a></li>
            <li><span>FAQ</span></li>
          </ul>
        </div>
      </div>
      -->
    </div>
  </div>
</div>
END;

  }


  /**
   * [buildNotification description]
   * @param  [type] $text
   * @param  string $strong
   * @param  string $type
   * @param  string $content
   * @return [type]
   */
  static private $NOT_TYPE_TO_ICON = [
    'default' => '',
    'notice' => 'times',
    'info'  => 'info',
    'warning' => 'exclamation',
    'success' => 'check'
  ];
  static function buildNotification($text, $strong = '', $type = 'notice', $content = '') {
    if ( !empty($strong) )
      $strong = "<strong>$strong</strong> ";

    $icon = self::$NOT_TYPE_TO_ICON[explode(' ', $type)[0]];
    return <<<END
<div class="alert $type bottom-20">
  <div class="alert-icon">
    <i class="fa fa-$icon"></i>
  </div>
  <div class="alert-content">
    <p>${strong} $text</p>
    $content
  </div>
</div>
END;

  }


  /**
   * @param  string $title
   * @param  string $text
   * @param  string $icon FontAwesome icon
   * @param  string $type '' | 'top' | 'alt'
   * @param  string $iconColor
   * @return string
   */
  static function buildFeature($title, $text, $icon, $type = '') {
    return <<<END
<div class="iconbox $type clearfix bottom-sm-30">
  <div class="iconbox-icon">
    <i class="fa fa-$icon"></i>
  </div><!-- // .iconbox-icon -->
  <div class="iconbox-content">
    <h4 class="bottom-10">$title</h4>
    <p>$text</p>
  </div><!-- // .iconbox-content -->
</div>
END;
  }


  /**
   * [buildListItems description]
   * @param  [type] $items
   * @return [type]
   */
  static function buildListItems($items) {
    $r = '';
    foreach ($items as $item) {
      $r .= '<li>'.$item.'</li>';
    }
    return $r;
  }


  /**
   * [buildInfoBox description]
   * @param  [type] $title
   * @param  [type] $text
   * @param  string $content
   * @return [type]
   */
  static function buildInfoBox($title, $text, $content = '', $type = '') {
    return <<<END
<div class="callout $type bottom-20">
  <div class="callout-content">
      <div class="pull-left">
        <h3 class="bottom-0">$title</h3>
        <p>$text</p>
      </div>
      $content
      <div class="clearfix"></div>
  </div><!-- // .callout-content -->
</div>
END;
  }


  /**
   * [buildInfoBox description]
   * @param  [type] $title
   * @param  [type] $text
   * @param  string $content
   * @return [type]
   */
  static function buildTakeActionBox($title, $text, $content = '') {
    if ( $title !== '' )
      $title = '<h2 class="bottom-20 white">'.$title.'</h2>';

    if ( $text !== '' )
      $text = '<p>'.$text.'</p>';

    return <<<END
<div class="widget action">
  $title
  $text

  $content
  <div class="clearfix"></div>
</div>
END;
  }


  /**
   * [echoTableRows description]
   * @param  [type] $arrTableRows
   * @return [type]
   */
  static function echoTableRows($arrTableRows) {
    foreach ($arrTableRows as $row) {
      echo '<tr>';
      foreach ($row as $value) {
        echo "<td>$value</td>";
      }

      echo '</tr>';
    }
  }


  /**
   * [currencyArrToString description]
   * @param  [type] $arrCurrencies
   * @return [type]
   */
  static function currencyArrToString($arrCurrencies) {
    if ( empty($arrCurrencies) )
      return ' - ' ;

    $arr = [];
    foreach ($arrCurrencies as $currency => $amount) {
      $arr[] = \Tbmt\Localizer::currencyFormat($amount, $currency);
    }

    return implode(', ', $arr);
  }


  /**
   * [accordion description]
   * @param  [type] $id
   * @param  [type] $items
   * @return [type]
   */
  static function accordion($id, $items) {
    $r = '<div class="accordion toggle bottom-sm-30">';
    $l = count($items);
    for ( $i = 0; $i < $l; $i += 2 ) {
      $y = $i+1;
      $r .= <<<END
<div class="panel accordion-item bottom-20">
  <div class="accordion-heading">
    <h5 class="accordion-title">
      <a class="collapsed" data-toggle="collapse" href="#$id$i">
        $items[$i]
      </a>
    </h5><!-- // .accordion-title -->
  </div><!-- // .accordion-heading -->

  <div id="$id$i" class="accordion-collapse collapse">
    <div class="accordion-body">
      $items[$y]
    </div><!-- // .accordion-body -->
  </div> <!-- // .collapse -->
</div>
END;
    }

    return $r;
  }


  /**
   * [accordion description]
   * @param  [type] $id
   * @param  [type] $items
   * @return [type]
   */
  static function testimonial($text, $infoName = '', $infoOrigin = '') {
    $info = '';
    if ( $infoName ) {
      $info = <<<END
    <div class="testimonial-info">
      <span class="name">$infoName</span>
      <span class="company">$infoOrigin</span>
    </div>
END;
    }

    return <<<END

<div class="testimonail-detail active">
  <div class="testimonial-content">
    <p>$text</p>
  </div><!-- // .testimonial-content -->
  $info
</div>
END;
  }

  static function buildMemberAddress(\Member $member) {
    $zipCode = \Tbmt\Base::encodeHtml($member->getZipCode());
    $street = \Tbmt\Base::encodeHtml($member->getStreet());
    $streetadd = \Tbmt\Base::encodeHtml($member->getStreetAdd());
    $city = \Tbmt\Base::encodeHtml($member->getCity());
    $country = \Tbmt\Base::encodeHtml($member->getCountry());

    if ( $streetadd )
      $streetadd .= '<br>';

    return <<<END
<address>
    $street<br>
    $streetadd
    $zipCode $city<br>
    $country
</address>
END;
  }

  static function buildBankAddress(\Member $member) {
    $name = \Tbmt\Base::encodeHtml($member->getBankName());
    $zipCode = \Tbmt\Base::encodeHtml($member->getBankZipCode());
    $street = \Tbmt\Base::encodeHtml($member->getBankStreet());
    $city = \Tbmt\Base::encodeHtml($member->getBankCity());
    $country = \Tbmt\Base::encodeHtml($member->getBankCountry());

    return <<<END
<address>
    $name<br>
    $street<br>
    $zipCode $city<br>
    $country
</address>
END;
  }

  /**
   * [buildPurchaseAgreements description]
   * @return [type]
   */
  static function buildPurchaseAgreements() {
    return \Tbmt\Localizer::getInsert('common.purchase_agreemensts',
      [
        'terms' => ''.self::buildLink(\Tbmt\Localizer::get('common.terms'), \Tbmt\Router::toModule('about', 'terms'), '', 'target="_blank"'),
        'privacy' => ''.self::buildLink(\Tbmt\Localizer::get('common.privacy'), \Tbmt\Router::toModule('about', 'privacy'), '', 'target="_blank"'),
        'cancelation_right' => ''.self::buildLink(\Tbmt\Localizer::get('common.cancelation_right'), \Tbmt\Router::toModule('about', 'cancelation_right'), '', 'target="_blank"'),
      ],
      false
    );
  }

  /**
   * loading indicator
   * @param  \Member $member
   * @return [type]
   */
  static function buildLoadingIndicator() {
    return <<<END
<i class="fa fa-cog fa-spin"></i>
END;
  }

  /**
   * [buildProductPrice description]
   * @return [type]
   */
  static function buildProductPrice() {
    return \Tbmt\Localizer::currencyFormat(\Transaction::$MEMBER_FEE, \Tbmt\Localizer::get('currency_symbol.'.\Transaction::$BASE_CURRENCY));
  }

  /**
   * [buildProductPrice description]
   * @return [type]
   */
  static function buildProductPdf() {
      return \Tbmt\Localizer::get('common.pdf_explanation').'<a href="'.\Tbmt\Router::toModule('download', 'hgillustration').'" style="color:#EF4A43;">'.
        \Tbmt\Localizer::get('common.pdf_explanation_link').'</a>';
  }

}

?>