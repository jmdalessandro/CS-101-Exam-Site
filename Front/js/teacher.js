function SwapDivs(div1, div2) {
  var d1 = document.getElementById(div1);
  var d2 = document.getElementById(div2);
  if (d2.style.display == "none") {
    d1.style.display = "none";
    d2.style.display = "block";
  }
  else {
    d1.style.display = "block";
    d2.style.display = "none";
  }
}

function setDisplay(div, displayOpt) {
  var d = document.getElementsByClassName(div);
  var opt = displayOpt;
  d.style.display = opt;
}

function trackScore() {

}

function submitQuestion() {
  const form = {
    topic: document.getElementById('topic'),
    question_name: document.getElementById('question_name'),
    question_text: document.getElementById('question_text'),
    difficulty: document.getElementById('difficulty'),
    constraint: document.getElementById('constraint'),
    submit: document.getElementById('submit-question'),
    msg: document.getElementById('createQuestion-notifs')
  };

      const request = new XMLHttpRequest();

      request.onload = function() {

        let responseObj = null;

        try {
          responseObj = JSON.parse(request.responseText);
        } catch (e) {
          console.error('could not parse json');
          console.log("response text: " + request.responseText);
        }
        if (responseObj) {
          handleResponse(responseObj);
        }
      };

    //const requestData = `topic=${form.topic.value}&question_name=${form.question_name.value}&question_text=${form.question_text.value}&difficulty=${form.difficulty.value}&constraint=${form.constraint.value}&input1=${form.input1.value}&output1=${form.output1.value}&input2=${form.input2.value}&output2=${form.output2.value}`;
    const requestData = {};

    for (var i = 0; i < document.getElementsByName("testcase").length /2; i++) {
      var inputName = "input" + (i+1);
      var outputName = "output" + (i+1);
      var inputVal = document.getElementById(inputName).value;
      var outputVal = document.getElementById(outputName).value;

      if (document.getElementById(inputName).id == inputName) {
        requestData[inputName] = inputVal;
      }
        requestData[outputName] = outputVal;
    requestData["question_name"] = form.question_name.value;
    requestData["question_text"] = form.question_text.value;
    requestData["topic"] = form.topic.value;
    requestData["difficulty"] = form.difficulty.value;
    requestData["constraint"] = form.constraint.value;
    }
    //console.log(requestData);
    console.log(JSON.stringify(requestData));
    request.open("post", "createQuestion.php");
    request.setRequestHeader('Content-type', 'application/json'); // didnt include 'application/... in the options'
    request.send(JSON.stringify(requestData));

    function handleResponse(responseObj) {
      if (responseObj.msg == 'question added') {
        while (form.msg.firstChild) {
          form.msg.removeChild(form.msg.firstChild);
        }
            const li = document.createElement('li');
            li.textContent = 'Question Added';
            form.msg.appendChild(li);

        form.msg.style.display = "block";
      }
      if (responseObj.msg == 'not added') {
        while (form.msg.firstChild) {
          form.msg.removeChild(form.msg.firstChild);
        }
            const li = document.createElement('li');
            li.textContent = 'Question Not Added';
            form.msg.appendChild(li);

        form.msg.style.display = "block";
      }
    };
}

function showQuestions() {
  const request = new XMLHttpRequest();

  request.onload = function () {
    document.getElementById('question-table').innerHTML = this.responseText;
  };
  request.open("get", "getQuestionBank.php");
  request.send();
}

function showQuestionTblChecks() {
  const request = new XMLHttpRequest();

  request.onload = function () {
    document.getElementById('create-exam').innerHTML = this.responseText;
  };
  request.open("get", "showQuestionTblChecks.php");
  request.send();
}

function addQuestionToExam() {
  const form = {
    exam_name: document.getElementById('exam_name'),
    msg: document.getElementById('form-msgs'),
  }
  var table = document.getElementById('preview');
  var rowCount = table.rows.length;
  var chkCount = 0;
  var requestData = "";
  var count = 1;
  var json = "";
  var requestData = "{ \"exam_name\":\"" + form.exam_name.value + "\"";
  for (var i = 0; i < rowCount; i++) { //loops through the entire row length
    var row = table.rows[i];
    //console.log(row);

      var point = row.cells[3].children[0].value;
      var qid = row.cells[0].innerHTML;
      requestData += ", \"qid" + (i+1) + "\":\"" + qid + "\", \"points" + (i+1) + "\":\"" + point + "\"";
  }
  requestData += " }";
  console.log(requestData);

  const request = new XMLHttpRequest();

  request.onload = function() {
    try {
      var responseObj = request.responseText;
      responseObj = JSON.parse(request.responseText);
      console.log('handling response');
      handleResponse(responseObj);
    } catch (e) {
      console.error('could not parse json');
      console.log("response: " + request.responseText);
    }
  };
  request.open('POST', 'submitQuestion.php');
  request.setRequestHeader('Content-type', 'application/json');
  request.send(requestData); //will send something like this: qid1=1&qid2=23 and so on, which will be easily encoded as a json

  function handleResponse(responseObj) {
    if (responseObj.msg == 'exam added') {
      while (form.msg.firstChild) {
          form.msg.removeChild(form.msg.firstChild);
        }
            const li = document.createElement('li');
            li.textContent = 'EXAM ADDED';
            form.msg.appendChild(li);

        form.msg.style.display = "block";
    }
    else if (responseObj.msg == 'not added') {
      while (form.msg.firstChild) {
          form.msg.removeChild(form.msg.firstChild);
        }
            const li = document.createElement('li');
            li.textContent = 'EXAM NOT ADDED';
            form.msg.appendChild(li);

        form.msg.style.display = "block";
    }
    else {
      console.error('json couldnt be handled: ');
      console.log(request.responseText);
    }
  };
}
function keyword_search(sorter, table) {
  var search = document.getElementById(sorter);
  var filter = search.value.toUpperCase();
  var table = document.getElementById(table);
  var tr = table.getElementsByTagName("tr");
  var i, j;
  //dynamic search
  for (i = 1; i < tr.length; i++){
    tr[i].style.display = "none";

    td = tr[i].getElementsByTagName("td");
    for (j = 0; j < td.length; j++) {
      cell = tr[i].getElementsByTagName("td")[j];
      if (cell) {
        if (cell.innerText.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
          break;
        }
      }
    }
  }
}
//filtering functions
function filterChkbox() {
  var topic = document.getElementById('sort-topic-chkbox').value;
  var difficulty = document.getElementById('sort-difficulty-chkbox').value;

  const request = new XMLHttpRequest();
  var requestData = "{\"topic\":\"" + topic + "\",\"difficulty\":\"" + difficulty + "\"}";
  console.log(requestData);
  var responseObj = null;
  request.onload = function() {
    document.getElementById('create-exam').innerHTML = this.responseText;
  };
  request.open("POST", "filterChkbox.php");
  request.setRequestHeader('Content-Type', 'application/json');
  request.send(requestData);
}

function filter() {
  var topic = document.getElementById('sort-topic').value;
  var difficulty = document.getElementById('sort-difficulty').value;

  const request = new XMLHttpRequest();
  var requestData = "{\"topic\":\"" + topic + "\",\"difficulty\":\"" + difficulty + "\"}";
  console.log(requestData);
  var responseObj = null;
  request.onload = function() {
    document.getElementById('question-table').innerHTML = this.responseText;
  };
  request.open("POST", "filter.php");
  request.setRequestHeader('Content-Type', 'application/json');
  request.send(requestData);
}

//adds additional test cases
function alterFields(id) {
  //find out if we're removing or adding a input field
  var count = document.getElementsByName("testcase").length;

  if (id == 'add-cases') {
    count = Math.floor((count / 2) + 1);
    console.log(count);
    var div = document.createElement("DIV");
    div.style.width = "20%";
    var label = document.createElement("LABEL");
    label.htmlFor = 'input ';
    label.innerHTML = 'Input ' + count;
    label.style.cssText ="float: left; width: 10em; margin-right: 1em;";
    var input = document.createElement("INPUT");
    input.setAttribute("type","text");
    input.setAttribute("name", "testcase");
    input.style.cssText ="float: left; width: 10em; margin-right: 1em;";
    input.id = 'input' + count;
    div.appendChild(label);
    label.appendChild(input);

    var outputLabel = document.createElement("LABEL");
    outputLabel.htmlFor = 'output ';
    outputLabel.innerHTML = 'Output ' + count;
    outputLabel.style="float: left; width: 10em; margin-right: 1em;";
    var output = document.createElement("INPUT");
    output.setAttribute("type","text");
    output.setAttribute("name", "testcase");
    output.style.cssText ="float: left; width: 10em; margin-right: 1em;";
    output.id = 'output' + count;
    document.getElementById('create-question').appendChild(div);
    div.appendChild(outputLabel);
    outputLabel.appendChild(output);
  }
  else {
    var el = document.getElementById('create-question');
    el.removeChild(el.lastChild); //removes both output and input at the same time?
    count = Math.floor((count / 2) - 1);
    console.log(count);
  }
}

function showTblChks() {
  const request = new XMLHttpRequest();

  request.onload = function () {
    document.getElementById('preview-table').innerHTML = this.responseText;
  };
  request.open("get", "showTblChks.php");
  request.send();
}

function moveRight(id, name, text, topic, diff, constraint, i) {
  var leftTable = document.getElementById('addQuestionTbl');
  var rightTable = document.getElementById('preview');
  rightTable.style.alignSelf = "center";
  var newRow = rightTable.insertRow(rightTable.length),
      cell1 = newRow.insertCell(0),
      cell2 = newRow.insertCell(1),
      cell3 = newRow.insertCell(2),
      cell4 = newRow.insertCell(3),
      cell5 = newRow.insertCell(4);
  cell1.innerHTML = id;
  cell2.innerHTML = name;
  cell3.innerHTML = text;
  cell4.innerHTML = "<input type='text' id='rightScore' placeholder='enter score here' onkeyup='trackScore()'>";
  cell5.innerHTML = "<input type='checkbox' id='rightChkbox' onchange='moveLeft()'>";

  var index = leftTable.rows[i].rowIndex;
  leftTable.deleteRow(index);
}

function moveLeft() {
  var leftTable = document.getElementById('addQuestionTbl');
  var rightTable = document.getElementById('preview');
  var rowCount = rightTable.rows.length;
  for (var i = 0; i < rowCount; i++) { //loops through the entire row length
    var chkbox = rightTable.rows[i].cells[4];
    if (chkbox.checked) { //get data if box was checked
      console.log("not checked!");
    }
    else {
      var index = rightTable.rows[i].rowIndex;  //find a way to check and add it back to the right table
      rightTable.deleteRow(index);
      showQuestionTblChecks();
    }
  }
}

function handleChk() {
  var leftTable = document.getElementById('addQuestionTbl');
  var rightTable = document.getElementById('preview');
  var rowCount = leftTable.rows.length;
    for (var i = 1; i < rowCount; i++) { //loops through the entire row length
      var row = leftTable.rows[i];
      var chkbox = row.cells[0].childNodes[1];
      if (chkbox.checked) { //get data if box was checked
        var id = row.cells[1].innerHTML, name = row.cells[2].innerHTML, text = row.cells[3].innerHTML,
            topic = row.cells[4].innerHTML, diff = row.cells[5].innerHTML, constraint = row.cells[6].innerHTML;
        moveRight(id, name, text, topic, diff, constraint, i);
      }
    }
}

function trackScore() {
  const form = {
    msg: document.getElementById('form-msgs'),
    submit: document.getElementById('submit-exam'),
  } ;
  var el = document.getElementById("total-score");
  var table = document.getElementById("preview");
  var points = 0, totalScore;
  for (var i = 0; i < table.rows.length; i++){
    points =  Number(points) + Number(table.rows[i].cells[3].children[0].value);
    el.innerHTML = points;
  }
}

function getGradedExam() {
  const request = new XMLHttpRequest();

  request.onload = function() {
    document.getElementById('examsTable').innerHTML = this.responseText;
  };
  request.open("GET", "getGradedExam.php");
  request.send();
}

function makeGradedExam(button) {
    var examName = button.innerHTML;
    var requestData = {};
    requestData["examName"] = examName;
    requestData = JSON.stringify(requestData);
    const request = new XMLHttpRequest();

    request.onload = function() {
      var responseObj;

      try {
          responseObj = JSON.parse(request.responseText);
        } catch (e) {
          console.error('could not parse json');
          console.log("response text: " + request.reponseText);
        }
        if (responseObj) {
          handleResponse(responseObj);
        }
      };

    request.open("POST", "makeGradedExam.php");
    request.setRequestHeader('Content-Type','application/json');
    request.send(requestData);

    function handleResponse(responseObj) {
      if (responseObj.msg == 'got graded exam') { //create exam here
        //var examInfo = JSON.stringify(responseObj); 
        var examInfo = responseObj;
        console.log("Exam object:\n");
        console.log(examInfo);
        if (typeof examInfo.partials !== "undefined") {
          partial1 = examInfo.partials[1].split(",");
          partial2 = examInfo.partials[2].split(",");
          partial3 = examInfo.partials[3].split(",");
        }
        //partial1 = examInfo.partials[1].split(",");
        //partial2 = examInfo.partials[2].split(",");
        //partial3 = examInfo.partials[3].split(",");
        SwapDivs('examList', 'reviewExamContainer');

        //building table 
        var examContainer = document.getElementById("reviewExamContainer");
        var examTitle = document.createElement("H1");
        examTitle.setAttribute("id", "examName");
        var examTitleText = document.createTextNode(examInfo.exam_name); 
        examTitle.appendChild(examTitleText);
        examContainer.appendChild(examTitle);
        var table = document.getElementById('tabularExamReview'); //get table from html
        //insert headers
        var newRow = table.insertRow(table.length),
            cell1 = newRow.insertCell(0),
            cell2 = newRow.insertCell(1),
            cell3 = newRow.insertCell(2),
            cell4 = newRow.insertCell(3),
            cell5 = newRow.insertCell(4),
            cell6 = newRow.insertCell(5);
            cell1.innerHTML = "Question";
            cell2.innerHTML = "Student Answer";
            cell3.innerHTML = "Comments";
            cell4.innerHTML = "Partial Scores";
            cell5.innerHTML = "Your Score";
            cell6.innerHTML = "Total Score Possible";

        //first question
          var newRow = table.insertRow(table.length),
            cell1 = newRow.insertCell(0),
            cell2 = newRow.insertCell(1),
            cell3 = newRow.insertCell(2),
            cell4 = newRow.insertCell(3),
            cell5 = newRow.insertCell(4),
            cell6 = newRow.insertCell(5);
            cell1.innerHTML = examInfo.exam_questions[1];
            cell1.setAttribute("name", "exam_questions");
            cell2.innerHTML = "<textarea rows='10' cols='35' disabled>" + examInfo.exam_answers[1] + "</textarea>";
            cell3.innerHTML = "<textarea rows='15' cols='35'>" + examInfo.exam_comments[0] + "\n\n" + examInfo.exam_comments[1] +
                              "\n\n" + examInfo.exam_comments[2] + "\n\n" + examInfo.exam_comments[3] + "\n\n" + examInfo.exam_comments[4] + "</textarea>";
            cell4.innerHTML = "<table><tr><td><input type='text' name='editPartial' value='" + partial1[0] + "'></td></tr>";
            cell4.innerHTML += "<table><tr><td><input type='text' name='editPartial' value='" + partial1[1] + "'></td></tr>";
            cell4.innerHTML += "<table><tr><td><input type='text' name='editPartial' value='" + partial1[2] + "'></td></tr>";
            cell4.innerHTML += "<table><tr><td><input type='text' name='editPartial' value='" + partial1[3] + "'></td></tr>";
            if (typeof partial1[4] !== "undefined") {
              cell4.innerHTML += "<table><tr><td><input type='text' name='editPartial' value='" + partial1[4] + "'></td></tr>";
            }
            //dont pull this from the database, instead just add it up from the partials
            cell5.innerHTML = "<input type='text' name='editScore' value='" + (parseFloat(partial1[0]) + parseFloat(partial1[0])) + "'disabled>"; 
            cell6.innerHTML = "<input type='text' name='editTotal' value='" + examInfo.totalScore[1] + "'>";
        
        //cell3.innerHTML = examInfo.exam_comments;
        var footerDiv = document.querySelector("div.footer");
        var footer = document.createElement("div");
        var commentBox = document.createElement("TEXTAREA");
        commentBox.setAttribute("id","addComments");
        commentBox.cols = "50";
        commentBox.rows = "20";
        commentBox.style.width = "100%";
        commentBox.setAttribute("placeholder","Enter additional comments here...");
        footer.appendChild(commentBox);
        var releaseBtn = document.createElement("BUTTON");
        var btnName = document.createTextNode("Release Exam");
        releaseBtn.appendChild(btnName);
        releaseBtn.setAttribute("type", "button");
        releaseBtn.setAttribute("id", "releaseBtn");
        releaseBtn.setAttribute("onclick", "releaseGrades();");
        footer.appendChild(releaseBtn);
        footerDiv.appendChild(footer);
      }
      else {
        console.log("failed!" + request.responseText);
      }
    }
}

function releaseGrades() {
    var msg = document.getElementById("notifs");
    var examName = document.getElementById("examName");
    var partialArr = [], scoreArr = [], totalArr = [];
    var partialObj = document.getElementsByName("editPartial"),
        partialCount = partialObj.length;
    for (var i = 0; i < partialCount; i++) {
      partialArr.push(partialObj[i].value);
    } 
    var scoreObj = document.getElementsByName("editScore"),
        scoreCount = scoreObj.length;
    for (var i = 0; i < scoreCount; i++) {
      scoreArr.push(scoreObj[i].value);
    }
    var addComments = document.getElementById("addComments");
    const requestData = "{\"examName\":\"" + examName.innerHTML + "\",\"addComments\":\"" + addComments.value + "\",\"partialScore\":\"" + 
                          partialArr + "\",\"score\":\"" + scoreArr + "\"}";
    console.log(requestData);
    
    //AJAX
     const request = new XMLHttpRequest();

    request.onload = function() {
      let responseObj = null;

      try {
          responseObj = JSON.parse(request.responseText);
        } catch (e) {
          console.error('could not parse json');
          console.log("response text: " + request.responseText);
        }
        if (responseObj) {
          handleResponse(responseObj);
        }
      };

    request.open("POST", "submitGradedExam.php");
    request.setRequestHeader('Content-type','application/json');
    request.send(requestData);
    
    function handleResponse(responseObj) {
      console.log(responseObj);
      console.log('request went through');
    }
}

function checkExam() { //check exam availibilty

}
