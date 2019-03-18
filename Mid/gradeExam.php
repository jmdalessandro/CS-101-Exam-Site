<?php

$finalGrade_url = 'https://web.njit.edu/~jmd35/beta/back/addGrade.php';
$gradingData_url = 'https://web.njit.edu/~jmd35/beta/back/getGradingData.php';
$getTestCases_url = 'https://web.njit.edu/~jmd35/beta/back/getGradingData.php';

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
    $points = 0.0;
    $comments = '';
    writeFile('file.py', $stuAns);
    $output = compileFile('file.py');
    switch($case){
        case 0:
            
            if (end($output) == 0 && $stuAns != null){
                $points ++;
                $comments = $comments."Program compiled. +".($points/5)*$maxPoints." Points\n";
                }
                
            else {
                $comments = $comments."Program did not compile. 0 Points";
                break;
                }
   
    
        case 1:
            
             if(strpos($stuAns, $funcName) == FALSE) {
                $answer = $stuAns;
                $funcName= $funcName;
                $result = preg_split('/def/', $answer);
                $result = $result[1];
                $eachWords = explode('(', trim($result));
                $eachWords1= $eachWords[0];
                $appendAnswer=$eachWords1;
                $stuAns= str_replace($appendAnswer,$funcName,$answer);
                $comments = $comments."\nFunction name is incorrect -".(1/5)*$maxPoints;
                writeFile('file.py', $stuAns);
               
                
              } else{
                $points+=1;
                $comments = $comments."\nThe function name matches! +".(1/5)*$maxPoints."\n";
              }
        
        case 2:
        
            $input1= $cases[$round]['input1'];
            $output1= $cases[$round]['output1'];
            $input2= $cases[$round]['input2'];
            $output2= $cases[$round]['output2'];
            
            
            
        
                   switch($case){
                      case 0:
                            appendFile('file.py',$input1);
                            $output = runTestCase('file.py');
                            
                            if ($output[0] == $output1){
                                  $comments = $comments."\nYour output for was: ".$output[$case]." Correct output: ".$output1." +".(((3/5)*$maxPoints)/2)."\n";
                                  $points+=1.5;
                            }
                            else{
                              $comments = $comments."\nYour output for was: ".$output[$case]." Correct output: ".$output1." -".(((3/5)*$maxPoints)/2)."\n";
                            }
                            
                      case 1:
                            writeFile('file.py', $stuAns);
                            appendFile('file.py',$input2);
                            $output = runTestCase('file.py');
                            if ($output[0] == $output2){
                                  $comments = $comments."\nYour output for was: ".$output[$case]." Correct output: ".$output2." +".(((3/5)*$maxPoints)/2)."\n";
                                  $points+=1.5;
                            }
                            else{
                              $comments = $comments."\nYour output for was: ".$output[$case]." Correct output: ".$output1." -".(((3/5)*$maxPoints)/2)."\n";
                            }
                  }
              
        }
            
       
            
    
    $points = $points/5.0;
    $points = $points*$maxPoints;
    $pArray = ['Points' => $points,
               'Comments' => $comments];
    return $pArray;

}
            

$getData = file_get_contents('php://input');

//$getData = array(
    //        'exam_name' => "Exam1");
 //$exam_name = $getData;
 


$array  = json_decode($getdata,true);
$exam_name = $array;
 
$getData = json_encode($getData, true);             
            
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
    $finalData [] = array ('Question' => $i, 'Points' => $gradeing['Points'],
                      );
    
}
$finalData ['Comments'] = $commentsTotal;
$finalData ['totalPoints'] = $totalPoints;
$finalData ['exam_name'] = $exam_name['exam_name']; 
$submitGrade = json_encode($finalData, true); 
$response = submitGrade($submitGrade, $finalGrade_url);

echo json_encode($response, true); 


                        
?>           
         
