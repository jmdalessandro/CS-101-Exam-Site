<?php 
  //debug opts
  error_reporting(-1);
  //ini_set('display_errors',1);
  
//mysql credentials
  $dbhost = "sql1.njit.edu";
  $dbuser = "hy276";
  $dbpass = "FbRHBUeZ";
  $dbname = "hy276";
  
  //var_dump(file_get_contents("php://input"));
  
  $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
  
  if (!$conn) {
    die("Cannot connect to DB: " . mysqli_connect_error());
  }
  
  $query = "SELECT exam_name, question_text, score FROM exam_list, question_bank WHERE question_bank.question_id = exam_list.question_id "; // AND exam_name = '$exam_name'";
  //$query = "SELECT exam_name, question_text, score FROM exam_list, question_bank WHERE question_bank.question_id = exam_list.question_id AND exam_name = '$exam_name'";
  $itWorked = false;
  $exam_questions = [];
  $count = 0;
  
  if (!isset($json)) {
    $json = new stdClass();
  }
  
  if ($response = mysqli_query($conn, $query)) { //generate quiz from database
    $itWorked = true;
    //$json->exam_name = $exam_name;
    while($row = mysqli_fetch_array($response)) {
      $count++;
      $exam_name[$count] = $row['exam_name'];
      $exam_questions[$count] = $row['question_text'];
      $score[$count] = $row['score'];
      //print_r($row);
    }
  }
  else {
    die("query failed");
  }
  
  if ($itWorked) {
    $json->exam_name = $exam_name;
    $json->exam_questions = $exam_questions;
    $json->score = $score;
    $json->msg = "loading exam";
  }
  else if ($itWorked === false) {
    $json->msg = "could not load exam";
  }
  $encodedJSON = json_encode($json, true);
  echo $encodedJSON;
  
  
  mysqli_close($conn);
  
  ?>
