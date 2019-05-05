<?php
 
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
  
  $getdata = file_get_contents('php://input');
  
  
  $array  = json_decode($getdata,true);
  
  //$exam_name = "Exam2";
 $exam_name = $array['examName'];
 //echo "hello";
  
  $query1 = "SELECT * FROM `exam_list`,`student_exam`,`question_bank` WHERE `exam_list`.`exam_name` = `student_exam`.`exam_name` AND `exam_list`.`exam_name` = '$exam_name' AND `exam_list`.`question_id` = `question_bank`.`question_id`";
  
 //$query1 = "INSERT INTO `exam_list`(`exam_name`, `question_id`, `score`, `taken`) VALUES ('Exam2',44,55,1)";
 
 //$query1 = "SELECT * FROM `student_exam`";
 
 
  
 $response = mysqli_query($conn, $query1) or die($myQuery."<br/><br/>".mysql_error()); 
  
//while ($rows = mysqli_fetch_array($response, MYSQLI_NUM)) {
//    var_dump($rows);
//}

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
            'input3' => $row['input3'],
            'output3' => $row['output3'],
            'input4' => $row['input4'],
            'output4' => $row['output4'],
            'input5' => $row['input5'],
            'output5' => $row['output5'],
            'input6' => $row['input6'],
            'output6' => $row['output6'],
            'constraint' => $row['constraint'],
            'name'=> $row['name']
            
        );
    }
} else {
    echo "0 results";
}

//echo $output[1]['question_id'];
$output = json_encode($output, true);
/*
switch (json_last_error()) {
    case JSON_ERROR_NONE:
        echo ' - No errors';
    break;
    case JSON_ERROR_DEPTH:
        echo ' - Maximum stack depth exceeded';
    break;
    case JSON_ERROR_STATE_MISMATCH:
        echo ' - Underflow or the modes mismatch';
    break;
    case JSON_ERROR_CTRL_CHAR:
        echo ' - Unexpected control character found';
    break;
    case JSON_ERROR_SYNTAX:
        echo ' - Syntax error, malformed JSON';
    break;
    case JSON_ERROR_UTF8:
        echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
    break;
    default:
        echo ' - Unknown error';
    break;
}
*/

echo $output;

mysqli_close($conn);
?>
