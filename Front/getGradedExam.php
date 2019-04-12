<?php
$url = 'https://web.njit.edu/~hy276/beta/back/getGradedExam.php';
//$url = 'https://web.njit.edu/~hy276/beta/getGradedExam.php';
$opts = [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_FOLLOWLOCATION => 1
];

$ch = curl_init();

curl_setopt_array($ch, $opts);

$result = curl_exec($ch);

if ($result === false) {
  die("curl to get graded exam failed");
}

curl_close($ch);

echo $result;
