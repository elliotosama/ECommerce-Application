<link rel="stylesheet" href="layout/css/bootstrap.min.css">
<link rel="stylesheet" href="layout/css/backend.css">
<?php
  session_start();
  $pageTitle = 'dashboard';
  if(isset($_SESSION['username'])) {
    include 'init.php';
    ?>
    <div class="container home-statistics">
      <h1 class="text-center">Dashboard</h1>
      <div class="row">
        <div class="col-md-3">
          <div class="stat no-members"><a href="members.php">Nom Of Members <span><?php echo countItems('userId', 'users');?></span></a></div>
          </div>
        <div class="col-md-3">
          <div class="stat no-pending"><a href="members.php?activation=true">Pending Members <span><?php echo countItems('userId', 'users', 0);?></span></a></div>
        </div>
        <div class="col-md-3">
          <div class="stat no-items"><a href="items.php">Total Items <span><?php echo countItems('id', 'items');?></span></a></div>
        </div>
        <div class="col-md-3">
          <div class="stat no-comments"><a href="comments.php">Total Comments <span><?php echo countItems('id', 'comments');?></span></a></div>
        </div>
      </div>
    </div>
    <div class="container latest-members">
      <div class="row">
        <?php $numOfUsers = 5;?>
        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-users"></i> Latest <?php echo $numOfUsers ?> Members</div>
            <div class="panel-body">
              <ul class="list">
                <?php
                    $rows = getLatest('*', 'users', 'userId', 3);
                    foreach($rows as $row) {
                      echo "<li>" . $row[1];
                      echo "<div>";
                      echo "<a href='members.php?action=edit&id=$row[0]' class='btn btn-primary pull-right'><i class='fa fa-edit'></i>Edit</a>";
                      if($row[5] == 0) {
                        echo "<a href='members.php?action=activate&id=$row[0]' class='btn btn-info m-1'><i class='fa-solid fa-check'></i> Activate</a>";
                      }
                      echo "<a href='members.php?action=delete&id=$row[0]' class='btn btn-danger pull-right'><i class='fa fa-edit'></i>Delete</a>";
                      echo "</div>";
                      echo "</li>";
                    }
                ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-users"></i> Latest Products</div>
            <div class="panel-body">
            <ul class="list">
                <?php
                    $rows = getLatest('*', 'items', 'id', 3);
                    foreach($rows as $row) {
                      echo "<li>" . $row[1];
                      echo "<div>";
                      echo "<a href='items.php?action=edit&id=$row[0]' class='btn btn-primary pull-right m-1'><i class='fa fa-edit'></i>Edit</a>";
                      echo "<a href='items.php?action=delete&id=$row[0]' class='btn btn-danger pull-right'><i class='fa fa-close'></i>Delete</a>";
                      if($row[9] == 0) {
                        echo "<a href='items.php?action=approve&id=$row[0]' class='btn btn-info m-1'><i class='fa-solid fa-check'></i>Approve</a>";
                      }
                      echo "</div>";
                      echo "</li>";
                    }
                ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-users"></i> Latest Comments</div>
            <div class="panel-body">
                <?php
                  $statement = "SELECT
                                  comments.*, users.username
                                from 
                                  comments 
                                inner join 
                                  users 
                                on 
                                  users.userId = comments.userId";
                  $execute = $conn->query($statement);
                  $results = $execute->fetch_all();
                  foreach($results as $comment) {
                    echo "<div class='comments-box'>";
                      echo "<div class='member-name text-center'>" . $comment[6] . "</div>";
                      echo "<p class='member-comment'>" . $comment[1] . "</p>"; 
                    echo "</div>";
                  }
                ?>
            </div>
          </div>
        </div>
      </div>
      
    </div>
    <?php
    include $tpl.'footer.php';
  } else {
    header('Location: index.php');
  }

  ?>
