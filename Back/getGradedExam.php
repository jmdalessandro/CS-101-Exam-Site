<?php

$dbhost = "sql1.njit.edu";
$dbuser = "hy276";
$dbpass = "FbRHBUeZ";
$dbname = "hy276";
//login to
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
  die("Cannot connect to DB: " . mysqli_connect_error());
}

$query = "SELECT DISTINCT `exam_name` FROM `student_exam`";

$itWorked = false;

if ($response = mysqli_query($conn, $query)) {
  echo "<table id='examNameTbl'>
            <tr>
              <th>Exam Name </th>
              </tr>";

  while ($row = mysqli_fetch_array($response)) {
    $count = 0;
    echo "<tr>";
    echo "<td> <button type='button' name='". $row['exam_name'] ."' onclick='makeGradedExam(this);' style='width:100%'>" . $row['exam_name'] . "</button></td>";
    echo "</tr>";
  }
  echo "</table>";
}
else {
  die("Query failed");
}

mysqli_close($conn);

