<?php
//backend
error_reporting(-1);
ini_set('display_errors', 1);
//header('Content-Type: application/json');

$post = file_get_contents('php://input');
$array = json_decode($post, true);

$topic = $array['topic'];
$difficulty = $array['difficulty'];
//echo $topic;
//echo $difficulty;

$dbhost = "sql1.njit.edu";
$dbuser = "hy276";
$dbpass = "FbRHBUeZ";
$dbname = "hy276";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
  die("Cannot connect to DB: " . mysqli_connect_error());
}

$query = "SELECT * FROM `question_bank` "; //if both topic and difficulty are all or default
$itWorked = false;
if ($topic !== 'all') {
  $query = "SELECT * FROM `question_bank` WHERE `topic` = '$topic'";  
}
if ($difficulty !== 'all') {
  $query .= "AND `difficulty` = '$difficulty' ";
}
if ($difficulty !== 'all' and $topic === 'all') {
  $query = "SELECT * FROM `question_bank` WHERE `difficulty` = '$difficulty'";
}
//echo $query;
if ($response = mysqli_query($conn, $query)) {
  $itWorked = true;
  echo "<label for='exam_name'>Exam Name</label>";
	echo "<input type='text' id='exam_name'><br>";
	echo "<table id='addQuestionTbl'>
              <tr>
              <th>Check to add Question</th>
	      <th>Score</th>
              <th>Question ID</th>
              <th>Question Name</th>
	      <th>Question</th>
              <th>Topic</th>
              <th>Difficulty</th>
              </tr>";

        while ($row = mysqli_fetch_array($response)) {
          echo "<tr>";
          echo "<td> <input type='checkbox' id='checkbox'> </td>";
          echo "<td> <input type='text' id='points'> </td>";
	  echo "<td id='qid'>" . $row['question_id'] . "</td>";
          echo "<td id='qname'>" . $row['name'] . "</td>";
	  echo "<td id='qtext'>" . $row['question_text'] . "</td>";
          echo "<td id='top'>" . $row['topic'] . "</td>";
          echo "<td id='diff'>" . $row['difficulty'] . "</td>";
          echo "</tr>";

        }
        echo "</table>";
      }
      else {
        die("query failed to db:");
      }
      
mysqli_close($conn);
