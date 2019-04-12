<?php
$url = 'https://web.njit.edu/~hy276/beta/back/makeGradedExam.php';
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
    die("curl failed to makeGradedExam.php");
}

curl_close($ch);

echo $result;
