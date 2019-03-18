<?php
//front
error_reporting(E_ALL);
ini_set('display_errors', 1);

//needs to be directed to mid
//$url = 'http://web.njit.edu/~hy276/beta/back/submitQuestion.php';
$url = 'http://web.njit.edu/~jmd35/beta/submitQuestion.php';

if (isset($_POST['exam_name'])) {
  $exam_name = $_POST['exam_name'];
}
if (isset($_POST['qid1'])) {
  $qid1 = $_POST['qid1'];
}
if (isset($_POST['points1'])) {
  $points1 = $_POST['points1'];
}
if (isset($_POST['qid2'])) {
  $qid2 = $_POST['qid2'];
}
if (isset($_POST['points2'])) {
  $points2 = $_POST['points2'];
}
if (isset($_POST['qid3'])) {
  $qid3 = $_POST['qid3'];
}
if (isset($_POST['points3'])) {
  $points3 = $_POST['points3'];
}

$post = [
  "exam_name" => "$exam_name",
  "qid1" => "$qid1",
  "points1" => "$points1",
  "qid2" => "$qid2",
  "point2" => "$points2",
  "qid3" => "$qid3",
  "points3" => "$points3",
];

$send = json_encode($post, true);

//`echo $send;

$opts = [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_FOLLOWLOCATION => 1,
  CURLOPT_POST => 1,
  CURLOPT_POSTFIELDS => $send

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
