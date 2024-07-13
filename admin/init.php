<?php
  include 'connect.php';
  $tpl = 'includes/templates/';
  $css = 'layout/css/';
  $js = 'layout/js/';
  $lg = 'includes/languages/';
  $func = 'includes/functions/';
  include 'connect.php';
  include $lg.'english.php';
  include $tpl.'header.php';
  include $func . 'functions.php';
  if(!isset($noNavBar)) {
    include $tpl.'navbar.php';
  }
?>