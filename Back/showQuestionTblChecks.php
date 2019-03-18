<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen">
      table {
        width: 100%;
        border-collapse: collapse;
      }
      table, td, th {
        border: 1px solid block;
        padding: 5px;
      }
      th {
        text-align: left;
      }
    </style>
  </head>
  <body>
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

      $query = "SELECT * FROM `question_bank`";

      if ($response = mysqli_query($conn, $query)) {
        echo "<table id='addQuestionTbl'>
              <tr>
              <th>Check to add Question</th>
              <th>Question ID</th>
              <th>Parameters</th>
              <th>Question</th>
              <th>Topic</th>
              <th>Difficulty</th>
              </tr>";

        while ($row = mysqli_fetch_array($response)) {
          echo "<tr>";
          echo "<td> <input type='checkbox' id='checkbox'> </td>";
          echo "<td>" . $row['question_id'] . "</td>";
          echo "<td>" . $row['parameters'] . "</td>";
          echo "<td>" . $row['question_text'] . "</td>";
          echo "<td>" . $row['topic'] . "</td>";
          echo "<td>" . $row['difficulty'] . "</td>";
          echo "</tr>";

        }
        echo "</table>";
      }
      else {
        die("query failed to db:");
      }

      mysqli_close($conn);

     ?>
  </body>
</html>
