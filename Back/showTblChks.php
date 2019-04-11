
    <?php
    //BACK PHP
      error_reporting(E_ALL);
      ini_set('display_errors', 1);

      $dbhost = "sql1.njit.edu";
      $dbuser = "hy276";
      $dbpass = "FbRHBUeZ";
      $dbname = "hy276";
      //login to
      $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

      if (!$conn) {
        die("Cannot connect to DB: " . mysqli_connect_error());
      }

      $query = "SELECT * FROM `question_bank`";

      if ($response = mysqli_query($conn, $query)) {
        echo "<label for='exam_name'>Exam Name</label>";
	      echo "<input type='text' id='exam_name'><br>";
	      echo "<table id='TblChks'>
              <tr>
              <th>Check to add Question</th>
	            <th>Score</th>
              <th>Question ID</th>
              <th>Question Name</th>
	            <th>Question</th>
              <th>Topic</th>
              <th>Difficulty</th>
              <th>Constraint</th>
              </tr>";

        while ($row = mysqli_fetch_array($response)) {
          echo "<tr>";
          echo "<td> <input type='checkbox' id='checkbox' > </td>";
          echo "<td> <input type='text' id='points'> </td>";
	        echo "<td id='qid'>" . $row['question_id'] . "</td>";
          echo "<td id='qname'>" . $row['name'] . "</td>";
	        echo "<td id='qtext'>" . $row['question_text'] . "</td>";
          echo "<td id='top'>" . $row['topic'] . "</td>";
          echo "<td id='diff'>" . $row['difficulty'] . "</td>";
          echo "<td id='constraint'>" . $row['constraint'] . "</td>";
          echo "</tr>";

        }
        echo "</table>";
      }
      else {
        die("query failed to db:");
      }

      mysqli_close($conn);

     ?>
