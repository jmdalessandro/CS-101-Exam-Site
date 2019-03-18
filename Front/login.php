<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  //globals
  $url = 'https://web.njit.edu/~jmd35/beta/login.php';
  $usr = $_POST['username'];
  $pw = $_POST['password'];
  $postData = [
    "username" => "$usr",
    "password" => "$pw",
  ];
  $postData = json_encode($postData);
  $login_opts = [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => $postData,
    CURLOPT_FOLLOWLOCATION => 1,
  ];

  //echo "<script>console.log('username=" . $usr . " password=" . $pw . "')</script>";

  //curl setup
  $ch = curl_init();

  curl_setopt_array($ch, $login_opts);

  $result = curl_exec($ch);

  if ($result === false) {
    die('request failed');
  }

  curl_close($ch);

  echo $result;
 ?>
