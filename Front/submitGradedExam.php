<?php

  $data = file_get_contents('php://input');
  $url = 'https://web.njit.edu/~hy276/beta/back/submitGradedExam.php';
  $opts = [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data
  ];
  
  $ch = curl_init();
  
  curl_setopt_array($ch, $opts);
  
  $result = curl_exec($ch);
  if ($result === false) { 
    die("CURL failed to execute: " . curl_errno($ch));
  }
  
  curl_close($ch);
  
  echo $result;
