<nav class="navbar navbar-expand-lg navbar-light bg-light nav-style">
  <a class="navbar-brand" href="<?php echo 'dashboard.php'?>"><?php echo lang("HOME_ADMIN")?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="categories.php"><?php echo lang('CATEGORIES')?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="comments.php"><?php echo lang('COMMENTS')?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="items.php"><?php echo lang('ITEMS')?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="members.php"><?php echo lang('MEMBERS')?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><?php echo lang('STATISTICS')?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><?php echo lang('LOGS')?></a>
      </li>
      <div class="dropdown nav-item">
	      <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        <?php echo "Osama"?>
      </button>
	      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
		      <li><a class="dropdown-item" href="members.php?action=edit&id=<?php echo $_SESSION['id']?>"><?php echo lang('EDIT_PROFILE')?></a></li>
		      <li><a class="dropdown-item" href="#"><?php echo lang('SETTINGS')?></a></li>
		      <li><a class="dropdown-item" href="logout.php"><?php echo lang('LOGOUT')?></a></li>
	      </ul>
	    </div>
    </ul>
  </div>
</nav>