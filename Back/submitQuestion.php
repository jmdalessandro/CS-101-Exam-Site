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
  
  
  $array  = json_decode($getdata,true);
  
  /*$array = ["qid1" => "22",
            "qid2" => "33",
            "qid3" => "44",
            "exam_name" => "Test",
            "points1" => "25",
            "points2" => "25",
            "points3" => "50"
               ];
  */
  
  
  $question_id1    = $array['qid1'];
  $question_id2     = $array['qid2'];
  $question_id3     = $array['qid3'];
  $exam_name       = $array['exam_name'];
  $points1           = $array['points1'];
  $points2         = $array['points2'];
  $points3           = $array['points3'];
  
  

  $query1 = "INSERT INTO `exams`(`exam_name`, `question_id`, `score`) VALUES ('$exam_name','$question_id1','$points1')";
  $query2 = "INSERT INTO `exams`(`exam_name`, `question_id`, `score`) VALUES ('$exam_name','$question_id2','$points2')";
  $query3 = "INSERT INTO `exams`(`exam_name`, `question_id`, `score`) VALUES ('$exam_name','$question_id3','$points3')";
  
   $itWorked = false;
  
  if ($response = mysqli_query($conn, $query1)) { //checks if the query worked
      //echo "<script>console.log('query worked')</script>";
      $itWorked = true;
  }
  
   if ($response = mysqli_query($conn, $query2)) { //checks if the query worked
      //echo "<script>console.log('query worked')</script>";
      $itWorked = true;
  }
   if ($response = mysqli_query($conn, $query3)) { //checks if the query worked
      //echo "<script>console.log('query worked')</script>";
      $itWorked = true;
  }
  //else {
    //  echo "<script>console.log('Something went wrong')</script>";
    //}
   //creating json to send back
   
  if (!isset($login_json)) {
    $login_json = new stdClass();
  }
  if ($itWorked) {
    
    $login_json->msg = "exam added";
  }
  
  else if ($itWorked === false) {
   
    $login_json->msg = "not added";
  }
  $login_json = json_encode($login_json, true);
  echo $login_json;
  
  mysqli_close($conn);
