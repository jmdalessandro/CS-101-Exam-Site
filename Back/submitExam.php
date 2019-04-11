<?php 
  //back
  error_reporting(-1);
  ini_set('display_all', 1);
//mysql credentials
  $dbhost = "sql1.njit.edu";
  $dbuser = "hy276";
  $dbpass = "FbRHBUeZ";
  $dbname = "hy276";
  //login to
  $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
  
  if (!$conn) {
    die("Cannot connect to DB: " . mysqli_connect_error());
  }

$data = file_get_contents("php://input");

$array = json_decode($data, true);

$examName = $array['examName'];
$answer1 = $array['answer1'];
$answer2 = $array['answer2'];
$answer3 = $array['answer3'];

$answers = [];
for ($i = 0; $i < 3; $i++) {
  $answers[$i] = ${'answer' . $i}; 
}

$query = "INSERT INTO `student_exam` (`exam_name`, `q1_answer`, `q2_answer`, `q3_answer`) VALUES ('$examName', '$answer1', '$answer2', '$answer3')";
$itWorked = false;

if ($response = mysqli_query($conn, $query)) {
  $itWorked = true;
}
if (!isset($json)) {
  $json = new stdClass();
}
if ($itWorked) {
  //$json->data = $answers;
  $json->msg = "submission passed";
}
else {
  //$json->data = $answers;
  $json->msg = "submission failed";
}

mysqli_close($conn);

echo json_encode($json, true);
