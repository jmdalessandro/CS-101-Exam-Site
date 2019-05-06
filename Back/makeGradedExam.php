<?php
//debug opts
error_reporting(-1);
//ini_set('display_errors',1);

$data = trim(file_get_contents("php://input"), "\x0");
$array = json_decode($data, true);
$exam_name = $array['examName']; 

//mysql credentials
$dbhost = "sql1.njit.edu";
$dbuser = "hy276";
$dbpass = "FbRHBUeZ";
$dbname = "hy276";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
  die("Cannot connect to DB: " . mysqli_connect_error());
}

$query = "SELECT * FROM `exam_list`,`student_exam`,`question_bank` WHERE `exam_list`.`exam_name` = `student_exam`.`exam_name`
          AND `exam_list`.`exam_name` = '$exam_name' AND `exam_list`.`question_id` = `question_bank`.`question_id`";
$commentQuery = "SELECT * FROM `exam_comments`";
$itWorked = false;
$exam_answers = $exam_scores = $partials = $totalScore = $exam_comments = $exam_questions = [];
$count = 0;
$commentCount = 1;
$questionCount = 1;

if (!isset($json)) {
  $json = new stdClass();
}
if ($response = mysqli_query($conn, $commentQuery)) {
  $itWorked = true;
  while($row = mysqli_fetch_array($response)) {
    $count++;
    for ($questionCount = 1; $questionCount <= 3; $questionCount++) {
      for ($commentCount = 1; $commentCount <= 5; $commentCount++) {
        array_push($exam_comments, $row['q' . $questionCount . 'C' . $commentCount]);
      }
    }
  }
}

$count = 0;
if ($response = mysqli_query($conn, $query)) { //generate quiz from database
  $itWorked = true;
  $json->exam_name = $exam_name;
  while($row = mysqli_fetch_array($response)) {
    $count++;
    $exam_questions[$count] = $row['question_text'];
    $exam_answers[$count] = $row['q' . $count . '_answer'];
    $exam_scores[$count] = $row['q' . $count . '_score'];
    $partials[$count] = $row['q' . $count . '_parts'];
    $totalScore[$count] = $row['score'];
    //$exam_comments = $row['exam_comment']; 
  }
}
else {
  die("query failed");
}

if ($itWorked) {
  $json->exam_name = $exam_name;
  $json->exam_questions = $exam_questions;
  $json->exam_answers = $exam_answers;
  $json->exam_scores = $exam_scores;
  $json->partials = $partials;
  $json->totalScore = $totalScore;
  $json->exam_comments = $exam_comments; 
  $json->msg = "got graded exam";
}
else if ($itWorked === false) {
  $json->msg = "no graded exam";
}
$encodedJSON = json_encode($json, true);
echo $encodedJSON;


mysqli_close($conn);

?>
