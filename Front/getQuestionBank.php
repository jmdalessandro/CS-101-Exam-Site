<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$url = 'https://web.njit.edu/~jmd35/beta/getQuestionBank.php'; //change to this DIR
//$url = 'https://web.njit.edu/~hy276/beta/back/getQuestionBank.php'; //test DIR
$opts = [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_FOLLOWLOCATION => 1
];

$ch = curl_init();

curl_setopt_array($ch, $opts);

$result = curl_exec($ch);

if (!$result) {
  die('connection failed');
}

curl_close($ch);

echo $result;

 ?>
