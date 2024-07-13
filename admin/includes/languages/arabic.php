<?php

  function lang ($phrase) {
    static $arr = array (
      'message' => 'السلام عليكم',
      'admin' => 'المدير',
    );
    return $arr[$phrase];
  }