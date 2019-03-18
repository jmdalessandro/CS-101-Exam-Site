<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbhost = "sql1.njit.edu";
$dbuser = "hy276";
$dbpass = "HY9Co7Qkq";
$dbname = "hy276";
//login to
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
  die("Cannot connect to DB: " . mysqli_connect_error());
}

$query = "SELECT DISTINCT `exam_name` FROM `exams`";

$itWorked = false;

if ($response = mysqli_query($conn, $query)) {
  echo "<table>
  	    <tr>
	      <th>Exam Name </th>
	      </tr>";

  while ($row = mysqli_fetch_array($response)) {
    echo "<tr>";
    echo "<td>" . $row['exam_name'] . "</td>";
    echo "<td> <button type='button' id='take_exam' onclick='selectExam()>Take this exam</button> </td>";
    echo "</tr>";
  }
  echo "</table>";
}
else {
  die("Query failed");
}

mysqli_close($conn);
  












 ?>
