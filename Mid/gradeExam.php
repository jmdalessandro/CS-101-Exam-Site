<?php
$finalGrade_url = 'https://web.njit.edu/~hy276/beta/back/addGrade.php';
$gradingData_url = 'https://web.njit.edu/~hy276/beta/back/getGradingData.php';
$getTestCases_url = 'https://web.njit.edu/~hy276/beta/back/getGradingData.php';
$maxPoints = 0;
$totalPoints = 0;
$examPoints = array();
function submitGrade($data, $url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $resDecoded = json_decode($response, true);
    curl_close($ch);
    return $resDecoded;
}
function getGradingData($data, $url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $resDecoded = json_decode($response, true);
    curl_close($ch);
    return $resDecoded;
}
function getTestCases($data, $url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $resDecoded = json_decode($response, true);
    curl_close($ch);
    return $resDecoded;
}
function compileFile($pyFile){
    $cmd = 'python ./'.$pyFile;
    $output = array();
    exec($cmd, $output,$return_status);
    $output[] = $return_status;
    return $output;
}
function writeFile($pyFile, $studentAnswer){
    $handle = fopen($pyFile, 'w') or die("Can't open file");
    fwrite($handle, $studentAnswer);
    fclose($handle);
}
function appendFile($pyFile, $case){
    $handle = fopen($pyFile, 'a') or die("Can't append...");
    fwrite($handle,"\nprint(".$case.")");
    fclose($handle);
}
function runTestCase($pyFile){
    $cmd = 'python ./'.$pyFile;
    exec($cmd, $output);
    return $output;
}
function grader($case, $round, $stuAns, $funcName, $cases, $maxPoints){
    $partPoints = '';
    $qPoints = 0;
    $comments = '';
    writeFile('file.py', $stuAns);
    $output = compileFile('file.py');
    $divider = 0;
    if (strstr($cases[$round]['constraint'], "none") == FALSE){
        $divider = 5;
    }
    else{
        $divider = 4;
    }
    
    switch($case){
    
        case 0:
              if (strstr($cases[$round]['constraint'], "none") == FALSE){
                  
                  if (strstr($stuAns, $cases[$round]['constraint']) == TRUE) {
                  
                  $comments = $comments."Constraint Satisfied. +".(1/$divider)*$maxPoints." Points\n";
                  $points = (1/$divider)*$maxPoints;
                  $partPoints = $partPoints.$points;
                  $qPoints += $points;
                      
                  }
                  else{
                    $comments = $comments."Constraint Not Satisfied. -".(1/$divider)*$maxPoints." Points\n";
                    
                    $partPoints = $partPoints."0";
                  }
              }
        
        case 1:
        
              $answer = $stuAns;
              $eachWords = explode(')', trim($answer));
              $eachWords1 = explode(' ', trim($eachWords[1]));

              if (strstr($eachWords1[0], ":") == FALSE){
                  $orig = $eachWords[0].")";
                  $appendAnswer = $eachWords[0]."):";
                  $stuAns= str_replace($orig,$appendAnswer,$answer);
                  $comments = $comments."Forgot collon after function def. -".(1/$divider)*$maxPoints." Points\n";
                  if($divider == 4){
                  $partPoints = $partPoints."0";
                  }
                  else{
                  $partPoints = $partPoints.",0";
                  }
                  }
              else{
              $comments = $comments."Did not forget collon after function def. +".(1/$divider)*$maxPoints." Points\n";
              $points = (1/$divider)*$maxPoints;
                  if ($divider == 4){
                  $partPoints = $partPoints.$points;
                  }
                  else{
                  $partPoints = $partPoints.",".$points;
                  }
                  $qPoints += $points;
              }
              
              
        case 2:
            
            if (end($output) == 0 && $stuAns != null){
                $points = (1/$divider)*$maxPoints;
                  $partPoints = $partPoints.",".$points;
                  $qPoints += $points;
                $comments = $comments."Program compiled. +".(1/$divider)*$maxPoints." Points\n";
                }
                
            else {
                $comments = $comments."Program did not compile. 0 Points";
                break;
                }
   
    
        case 3:
            
             if(strpos($stuAns, $funcName) == FALSE) {
                $answer = $stuAns;
                $funcName= $funcName;
                $result = preg_split('/def/', $answer);
                $result = $result[1];
                $eachWords = explode('(', trim($result));
                $eachWords1= $eachWords[0];
                $appendAnswer=$eachWords1;
                $stuAns= str_replace($appendAnswer,$funcName,$answer);
                $comments = $comments."\nFunction name is incorrect -".(1/$divider)*$maxPoints." Points\n";
                $partPoints = $partPoints.",0";
                writeFile('file.py', $stuAns);
               
                
              } else{
                $points = (1/$divider)*$maxPoints;
                  $partPoints = $partPoints.",".$points;
                  $qPoints += $points;
                $comments = $comments."\nThe function name matches! +".(1/$divider)*$maxPoints." Points\n";
              }
        
        case 4:
            $casePoints = 0;
            $caseIn = [];
            $caseOut = [];
            
            for ($i=1; $i < 7; $i++){
            
            $in = 'input'.$i;
            $out = 'output'.$i;
            
            if($cases[$round][$in] == NULL){
              break;
            }
            $caseIn[$i-1] = $cases[$round][$in];
            $caseOut[$i-1] = $cases[$round][$out];
           
            }
            
            $numCases = count($caseIn);
            for ($i=0; $i < $numCases; $i++){
        
                            writeFile('file.py', $stuAns);
                            appendFile('file.py',$caseIn[$i]);
                            $output = runTestCase('file.py');
                            if ($output[0] == $caseOut[$i]){
                                  $comments = $comments."\nYour output for was: ".$output[$case]." Correct output: ".$caseOut[$i]." +".(((1/$divider)*$maxPoints)/$numCases)."\n";
                                  $points = ((1/$divider)*$maxPoints)/$numCases;
                                  $casePoints += $points;
                                 
                            }
                            else{
                              $comments = $comments."\nYour output for was: ".$output[$case]." Correct output: ".$caseOut[$i]." -".(((1/$divider)*$maxPoints)/$numCases)."\n";
                              
                            }
              
        }
          $qPoints += $casePoints;
          $partPoints = $partPoints.",".$casePoints;
     }    
       
            
    
    
    
    $pArray = [ 'PartPoints' => $partPoints,
                'Points' => $qPoints,
               'Comments' => $comments];
    return $pArray;
}
            

$getData = file_get_contents('php://input');

//$getData = array(
           'exam_name' => "Exam1");
 //$exam_name = $getData;
 


$array  = json_decode($getdata,true);
$exam_name = $array;
 
//$getData = json_encode($getData, true);             
            
$testData = getGradingData($getData,$gradingData_url);
  
           
  
  
$answers = array(
            $testData[0]['q1_answer'],
            $testData[0]['q2_answer'],
            $testData[0]['q3_answer'],
            );
$commentsTotal = '';
//echo $answers[1];
for ($i=0; $i < 3; $i++){
    $max_points += $testData[$i]['points'];
    $gradeing = grader(0, $i, $answers[$i], $testData[$i]['name'], $testData, $testData[$i]['points']);
    $commentsTotal = $commentsTotal."\n".$answers[$i]."\n".$gradeing['Comments'];
    $totalPoints += $gradeing['Points'];
    $finalData [] = array ('Question' => $i, 'Points' => $gradeing['Points'], 'PartPoints' => $gradeing['PartPoints']
                      );
    
}
$finalData ['Comments'] = $commentsTotal;
$finalData ['totalPoints'] = $totalPoints;
$finalData ['exam_name'] = $exam_name['exam_name']; 
$submitGrade = json_encode($finalData, true); 
$response = submitGrade($submitGrade, $finalGrade_url); 
echo $submitGrade;
echo json_encode($response, true); 
                        
?>           
         
