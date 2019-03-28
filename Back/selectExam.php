<?php 
  //debug opts
  error_reporting(-1);
  ini_set('display_all',1);
  //header('Content-type: application/json');
  
//mysql credentials
  $dbhost = "sql1.njit.edu";
  $dbuser = "hy276";
  $dbpass = "HY9Co7Qkq";
  $dbname = "hy276";
  
  $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
  
  if (!$conn) {
    die("Cannot connect to DB: " . mysqli_connect_error());
  }
  
  $data = file_get_contents('php://input'); //cant seem to find the json thats being sent
  //var_dump($data);
  $array = json_decode($data, true);
  $exam_name = $array['exam_name'];
  
  //echo "test";
  //var_dump($exam_name);
  
  $query = "SELECT exam_name, question_text FROM exams, question_bank WHERE question_bank.question_id = exams.question_id AND exam_name = '$exam_name'";
  $itWorked = false;
  $exam_questions = [];
  $count = 0;
  
  if (!isset($json)) {
    $json = new stdClass();
  }
  
  if ($response = mysqli_query($conn, $query)) { //generate quiz from information
    $itWorked = true;
    while($row = mysqli_fetch_array($response)) {
      $count++;
      $json->exam_name = $exam_name;
      $exam_questions[$count] = $row['question_text'];
    }
    $json->exam_questions = $exam_questions;
  }
  else {
    die("query failed");
  }
  
  if ($itWorked) {
    $json->msg = "loading exam";
  }
  else if ($itWorked === false) {
    $json->msg = "could not load exam";
  }
  $json = json_encode($json, true);
  echo $json;
  
  
  mysqli_close($conn);
  
  ?>
