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

    const requestData = `topic=${form.topic.value}&question_name=${form.question_name.value}&question_text=${form.question_text.value}&difficulty=${form.difficulty.value}&input1=${form.input1.value}&output1=${form.output1.value}&input2=${form.input2.value}&output2=${form.output2.value}`;

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
  }
  var table = document.getElementById('addQuestionTbl');
  var rowCount = table.rows.length;
  var chkCount = 0;
  var requestData = "";
  var count = 1;
  var json = "";
  var requestData = "{ \"exam_name\":\"" + form.exam_name.value + "\"";
  for (var i = 1; i < rowCount; i+=1) { //loops through the entire row length
    var row = table.rows[i];
    //console.log(row);
    var chkbox = row.cells[0].childNodes[1];
    if (chkbox.checked) { //get data if box was checked
      var point = row.cells[1].children[0].value;
      var qid = row.cells[2].innerHTML;
      requestData += ", \"qid" + i + "\":\"" + qid + "\", \"points" + i + "\":\"" + point + "\"";
    }
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
      alert('exam added!');
    }
    else if (responseObj.msg == 'not added') {
      alert('exam failed to add!');
    }
    else {
      console.error('json couldnt be handled: ');
      console.log(request.responseText);
    }
  };
}
function keyword_search(table) {
  var search = document.getElementById('sort-keyword');
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
  var topic = document.getElementById('sort-topic').value;
  var difficulty = document.getElementById('sort-difficulty').value;
  
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
function addFields() {

}
