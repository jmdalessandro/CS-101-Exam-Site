<?php 
  
  
  
  $dbhost = "sql1.njit.edu";
  $dbuser = "hy276";
  $dbpass = "FbRHBUeZ";
  $dbname = "hy276";
  //login to
  $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
  
  if (!$conn) {
    die("Cannot connect to DB: " . mysqli_connect_error());
  }
  
  $data = file_get_contents('php://input');
  $array = json_decode($data, true);
  $teacherComments = $array['addComments']; //additional comments
  $examName = $array['examName'];
  $partials = $array['partialScore'];  //have to explode and split into 
  $qComments = $array['qComments'];
  $score = $array['score'];
  $totalScore = 0; 
  $msg = "";
  
  $score = explode(",", $score);
  $qComments = explode(",", $qComments);
  $partials = explode(",", $partials);
  for ($i = 0; $i < count($partials); $i++) {
    $totalScore += $partials[$i]; 
  }
  
  
  $partial1 = $partials[0] . "," . $partials[1] . "," . $partials[2] . "," . $partials[3] . "," . $partials[4];

  $partial2= $partials[5] . "," . $partials[6] . "," . $partials[7] . "," . $partials[8] . "," . $partials[9];
 
  $partial3 = $partials[10] . "," . $partials[11] . "," . $partials[12] . "," . $partials[13] . "," . $partials[14];
  
  $itWorked = false;
  //update query
  $pointsQuery = "UPDATE `student_exam` SET `q1_score`='$score[0]',`q1_parts`='$partial1',`q2_score`='$score[1]',`q2_parts`='$partial2',`q3_score`='$score[2]',`q3_parts`='$partial3',`total_points`='$totalScore' WHERE `exam_name` = '$examName'";
  $commentQuery = "UPDATE `exam_comments` SET `q1Professor` = '$qComments[0]', `q2Professor` = '$qComments[1]', `q3Professor` = '$qComments[2]', `miscComments` = '$teacherComments' WHERE `exam_name` = '$examName'";
  
  if ($response = mysqli_query($conn, $pointsQuery)) {
    $itWorked = true;
  }
  else {
    $itWorked = false;
    $msg = "update to partial and points failed with error: " . mysqli_error($conn);
  }
  if ($response = mysqli_query($conn, $commentQuery)) {
    $itWorked = true;
  }
  else {
    $itWorked = false;
    $msg = "update to comments failed with error: " . mysqli_error($conn);
  }
  
  if (!isset($json)) {
    $json = new stdClass();
  }
  if ($itWorked === true) {
    $json->teacherComments = $teacherComments;
    $json->examName = $examName;
    $json->partials = $partials;
    $json->partial1 = $partial1;
    $json->partial2 = $partial2;
    $json->partial3 = $partial3;
    $json->qComments = $qComments;
    $json->score = $score;
    $json->totalScore = round($totalScore);
    $json->msg = "Updated";
  }
  else {
    $json->teacherComments = $teacherComments;
    $json->examName = $examName;
    $json->partials = $partials;
    $json->partial1 = $partial1;
    $json->partial2 = $partial2;
    $json->partial3 = $partial3;
    $json->qComments = $qComments;
    $json->score = $score;
    $json->totalScore = round($totalScore);
    $json->msg = "Not updated";
  }
  
  
  echo json_encode($json, true);
  
  mysqli_close($conn);
  
