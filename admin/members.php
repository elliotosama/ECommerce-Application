<?php
  session_start();
  if(isset($_SESSION['username'])) {
    include 'init.php';
    $do = isset($_GET['action']) ? $_GET['action'] : 'manage';
    
    if ($do == 'manage') { // manage page
    $statement = '';
    if(isset($_GET['activation']) && $_GET['activation'] == "true") {
      $statement = "SELECT userId, username, email, fullName, regStatus, Date from users where groupId != '1' AND regStatus = 0";
    } else {
      $statement = "SELECT userId, username, email, fullName, regStatus, Date from users where groupId != '1'";
    }
    $selectResult = $conn->query($statement);
    
    ?>
      <h2 class='text-center'>Manage Members</h2>
      <div class="container">
        <div class="responsive-table"></div>
          <table class="first-table table table-bordered">
            <tr>
              <td>#</td>
              <td>Username</td>
              <td>Email</td>
              <td>Full Name</td>
              <td>Registration Date</td>
              <td>Controls</td>
            </tr>
            <?php
            while($row = $selectResult->fetch_assoc()){
              echo "<tr>";
              echo "<td>" . $row['userId'] . "</td>";
              echo "<td>" . $row['username'] . "</td>";
              echo "<td>" . $row['email'] . "</td>";
              echo "<td>" . $row['fullName'] . "</td>";
              echo "<td>" . $row['Date'] . "</td>";
              echo "<td>";
              $id = $row['userId'];
                  echo "<a href='members.php?action=edit&id=$id' class='btn btn-primary m-1'><i class='fa fa-edit'></i>Edit</a>";
                  echo "<a href='members.php?action=delete&id=$id' class='btn btn-danger confirm-delete'><i class='fa fa-close'></i>Delete</a>";
                  if($row['regStatus'] == 0) {
                    echo "<a href='members.php?action=activate&id=$id' class='btn btn-info m-1'><i class='fa-solid fa-check'></i> Activate</a>";
                  }
                echo '</td>';
              echo '</tr>';
            }
            ?>
          </table>
        </div>
        <div class='d-flex justify-content-center mt-3'>
          <a href='members.php?action=add' class='btn btn-primary m-3'><i class="fa fa-plus"></i> add member</a>
      </div>
      </div>
    <?php }elseif ($do == 'add') {
      // adding member
      ?>
      <h1 class="text-center head mt-3">Add Member</h1>
      <div class="container">
        <form method="POST" action="members.php?action=insert" class="form-horizontal">
          <input type="hidden" name="userId" value="<?php echo $userId ?>">
          <div class="form-group">
              <label class="col-sm-3 control-label">Username</label>
              <div class="col-sm-10 item">
                <input type="text" name="username" placeholder="Username" class="form-control" autocomplete="off" required="required">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10 item">
                <input type="password" required="required" name="password" placeholder="password" class="form-control passwd" autocomplete="new-password">
                <i class="show-password fa-regular fa-eye-slash"></i>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Confirm Password</label>
              <div class="col-sm-10 item">
                <input type="password" required="required" name="confirmPassword" placeholder="Confirm Password" class="form-control" autocomplete="new-password">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">Email</label>
              <div class="col-sm-10 item">
                <input type="email" name="email" required="required" placeholder="Email" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">Full Name</label>
              <div class="col-sm-10 item">
                <input type="text" name="fullname" placeholder="Full Name" class="form-control">
              </div>
            </div>
            <div class="form-group item">
              <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Add Member" class="btn btn-primary">
              </div>
            </div>
        </form>
      </div>
      <?php
      // end adding member
    } elseif ($do == 'insert'){
      if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errorMessages = array();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        $fullName = $_POST['fullname'];
        $email = $_POST['email'];
        if(checkItem('username', 'users', $username)) {
          $errorMessages[] = 'The Username is exist';
        }
        if(empty($username) || strlen($username) <= 2 || strlen($username) > 20) {
          $errorMessages[] = 'Invalid Username';
        }
        if(empty($fullName) || strlen($fullName) <= 3){
          $errorMessages[] = 'Invalid Full Name';
        }
        if(empty($email)) {
          $errorMessages[] = 'Invalid Email';
        }
        if(empty($password) || strlen($password) <= 8 || $confirmPassword != $password) {
          $errorMessages[] = 'Invalid Password: Password Must Be Greater Than 8 Character And Can Not Be Empty';
        }
        echo '<div class="container">';
        if(sizeof($errorMessages) != 0) {
          foreach($errorMessages as $error) {
            echo '<div class="alert alert-danger">' . $error .'</div>';
          }
          echo "<form action='members.php?action=add' method='POST'>";
          echo "<button class='btn btn-primary'>Get Back</button>";
          echo "</form>";
        } else {
          $hasehdPassword = md5($password);
          $insertQuery = "INSERT INTO users(username, email, fullName, password, Date, regStatus) VALUES('$username','$email','$fullName','$hasehdPassword', now(), 1)";
          if($conn->query($insertQuery) === True) {
            echo '<div class="alert alert-success">The User Has Been Added Successfully</div>';
          }else {
            echo '<div class="alert alert-danger"> Something Went Wrong';
          }
        }
        echo "</div>";
      } else {
        echo "This Method Is Not Allowed In This Page";
      }
    } elseif ($do == 'edit') {
      
      $userId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
      $sql = "SELECT * FROM users WHERE userId='$userId' LIMIT 1";
      $result = $conn->query($sql);
      if($result->num_rows == 0) {
        $msg = 'There Is No User Have This Id';
        homeRedirectWhenError($msg);
      } else {
      $values = $result->fetch_assoc();
      ?>
        <h1 class="text-center head">Edit Member</h1>
        <div class="container">
          <form method="POST" action="?action=update" class="form-horizontal">
            <input type="hidden" name="userId" value="<?php echo $userId ?>">
            <div class="form-group item">
                <label class="col-sm-3 control-label">Username</label>
                <div class="col-sm-10">
                  <input type="text" name="username" placeholder="Username" class="form-control" autocomplete="off" value="<?php echo $values['username']?>" required="required">
                </div>
              </div>
              <div class="form-group item">
                <label class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                  <input type="password" name="password" placeholder="password" class="form-control" autocomplete="new-password">
                </div>
              </div>
              <div class="form-group item">
                <label class="col-sm-3 control-label">Email</label>
                <div class="col-sm-10">
                  <input type="email" name="email" value="<?php echo $values['email']?>" required="required" placeholder="Email" class="form-control">
                </div>
              </div>
              <div class="form-group item">
                <label class="col-sm-3 control-label">Full Name</label>
                <div class="col-sm-10">
                  <input type="text" name="fullname" value="<?php echo $values['fullName']?>" placeholder="Full Name" class="form-control">
                </div>
              </div>
              <div class="form-group item">
                <div class="col-sm-offset-2 col-sm-10">
                  <input type="submit" value="Save" class="btn btn-primary">
                </div>
              </div>
          </form>
        </div>
  <?php 
      }
    } elseif ($do == 'update') {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
          $username = $_POST['username'];
          $password = $_POST['password'];
          $updateQuery = '';
          $email = $_POST['email'];
          $fullName = $_POST['fullname'];
          $userid = $_POST['userId'];
          $hashedPassword = md5($password);
          // Form Validation
          $errors = array();
          if(empty($username) || strlen($username) <= 2 || strlen($username) > 20) {
            $errors[] = '<div class="alert alert-danger">Invalid username</div>';
          }
          if(empty($email)) {
            $errors[] = '<div class="alert alert-danger">Invalid Email</div>';
          }
          if(empty($fullName) || strlen($fullName) <= 3) {
            $errors[] = '<div class="alert alert-danger">Invalid Full Name</div>';
          }
          if(sizeof($errors) != 0) {
            foreach($errors as $error) {
              echo "<div class='container'>";
              echo "<div class='text-center'>";
              echo $error;
              echo "</dev>";
              echo "</dev>";
            }
          } else {
            if(strlen($password) == 0) {
              $updateQuery = "UPDATE users SET username = '$username', email = '$email',fullName = '$fullName' where userId = '$userid'";
            } else {
              $updateQuery = "UPDATE users SET username = '$username', email = '$email', password = '$hashedPassword', fullName = '$fullName' where userId = '$userid'";
            }
            if($conn->query($updateQuery) === TRUE) {
              echo "<div class='container'>";
              echo "<div class='text-center alert alert-success'>The Information Is Successfully</dev>";
              echo "</div>";
            }
          }
        } else {
          $msg = 'This Method Is Not Allowed In This Page';
          homeRedirectWhenError($msg);
        }
    }elseif ($do == 'activate') {
      echo '<div class="container">';
      $userId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
      if($userId != 0){
        if(!checkItem('userId', 'users', $userId)) {
          $msg = 'There Is No User With That Id';
          homeRedirectWhenError($msg);
        } else {
          $deleteStatement = "UPDATE users SET regStatus = 1 WHERE userId = '$userId'";
          if($conn->query($deleteStatement) === TRUE){
            echo "<div class='alert alert-success'>The user Updated Successfully</div>";
          }
        }
      }
      echo "</div>";
      echo '</div>';
    } elseif($do == 'delete') {
      echo "<div class='container'>";
      $userId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
      if($userId != 0){
        if(!checkItem('userId', 'users', $userId)) {
          $msg = 'There Is No User With That Id';
          homeRedirectWhenError($msg);
        } else {
          $deleteStatement = "DELETE from users WHERE userId = '$userId'";
          if($conn->query($deleteStatement) === TRUE){
            echo "<div class='alert alert-success'>The user Deleted Successfully</div>";
          }
        }
      }
      echo "</div>";
    }
    include $tpl.'footer.php';
  } else {
    header('Location: index.php');
    exit();
  } 
  ?>