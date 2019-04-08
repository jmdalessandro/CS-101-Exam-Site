<?php
//frontend side
error_reporting(-1);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$post = file_get_contents('php://input');

$url = 'https://web.njit.edu/~hy276/beta/back/filter.php';
$opts = [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_FOLLOWLOCATION => 1,
  CURLOPT_USERAGENT => 'hudson\'s request',
  CURLOPT_POST => 1,
  CURLOPT_POSTFIELDS => $post
];

$ch = curl_init();

curl_setopt_array($ch, $opts);

$result = curl_exec($ch);

if ($result === false) {
  die("CURL FAILED");
}

curl_close($ch);

echo $result;

