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

function submitQuestion() {
  const form = {
    topic: document.getElementById('topic'),
    question_name: document.getElementById('question_name'),
    question_text: document.getElementById('question_text'),
    difficulty: document.getElementById('difficulty'),
    constraint: document.getElementById('constraint'),
    input1: document.getElementById('input1'),
    output1: document.getElementById('output1'),
    input2: document.getElementById('input2'),
    output2: document.getElementById('output2'),
    submit: document.getElementById('submit-question')
  };

  //  form.submit.addEventListener('click', () => {
      const request = new XMLHttpRequest();

      request.onload = function() {

        let responseObj = null;

        try {
          responseObj = JSON.parse(request.responseText);
        } catch (e) {
          console.error('could not parse json');
          console.log("response text" + request.responseText);
        }
        if (responseObj) {
          handleResponse(responseObj);
        }
      };

    const requestData = `topic=${form.topic.value}&question_name=${form.question_name.value}&question_text=${form.question_text.value}&difficulty=${form.difficulty.value}&constraint=${form.constraint.value}&input1=${form.input1.value}&output1=${form.output1.value}&input2=${form.input2.value}&output2=${form.output2.value}`;

    request.open("post", "createQuestion.php");
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded'); // didnt include 'application/... in the options'
    request.send(requestData);

    function handleResponse(responseObj) {
      if (responseObj.msg == 'question added') {
        console.log('question added');
        alert('question was added!');
      }
      if (responseObj.msg == 'not added') {
        console.log('not added');
      }
    };
//  });
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
  //var count;
  
  if (id == 'add-cases') {
    var div = document.createElement("DIV");
    //div.id = 'fields';
    var label = document.createElement("LABEL");
    label.htmlFor = 'input ';
    label.innerHTML = 'input ';
    label.style="float: left; width: 10em; margin-right: 1em;";
    var input = document.createElement("INPUT");
    input.setAttribute("type","text");
    input.id = 'input';
    div.appendChild(label);
    label.appendChild(input);
    //document.getElementById('create-question').appendChild(div);
    
    var outputLabel = document.createElement("LABEL");
    outputLabel.htmlFor = 'output ';
    outputLabel.innerHTML = 'output ';
    outputLabel.style="float: left; width: 10em; margin-right: 1em;";
    var output = document.createElement("INPUT");
    output.setAttribute("type","text");
    output.id = 'output';
    document.getElementById('create-question').appendChild(div);
    div.appendChild(outputLabel);
    outputLabel.appendChild(output);
    count++;
  }
  else {
    var el = document.getElementById('create-question');
    el.removeChild(el.lastChild); //removes both output and input at the same time?
    //el.removeChild(el.lastChild);
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
  cell4.innerHTML = "<input type='text' id='score' placeholder='enter score here'>";
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
