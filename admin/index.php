<?php
  $noNavBar = true;
  session_start();
  if(isset($_SESSION['username'])) {
    header('Location: dashboard.php');
  }
  include 'init.php';
  $pageTitle = 'login';
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['pass'];
    $hashedPassword = md5($password);
    $groupid = 1;
    $sql = "SELECT userId, username, password, groupId FROM users WHERE username='$username' AND password = '$hashedPassword' AND groupId = '$groupid' LIMIT 1";
    $result = $conn->query($sql);
    if($result->num_rows > 0) {
      $_SESSION['id'] = $result->fetch_assoc()['userId'];
      $_SESSION['username'] = $username;
      header('Location: dashboard.php');
    }
  }
?>
<form class="login" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
  <h4 class="text-center">Admin Login</h4>
  <input type="text" name="username" class="form-control input-lg" autocomplete="off" placeholder="username"/>
  <input type="password" name="pass" class="form-control input-lg" autocomplete="new-password" placeholder="password"/>
  <input type="submit" value="Login" class="btn btn-primary btn-block" />
</form>
<?php include $tpl . "footer.php"; ?>


