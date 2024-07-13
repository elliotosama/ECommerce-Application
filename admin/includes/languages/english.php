<?php 


  function lang ($phrase) {
    static $arr = array (
      'HOME_ADMIN' => 'Home',
      'CATEGORIES' => 'Categories',
      'ITEMS' => 'Items',
      'MEMBERS' => 'Members',
      'COMMENTS' => 'Comments',
      'STATISTICS' => 'Statistics',
      'LOGS' => 'Logs',
      'SETTINGS' => 'Settings',
      'EDIT_PROFILE' => 'Edit Profile',
      'LOGOUT' => 'Logout',
    );
    return $arr[$phrase];
  }