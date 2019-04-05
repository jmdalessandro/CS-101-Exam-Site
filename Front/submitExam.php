<?php 
//front
$url = 'https://web.njit.edu/~hy276/beta/mid/submitExam.php';
$data = file_get_contents("php://input");

$opts = [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_FOLLOWLOCATION => 1,
  CURLOPT_POST => 1,
  CURLOPT_POSTFIELDS => $data
];

$ch = curl_init();

curl_setopt_array($ch, $opts);

$result = curl_exec($ch);

if ($result === false) {
  die("curl didnt work");
}

echo $result;
