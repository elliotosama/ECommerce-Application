<?php 


function printTitle() {
  global $title;
  if(isset($title)) {
    return $title;
  } else {
    return 'default';
  }
}

function homeRedirectWhenError($errorMessage, $seconds = 3) {
  echo "<div class='container mt-10'>";
  echo "<div class='alert alert-danger'>$errorMessage</div>";
  echo "<div class='alert alert-info'>You Will Be Redirected After $seconds Seconds</div>";
  echo "</div>";
  sleep($seconds);
  header("refresh: $seconds; url=index.php");
  exit();
}


function checkItem($column, $table, $condition) {
  global $conn;
  $statement = "SELECT $column from $table where $column = '$condition'";
  $result = $conn->query($statement);
  if($result->num_rows == 1) {
    return true;
  } else {
    return false;
  }
}

function countItems ($item, $table, $condition = null) {
  global $conn;
  if($condition === null) {
    $selectQuery = "SELECT COUNT($item) from $table";
  } else {
    $selectQuery = "SELECT COUNT($item) from $table where regStatus = '$condition'";
  }
  $result = $conn->query($selectQuery);
  return $result->fetch_column();
}

function getLatest($columns, $table, $order, $limit = 5) {
  $query = "SELECT $columns FROM $table ORDER BY $order DESC limit $limit";
  global $conn;
  $result = $conn->query($query);
  $rows = $result->fetch_all();
  return $rows;
}