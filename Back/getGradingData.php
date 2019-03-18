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
  
  //$exam_name = "Exam1";
  $exam_name = $array['exam_name'];
  
  $query1 = "SELECT * FROM `exams`,`student_exam`,`question_bank` WHERE `exams`.`exam_name` = `student_exam`.`exam_name` AND `exams`.`exam_name` = '$exam_name' AND `exams`.`question_id` = `question_bank`.`question_id`";
  
  $response = mysqli_query($conn, $query1);
  
  if ($response->num_rows > 0) {
    while ($row = $response->fetch_assoc()) {
    
        $output[] = array(
            'question_id' => $row['question_id'],
            'points' => $row['score'],
            'q1_answer' => $row['q1_answer'],
            'q2_answer' => $row['q2_answer'],
            'q3_answer' => $row['q3_answer'],
            'input1' => $row['input1'],
            'output1' => $row['output1'],
            'input2' => $row['input2'],
            'output2' => $row['output2'],
            'name'=> $row['name']
            
        );
    }
} else {
    echo "0 results";
}

//echo $output;

echo json_encode($output, true);
mysqli_close($conn);
?>
