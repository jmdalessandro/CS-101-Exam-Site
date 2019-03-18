<?php

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  if (isset($_POST['topic'])) {
    $topic = $_POST['topic'];
  }
  if (isset($_POST['question_text'])) {
    $question_text = $_POST['question_text'];
  }
  if (isset($_POST['difficulty'])) {
    $difficulty = $_POST['difficulty'];
  }
  if (isset($_POST['parameters'])) {
    $parameters = $_POST['parameters'];
  }
  if (isset($_POST['input1'])) {
    $input1 = $_POST['input1'];
  }
  if (isset($_POST['output1'])) {
    $output1 = $_POST['output1'];
  }
  if (isset($_POST['input2'])) {
    $input2 = $_POST['input2'];
  }
  if (isset($_POST['output2'])) {
    $output2 = $_POST['output2'];
  }

  $postData = [
    "topic" => "$topic",
    "question_text" => "$question_text",
    "difficulty" => "$difficulty",
    "parameters" => "$parameters",
    "input1" => "$input1",
    "output1" => "$output1",
    "input2" => "$input2",
    "output2" => "$output2",
  ];

$url = 'https://web.njit.edu/~jmd35/beta/createQuestion.php';
//$url = 'https://web.njit.edu/~hy276/beta/back/createQuestion.php';
//$url = 'https://web.njit.edu/~hy276/beta/front/test.php';

$postData = json_encode($postData);

$opts = [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_FOLLOWLOCATION => 1,
  CURLOPT_POST => 1,
  CURLOPT_POSTFIELDS => $postData
];

$ch = curl_init();

curl_setopt_array($ch, $opts);

$result = curl_exec($ch);

if ($result === false) {
  die('request failed');
}

curl_close($ch);

echo $result;











 ?>
