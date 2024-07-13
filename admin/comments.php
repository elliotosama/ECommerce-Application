<?php


session_start();
if(isset($_SESSION['username'])) {
  include 'init.php';
  $do = isset($_GET['action']) ? $_GET['action'] : "manage";
  if($do == 'manage') {
    $statement = 'SELECT 
                    comments.*, items.name
                  as 
                    itemName, users.username 
                  from 
                    comments 
                  inner join 
                    items 
                  on 
                    items.id = comments.itemId 
                  inner join 
                    users 
                  on 
                    users.userId = comments.userId;';
    $result = $conn->query($statement);
    $values = $result->fetch_all();
    ?>
      <h2 class='text-center'>Manage Comments</h2>
      <div class="container">
        <div class="responsive-table"></div>
          <table class="first-table table table-bordered">
            <tr>
              <td>#</td>
              <td>Comment</td>
              <td>Item Name</td>
              <td>User Name</td>
              <td>Date</td>
              <td>Controls</td>
            </tr>
            <?php
            foreach($values as $row){
              echo "<tr>";
              echo "<td>" . $row[0] . "</td>";
              echo "<td>" . $row[1] . "</td>";
              echo "<td>" . $row[6] . "</td>";
              echo "<td>" . $row[7] . "</td>";
              echo "<td>" . $row[3] . "</td>";
              echo "<td>";
              $id = $row[0];
                  echo "<a href='comments.php?action=edit&id=$id' class='btn btn-primary m-1'><i class='fa fa-edit'></i>Edit</a>";
                  echo "<a href='comments.php?action=delete&id=$id' class='btn btn-danger confirm-delete'><i class='fa fa-close'></i>Delete</a>";
                  if($row[2] == 0) {
                    echo "<a href='comments.php?action=approve&id=$id' class='btn btn-info m-1'><i class='fa-solid fa-check'></i> Approve</a>";
                  }
                echo '</td>';
              echo '</tr>';
            }
            ?>
          </table>
        </div>
      </div>
    <?php
    } else if ($do == 'edit') {
      $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
      $sql = "SELECT * FROM comments WHERE id='$id' LIMIT 1";
      $result = $conn->query($sql);
      if($result->num_rows == 0) {
        $msg = 'There Is No Comment Have This Id';
        homeRedirectWhenError($msg);
      } else {
      $values = $result->fetch_row();
      ?>
        <h1 class="text-center head">Edit Comment</h1>
        <div class="container">
          <form method="POST" action="?action=update" class="form-horizontal">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <div class="form-group item">
                <label class="col-sm-3 control-label">Comment</label>
                <div class="col-sm-10">
                  <input type="text" name="comment" placeholder="Comment" class="form-control" value="<?php echo $values[1]?>">
                </div>
              </div>
              <div class="form-group item">
                <label class="col-sm-3 control-label">Status</label>
                <div class="col-sm-10">
                  <input type="text" name="status" placeholder="Status" class="form-control" value="<?php echo $values[2]?>">
                </div>
              </div>
              <?php 
              $query = 'SELECT * FROM users';
              $result = $conn->query($query);
              $rows = $result->fetch_all();
            ?>
            <div class="form-group">
              <label class="col-sm-3 control-label">Users</label>
              <div class="col-sm-10">
                <select name="users" class="form-control">
                <?php 
                  foreach($rows as $row) {
                    echo "<option value='" . $row[0] . "'"; 
                    if($row[0] == $values[4]) {echo 'selected';}
                    echo ">" . $row[1] . "</option>";
                  }
                ?>
                </select>
              </div>
            </div>
            <?php 
              $query = 'SELECT * FROM items';
              $result = $conn->query($query);
              $rows = $result->fetch_all();
            ?>
            <div class="form-group">
              <label class="col-sm-3 control-label">Items</label>
              <div class="col-sm-10">
                <select name="items" class="form-control">
                <?php 
                  foreach($rows as $row) {
                    echo "<option value='" . $row[0] . "'"; 
                    if($row[0] == $values[5]) {echo 'selected';}
                    echo ">" . $row[1] . "</option>";
                  }
                ?>
                </select>
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
    }elseif ($do == 'update') {
      if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $comment = $_POST['comment'];
        $id = $_POST['id'];
        $status = $_POST['status'];
        $userId = $_POST['users'];
        $itemId = $_POST['items'];
        // Form Validation
        $errors = array();
        if(empty($name) || strlen($name) > 20) {
          $errorMessages[] = 'Invalid Product Name';
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
          $updateQuery = "UPDATE comments set comment = '$comment', status = '$status', userId = '$userId', itemId = '$itemId' where id = '$id'";
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
    } elseif ($do == 'delete') {
      echo "<div class='container'>";
      $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
      if($id != 0){
        if(!checkItem('id', 'comments', $id)) {
          $msg = 'There Is No Comment With That Id';
          homeRedirectWhenError($msg);
        } else {
          $deleteStatement = "DELETE from comments WHERE id = '$id'";
          if($conn->query($deleteStatement) === TRUE){
            echo "<div class='alert alert-success'>The Item Deleted Successfully</div>";
          }
        }
      }
    } elseif ($do == 'approve') {
      echo '<div class="container">';
      $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
      if($id != 0){
        if(!checkItem('id', 'comments', $id)) {
          $msg = 'There Is No Comment With That Id';
          homeRedirectWhenError($msg);
        } else {
          $approveStatement = "UPDATE comments SET status = 1 WHERE id = '$id'";
          if($conn->query($approveStatement) == TRUE){
            echo "<div class='alert alert-success'>The Comment Has Been Approved Successfully</div>";
          }
        }
      }
      echo "</div>";
      echo '</div>';
    }
  }