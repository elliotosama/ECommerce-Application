<?php


  session_start();
  if(isset($_SESSION['username'])) {
    include 'init.php';
    $do = isset($_GET['action']) ? $_GET['action'] : "manage";
    if($do == 'manage') {
      $statement = 'SELECT 
                        items.*, categories.name
                    as 
                        cat_name, users.username
                    from 
                        items
                    inner join 
                        users
                    on
                        users.userId = items.memberId
                    inner join 
                        categories 
                    on 
                        categories.id = items.categoryId;';
      $result = $conn->query($statement);
      ?>
        <h2 class='text-center'>Manage Products</h2>
        <div class="container">
          <div class="responsive-table"></div>
            <table class="first-table table table-bordered">
              <tr>
                <td>#</td>
                <td>Name</td>
                <td>Description</td>
                <td>Status</td>
                <td>Date</td>
                <td>Price</td>
                <td>Member Name</td>
                <td>Category</td>
                <td>Country</td>
                <td>Controls</td>
              </tr>
              <?php
              while($row = $result->fetch_assoc()){
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>" . $row['Date'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['cat_name'] . "</td>";
                echo "<td>" . $row['country'] . "</td>";
                echo "<td>";
                $id = $row['id'];
                    echo "<a href='items.php?action=edit&id=$id' class='btn btn-primary m-1'><i class='fa fa-edit'></i>Edit</a>";
                    echo "<a href='items.php?action=delete&id=$id' class='btn btn-danger confirm-delete'><i class='fa fa-close'></i>Delete</a>";
                    if($row['approved'] == 0) {
                      echo "<a href='items.php?action=approve&id=$id' class='btn btn-info m-1'><i class='fa-solid fa-check'></i> Approve</a>";
                    }
                  echo '</td>';
                echo '</tr>';
              }
              ?>
            </table>
          </div>
          <div class='d-flex justify-content-center mt-3'>
            <a href='items.php?action=add' class='btn btn-primary m-3'><i class="fa fa-plus"></i> add Item</a>
        </div>
        </div>
        <?php
    } elseif ($do == 'add') {
      ?>
      <h1 class="text-center head mt-3">Add Product</h1>
      <div class="container">
        <form method="POST" action="items.php?action=insert" class="form-horizontal">
          <div class="form-group">
              <label class="col-sm-3 control-label">Product Name</label>
              <div class="col-sm-10 item">
                <input type="text" name="name" placeholder="Product Name" class="form-control" required="required">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Description</label>
              <div class="col-sm-10 item">
                <input type="text" required="required" name="description" placeholder="Description" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">Price</label>
              <div class="col-sm-10 item">
                <input type="text" name="price" required="required" placeholder="Price" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">Country Of Manufacture</label>
              <div class="col-sm-10 item">
                <input type="text" name="country" placeholder="Country of Manufacture" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">Status Of The Product</label>
              <div class="col-sm-10">
                <select name="status" class="form-control">
                  <option value="0">...</option>
                  <option value="1">New</option>
                  <option value="2">Like New</option>
                  <option value="3">Used</option>
                  <option value="4">Old Version</option>
                </select>
              </div>
            </div>
            <?php 
              $query = "SELECT * FROM users";
              $result = $conn->query($query);
              $rows = $result->fetch_all();
            ?>
            <div class="form-group">
              <label class="col-sm-3 control-label">Members</label>
              <div class="col-sm-10">
                <select name="member" class="form-control">
                <option value="0">...</option>
                <?php 
                  foreach($rows as $row) {
                    echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
                  }
                ?>
                </select>
              </div>
            </div>
            <?php 
              $query1 = "SELECT * FROM categories";
              $result1 = $conn->query($query1);
              $rows1 = $result1->fetch_all();
            ?>
            <div class="form-group">
              <label class="col-sm-3 control-label">Categories</label>
              <div class="col-sm-10">
                <select name="category" class="form-control">
                  <option value="0">...</option>
                  <?php 
                    foreach($rows1 as $row1) {
                      echo "<option value='". $row1[0]."'> " . $row1[1]."</option>";
                    }
                  ?>
                </select>
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
    } elseif ($do == 'insert') {
      if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errorMessages = array();
        $name = $_POST['name'];
        $description= $_POST['description'];
        $price = $_POST['price'];
        $country = $_POST['country'];
        $status = $_POST['status'];
        $member = $_POST['member'];
        $category = $_POST['category'];
        if(empty($name) || strlen($name) <= 2 || strlen($name) > 20) {
          $errorMessages[] = 'Invalid Product Name';
        }
        if(empty($description) || strlen($description) <= 3){
          $errorMessages[] = 'Invalid Description';
        }
        if(empty($price)) {
          $errorMessages[] = 'Invalid Price';
        }
        if(empty($country)) {
          $errorMessages[] = 'Invalid Country';
        }
        if($status == 0) {
          $errorMessages = 'Please Choose Product Status';
        }
        if($member == 0) {
          $errorMessages = 'Please Add Member';
        }
        if($category == 0) {
          $errorMessages = 'Please add Category';
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
          $insertQuery = "INSERT INTO items(name, description, price, country, Date, status, memberId, categoryId) VALUES('$name','$description','$price','$country', now(), '$status', '$member', '$category')";
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
      $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
      $sql = "SELECT * FROM items WHERE id='$id' LIMIT 1";
      $result = $conn->query($sql);
      if($result->num_rows == 0) {
        $msg = 'There Is No User Have This Id';
        homeRedirectWhenError($msg);
      } else {
        $values = $result->fetch_row();
      }
      ?>
        <h1 class="text-center head">Edit Item</h1>
        <div class="container">
          <form method="POST" action="?action=update" class="form-horizontal">
            <input type="hidden" name="itemId" value="<?php echo $id ?>">
            <div class="form-group item">
                <label class="col-sm-3 control-label">Item Name</label>
                <div class="col-sm-10">
                  <input type="text" name="name" placeholder="Item Name" class="form-control" value="<?php echo $values[1]?>">
                </div>
              </div>
              <div class="form-group item">
                <label class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10">
                  <input type="text" name="description" placeholder="Description" class="form-control" value="<?php echo $values[2]?>">
                </div>
              </div>
              <div class="form-group item">
                <label class="col-sm-3 control-label">Country</label>
                <div class="col-sm-10">
                  <input type="text" name="country" value="<?php echo $values[4]?>" placeholder="Country Of Manufacutre" class="form-control">
                </div>
              </div>
              <div class="form-group item">
                <label class="col-sm-3 control-label">Price</label>
                <div class="col-sm-10">
                  <input type="text" name="price" value="<?php echo $values[3]?>" placeholder="Price Of The Product" class="form-control">
                </div>
              </div>
              <div class="form-group">
              <label class="col-sm-3 control-label">Status</label>
              <div class="col-sm-10">
                <select name="status" class="form-control">
                  <option value="1" <?php if($values[6] == 1) echo "selected" ?>>New</option>
                  <option value="2" <?php if($values[6] == 2) echo "selected" ?>>Like New</option>
                  <option value="3" <?php if($values[6] == 3) echo "selected" ?>>Used</option>
                  <option value="4" <?php if($values[6] == 4) echo "selected" ?>>Old Version</option>
                </select>
              </div>
            </div>
              <?php 
              $query = "SELECT * FROM users";
              $result = $conn->query($query);
              $rows = $result->fetch_all();
            ?>
            <div class="form-group">
              <label class="col-sm-3 control-label">Members</label>
              <div class="col-sm-10">
                <select name="member" class="form-control">
                <?php 
                  foreach($rows as $row) {
                    echo "<option value='" . $row[0] . "'"; 
                    if($row[0] == $values[7]) {echo 'selected';}
                    echo ">" . $row[1] . "</option>";
                  }
                ?>
                </select>
              </div>
            </div>
            <?php 
              $query = "SELECT * FROM categories";
              $result = $conn->query($query);
              $rows = $result->fetch_all();
            ?>
            <div class="form-group">
              <label class="col-sm-3 control-label">Categories</label>
              <div class="col-sm-10">
                <select name="category" class="form-control">
                <?php 
                  foreach($rows as $row) {
                    echo "<option value='" . $row[0] . "'"; 
                    if($row[0] == $values[8]) {echo 'selected';}
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
          <?php

          $stmt = "SELECT 
                    comments.*, users.username 
                  from 
                    comments
                  inner join 
                    users 
                  on 
                    users.userId = comments.userId
                  where itemId = '$values[0]'";
    $selectQuery = $conn->query($stmt);
    $selectQueryResult = $selectQuery->fetch_all();
    if(! empty($selectQueryResult)) {
      ?>
      <h2 class='text-center'>Manage [ <?php echo $values[1]?> ] Comments</h2>
        <div class="responsive-table"></div>
          <table class="first-table table table-bordered">
            <tr>
              <td>Comment</td>
              <td>User Name</td>
              <td>Date</td>
              <td>Controls</td>
            </tr>
            <?php
            foreach($selectQueryResult as $row2){
              echo "<tr>";
              echo "<td>" . $row2[1] . "</td>";
              echo "<td>" . $row2[6] . "</td>";
              echo "<td>" . $row2[3] . "</td>";
              echo "<td>";
              $id = $row2[0];
                  echo "<a href='comments.php?action=edit&id=$id' class='btn btn-primary m-1'><i class='fa fa-edit'></i>Edit</a>";
                  echo "<a href='comments.php?action=delete&id=$id' class='btn btn-danger confirm-delete'><i class='fa fa-close'></i>Delete</a>";
                  if($row2[2] == 0) {
                    echo "<a href='comments.php?action=approve&id=$id' class='btn btn-info m-1'><i class='fa-solid fa-check'></i> Approve</a>";
                  }
                echo '</td>';
              echo '</tr>';
            }
            ?>
          </table>
        <?php
        } else {
          echo "<div class='alert alert-primary text-center'>There Is No Comments On That Product</div>";
        }
        ?>
        </div>
  <?php 
    } elseif ($do == 'update') {
      if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $id = $_POST['itemId'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $country = $_POST['country'];
        $member = $_POST['member'];
        $category = $_POST['category'];
        $status = $_POST['status'];
        // Form Validation
        $errors = array();
        if(empty($name) || strlen($name) <= 2 || strlen($name) > 20) {
          $errorMessages[] = 'Invalid Product Name';
        }
        if(empty($description) || strlen($description) <= 3){
          $errorMessages[] = 'Invalid Description';
        }
        if(empty($price)) {
          $errorMessages[] = 'Invalid Price';
        }
        if(empty($country)) {
          $errorMessages[] = 'Invalid Country';
        }
        if($status == 0) {
          $errorMessages = 'Please Choose Product Status';
        }
        if($member == 0) {
          $errorMessages = 'Please Add Member';
        }
        if($category == 0) {
          $errorMessages = 'Please add Category';
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
          $updateQuery = "UPDATE items set name = '$name', description = '$description', country = '$country', price = '$price', memberId = '$member', categoryId = '$category', status = '$status' where id = '$id'";
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
        if(!checkItem('id', 'items', $id)) {
          $msg = 'There Is No Item With That Id';
          homeRedirectWhenError($msg);
        } else {
          $deleteStatement = "DELETE from items WHERE id = '$id'";
          if($conn->query($deleteStatement) === TRUE){
            echo "<div class='alert alert-success'>The Item Deleted Successfully</div>";
          }
        }
      }
      echo "</div>";
    } elseif ($do == 'approve') {

      echo '<div class="container">';
      $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
      if($id != 0){
        if(!checkItem('id', 'items', $id)) {
          $msg = 'There Is No User With That Id';
          homeRedirectWhenError($msg);
        } else {
          $approveStatement = "UPDATE items SET approved = 1 WHERE id = '$id'";
          if($conn->query($approveStatement) == TRUE){
            echo "<div class='alert alert-success'>The Item Has Been Approved Successfully</div>";
          }
        }
      }
      echo "</div>";
      echo '</div>';
    }
    include $tpl . 'footer.php';
  } else {
    header("Location: index.php");
    exit();
  }
