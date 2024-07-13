<?php

session_start();

if(isset($_SESSION['username'])){ 
  include 'init.php';
  $do = isset($_GET['action']) ? $_GET['action'] : 'manage';
  if($do == 'manage') {
    $selectQuery = "SELECT * FROM categories";
    $result = $conn->query($selectQuery);
    ?>
    <h2 class='text-center'>Manage Members</h2>
    <div class="container">
      <div class="responsive-table"></div>
        <table class="first-table table table-bordered">
          <tr>
            <td>#</td>
            <td>Name</td>
            <td>Description</td>
            <td>Is Visible</td>
            <td>Comments Allowed</td>
            <td>Ads Allowed</td>
            <td>Actions</td>
          </tr>
          <?php
          while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            if($row['visibility'] == 1) {
              echo "<td>Yes</td>";
            } else {
              echo "<td>No</td>";
            }
            if($row['allow_comments'] == 1) {
              echo "<td>Yes</td>";
            } else {
              echo "<td>No</td>";
            }
            if ($row['allow_ads'] == 1) {
              echo "<td>Yes</td>";
            } else {
              echo "<td>No</td>";
            }
            echo "<td>";
            $id = $row['id'];
                echo "<a href='categories.php?action=edit&id=$id' class='btn btn-primary m-1'><i class='fa fa-edit'></i>Edit</a>";
                echo "<a href='categories.php?action=delete&id=$id' class='btn btn-danger confirm-delete-two'><i class='fa fa-close'></i>Delete</a>";
              echo '</td>';
            echo '</tr>';
          }
          ?>
        </table>
      </div>
      <div class='d-flex justify-content-center mt-3'>
        <a href='categories.php?action=add' class='btn btn-primary m-3'><i class="fa fa-plus"></i> add Category</a>
    </div>
    </div>
    <?php
  } elseif($do == 'add') {
    ?>
    <h1 class="text-center head mt-3">Add Category</h1>
      <div class="container">
        <form method="POST" action="categories.php?action=insert" class="form-horizontal">
          <div class="form-group">
              <label class="col-sm-3 control-label">Category</label>
              <div class="col-sm-10 item">
                <input type="text" name="name" placeholder="Category Name" class="form-control" autocomplete="off" required="required">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Description</label>
              <div class="col-sm-10 item">
                <input type="text" name="description" placeholder="Description" class="form-control" autocomplete="off">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">visibiliey</label>
              <div class="col-sm-10 item">
                <div>
                  <input type="radio" id="visyes" name="visiblity" value=1>
                  <label for="visyes">Yes</label>
                </div>
                <div>
                  <input type="radio" id="visno" name="visiblity" value=0 checked>
                  <label for="visno">No</label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">Allow Comments</label>
              <div class="col-sm-10 item">
                <div>
                  <input type="radio" id="commentyes" name="comments" value=1>
                  <label for="commentyes">Yes</label>
                </div>
                <div>
                  <input type="radio" id="commentno" name="comments" value=0 checked>
                  <label for="commentno">No</label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">Allow ads</label>
              <div class="col-sm-10 item">
                <div>
                  <input type="radio" id="adyes" name="ads" value=1>
                  <label for="adyes">Yes</label>
                </div>
                <div>
                  <input type="radio" id="adno" name="ads" value=0 checked>
                  <label for="adno">No</label>
                </div>
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
  } elseif ($do == "insert") {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $errorMessages = array();
      $name = $_POST['name'];
      $description = $_POST['description'];
      $allowComments = $_POST['comments'];
      $allowAds = $_POST['ads'];
      $isVisible = $_POST['visiblity'];
      if(checkItem('name', 'categories', $name)) {
        $errorMessages[] = 'The Category Name is exist';
      } 
      if(empty($name) || strlen($name) <= 2 || strlen($name) > 20) {
        $errorMessages[] = 'Invalid Category Name';
      }
      echo '<div class="container">';
      if(sizeof($errorMessages) != 0) {
        foreach($errorMessages as $error) {
          echo '<div class="alert alert-danger">' . $error .'</div>';
        }
        echo "<form action='categories.php?action=add' method='POST'>";
        echo "<button class='btn btn-primary'>Get Back</button>";
        echo "</form>";
      } else {
        $query = "INSERT INTO categories(name, description,visibility, allow_comments, allow_ads) values ('$name', '$description', '$isVisible', '$allowComments', '$allowAds')";
        if($conn->query($query) == TRUE) {
          echo "<div class='alert alert-info mt-10'>The Category Is Inserted Successfully</div>"; 
        } else {
          echo "<div class='alert alert-danger'>Something Went Wrong In The Database</div>";
        }
      }
      echo "</div>";
    } else {
      echo "This Method Is Not Allowed In This Page";
    }
  } elseif ($do == 'edit') {
    $id = intval($_GET['id']);
    $editQuery = "SELECT * FROM categories where id='$id' limit 1";
    $result = $conn->query($editQuery);
    $values = $result->fetch_row();
    ?>
    <h1 class="text-center head mt-3">Update Category</h1>
    <div class="container">
      <form method="POST" action="categories.php?action=update&id=<?php echo $id?>" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label">Category Name</label>
            <div class="col-sm-10 item">
              <input type="text" name="name" placeholder="Category Name" class="form-control" autocomplete="off" value="<?php echo $values[1]?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10 item">
              <input type="text" name="description" placeholder="Description" class="form-control" autocomplete="off" value="<?php echo $values[2]?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">visibiliey</label>
            <div class="col-sm-10 item">
              <div>
                <input type="radio" id="visyes" name="visiblity" value=1 <?php if($values[3] == 1) echo "checked"?>>
                <label for="visyes">Yes</label>
              </div>
              <div>
                <input type="radio" id="visno" name="visiblity" value=0 <?php if($values[3] == 0) echo "checked"?>>
                <label for="visno">No</label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Allow Comments</label>
            <div class="col-sm-10 item">
              <div>
                <input type="radio" id="commentyes" name="comments" value=1 <?php if($values[4] == 1) echo "checked"?>>
                <label for="commentyes">Yes</label>
              </div>
              <div>
                <input type="radio" id="commentno" name="comments" value=0 <?php if($values[4] == 0) echo "checked"?>>
                <label for="commentno">No</label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">Allow ads</label>
            <div class="col-sm-10 item">
              <div>
                <input type="radio" id="adyes" name="ads" value=1 <?php if($values[5] == 1) echo "checked"?>>
                <label for="adyes">Yes</label>
              </div>
              <div>
                <input type="radio" id="adno" name="ads" value=0 <?php if($values[5] == 0) echo "checked"?>>
                <label for="adno">No</label>
              </div>
            </div>
          </div>
          <div class="form-group item">
            <div class="col-sm-offset-2 col-sm-10">
              <input type="submit" value="Update Category" class="btn btn-primary">
            </div>
          </div>
      </form>
    </div>
    <?php
  } elseif ($do == "update") {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $errorMessages = array();
      $name = $_POST['name'];
      $id = $_GET['id'];
      $description = $_POST['description'];
      $allowComments = $_POST['comments'];
      $allowAds = $_POST['ads'];
      $isVisible = $_POST['visiblity'];
      if(empty($name) || strlen($name) <= 2 || strlen($name) > 20) {
        $errorMessages[] = 'Invalid Category Name';
      }
      echo '<div class="container">';
      if(sizeof($errorMessages) != 0) {
        foreach($errorMessages as $error) {
          echo '<div class="alert alert-danger">' . $error .'</div>';
        }
        echo "<form action='categories.php?action=add' method='POST'>";
        echo "<button class='btn btn-primary'>Get Back</button>";
        echo "</form>";
      } else {
        $updateQuery = "UPDATE categories SET name='$name', description='$description', visibility='$isVisible', allow_comments = '$allowComments', allow_ads = '$allowAds' where id='$id'";
        if($conn->query($updateQuery) == TRUE) {
          echo "<div class='alert alert-info mt-10'>The Category Is Inserted Successfully</div>"; 
        } else {
          echo "<div class='alert alert-danger'>Something Went Wrong In The Database</div>";
        }
      }
      echo "</div>";
    } else {
      echo "This Method Is Not Allowed In This Page";
    }
  } elseif ($do == 'delete') {
    echo "<div class='container'>";
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
    if($id != 0) {
      $deleteQuery ="DELETE FROM categories WHERE id='$id'";
      if($conn->query($deleteQuery) == TRUE) {
        echo "<div class='alert alert-info'>One Record Deleted Successfully</div>";
      } else {
        echo "<div class='alert alert-danger'>Something Went Wrong</div>";
      }
    } else {
      echo "<div class='alert alert-danger'>Invalid Category ID</div>";
    }
  }
  include $tpl . 'footer.php';
} else {
  header('Location: index.php');
  exit();
}