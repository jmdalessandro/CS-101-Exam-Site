<?php

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  //test that the connection and info sent correctly
  //echo "<script>console.log('Curled to back')</script>";
  //print_r($_POST);

  $loginData = file_get_contents('php://input');

  $loginData = json_decode($loginData, true);

  $usr = $loginData['username'];
  $pw = $loginData['password'];

  //echo "<script>console.log('username=" . $usr . " password=" . $pw . "')</script>";

  //mysql credentials
  $dbhost = "sql1.njit.edu";
  $dbuser = "hy276";
  $dbpass = "HY9Co7Qkq";
  $dbname = "hy276";

  //login to
  $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

  if (!$conn) {
    die("Cannot connect to DB: " . mysqli_connect_error());
  }
  /* insert one teacher + student into database for testing (comment out after you do it once)
  $hashedPass = password_hash($pw, PASSWORD_DEFAULT);
  to insert an entry, you have to do one or the other(teacher or student), can't do both at the same time
  $insertEntry = mysqli_query($conn, "INSERT INTO `student_logins`(`ucid`, `Password`) VALUES ('$usr', '$hashedPass')");
  $insertEntry = mysqli_query($conn, "INSERT INTO `teacher_logins`(`ucid`, `Password`) VALUES ('$usr', '$hashedPass')");
  if ($insertEntry) {
    echo "<script>console.log('inserted an entry to the DB')</script>";
  }
  else
    echo "<script>console.log('insertion failed')</script>";
  */
  $login_query = "SELECT `ucid` FROM `student_logins` WHERE `ucid` = '$usr' OR
                          SELECT `ucid` FROM `teacher_logins` WHERE `ucid` = '$usr'";
  $student_login_query = "SELECT `ucid` FROM `student_logins` WHERE `ucid` = '$usr'";
  $teacher_login_query = "SELECT `ucid` FROM `teacher_logins` WHERE `ucid` = '$usr'";
  $studentHash = '$2y$10$bkfqjWpP6ioDIUQS8qIe9urzxFIdMKb3jGgWpViNUq02L0AIKfP6y'; // real pass = 123
  $teacherHash = '$2y$10$K0PdkAqs/WUx3onG9PUiVOogri9gNdJcy.g8QwsAGJ7IiXytJ/3m6'; //real pass = teacher
  $isTeacher = false;
  $isStudent = false;
  $isValid = false;

  if ($res_login_query = mysqli_query($conn, $login_query)) { //checks if the query worked
      //echo "<script>console.log('query for login credentials work')</script>";
  }
  if ( (mysqli_num_rows($res_login_query = mysqli_query($conn, $student_login_query))) > 0) { //checks if student ucid is used
    //echo "<script>console.log('student tried to log in')</script>";
    $isStudent = true;
    if (password_verify($pw, $studentHash)) {
      //echo "<script>console.log('password valid')</script>";
      $isValid = true;
    }
    else {
      //echo "<script>console.log('password invalid')</script>";
    }
  }
  else if ( (mysqli_num_rows($res_login_query = mysqli_query($conn, $teacher_login_query))) > 0) { //checks if teacher ucid is used
      //echo "<script>console.log('teacher tried to log in')</script>";
      $isTeacher = true;
      if (password_verify($pw, $teacherHash)) {
        //echo "<script>console.log('password valid')</script>";
        $isValid = true;
      }
      else {
        //echo "<script>console.log('password invalid')</script>";
      }
    }
  else {
    //echo "<script>console.log('someone not in the DB tried to log in')</script>";
  }

  //creating json to send back
  if (!isset($login_json)) {
    $login_json = new stdClass();
  }

  if ($isStudent && $isValid) {
    $login_json->whoami = "student";
    $login_json->msg = "valid login";
  }
  else if ($isTeacher && $isValid) {
    $login_json->whoami = "teacher";
    $login_json->msg = "valid login";
  }
  else if ($isValid === false) {
    //$login_json->whoami = "null";
    $login_json->msg = "invalid login";
  }

  $login_json = json_encode($login_json, true);

  echo $login_json;

  mysqli_close($conn);

?>
