<?php

namespace Tbmt\view;

class Factory {

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

}

?>