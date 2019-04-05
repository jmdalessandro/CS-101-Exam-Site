<?php 
//front

$url = 'https://web.njit.edu/~hy276/beta/back/selectExam.php';
//$url = 'https://web.njit.edu/~hy276/beta/mid/selectExam.php';

//var_dump($post);
$post = $_POST['exam_name'];
//var_dump($post);
$send = urlencode("exam_name=$post");
//var_dump($send);

$opts = [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_FOLLOWLOCATION => 1,
  CURLOPT_POST => 1,
  CULROPT_POSTFIELDS => $send
];

$ch = curl_init();

curl_setopt_array($ch, $opts);

$result = curl_exec($ch); //curl request doesn't send right

if ($result === false) {
  die("could not establish curl");
}

if (curl_errno($ch)) {
  die("error" . curl_error($ch));
}

curl_close($ch);

echo $result;
