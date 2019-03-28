<?php 
//front
error_reporting(-1);
ini_set('display_all',1);

$url = 'https://web.njit.edu/~hy276/beta/back/selectExam.php';
//phpinfo();
//var_dump($_POST); //apparently the post is empty from the javascript 

if (isset($_POST['exam_name'])) { 
  $exam_name = $_POST['exam_name'];
}

$post = array( 
  'exam_name' => $exam_name
);
//var_dump($post);
$send = json_encode($post);

$opts = array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_FOLLOWLOCATION => 1,
  CURLOPT_POST => 1,
  CULROPT_POSTFIELDS => $send
);

//var_dump($send);

$ch = curl_init();

curl_setopt_array($ch, $opts);

$result = curl_exec($ch);

if ($result === false) {
  die("could not establish curl");
}

curl_close($ch);

echo $result;

