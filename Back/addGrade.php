<?php
 
 //mysql credentials
  $dbhost = "sql1.njit.edu";
  $dbuser = "hy276";
  $dbpass = "HY9Co7Qkq";
  $dbname = "hy276";
  //login to
  $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
  
  if (!$conn) {
    die("Cannot connect to DB: " . mysqli_connect_error());
  }
  
  $getdata = file_get_contents('php://input');
  
  //echo $getdata;
  
  $array  = json_decode($getdata,true);
  
  

  
  $q1_score    = $array[0]['Points'];
  $q2_score       = $array[1]['Points'];
  $q3_score            = $array[2]['Points'];
  $exam_comment       = $array['Comments'];
  $total_points          = $array['totalPoints'];
  $exam_name = $array['exam_name'];
  
  
  
  
  
  
  
  $query ="UPDATE `student_exam` SET `q1_score`='$q1_score',`q2_score`='$q2_score',`q3_score`='$q3_score',`exam_comment`= '$exam_comment',`total_points`='$total_points' WHERE `exam_name` = '$exam_name'";
  
  $itWorked = false;
  
  if ($response = mysqli_query($conn, $query)) { //checks if the query worked
      //echo "<script>console.log('query worked')</script>";
      $itWorked = true;
  }
  
  //else {
    //  echo "<script>console.log('Something went wrong')</script>";
    //}
   //creating json to send back
  $grade_json = array('msg' => "grades added");
  
  //if (!isset($grade_json)) {
  //  $grade_json = new stdClass();
  //}
  /*
  if ($itWorked) {
    
    $grade_json->'msg' => "grades added";
  }
  
  else if ($itWorked === false) {
   
    $grade_json->'msg' => "not added";
  }
  */
  $grade_json = json_encode($grade_json, true);
  echo $grade_json;
  mysqli_close($conn);

?>
