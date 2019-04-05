function setDisplay(div, displayOpt) {
  d = document.getElementById(div);
  var opt = displayOpt;
  d.style.display = opt;
}

function getExam() {
  const request = new XMLHttpRequest();

  request.onload = function() {
    document.getElementById('exam').innerHTML = this.responseText;
  };
  request.open("GET", "getExam.php");
  request.send();
}

function submitExam() {
  console.log("pressed!");
  var fieldCount = document.getElementsByTagName("textarea");
  var fieldData = {};
  //gets answers from the inputted fields 
  for (var i = 1; i <= fieldCount.length; i++) {
    var id = "answer" + i;
    answer = document.getElementById(id).value;
    fieldData[id] = answer; 
  }
  console.log(fieldData);
  const request = new XMLHttpRequest();
  
  request.onload = function() { 
    let responseObj = null;
    
    try { 
      responseObj = JSON.parse(request.responseText);
    }
    catch(e) {
      console.error('could not parse json');
      console.log("response text: " + request.responseText);
    }
    if (responseObj) {
      handleResponse(responseObj);
    }
  };
  request.open("POST", "submitExam.php");
  request.setRequestHeader('Content-type', 'application/json');
  request.send(JSON.stringify(fieldData));
  
  function handleResponse(responseObj) {
      console.log("response sent!");
  }
}

function selectExam(button) {
  
  var exam = button.innerHTML;
  //console.log(exam);
  
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
  var requestData = `exam_name=${exam}`;
  console.log(requestData);
  
  request.open("POST", "selectExam.php");
  request.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  request.send(requestData);
  
   function handleResponse(responseObj) {
      if (responseObj.msg == 'loading exam') { //load exam for student to take
        console.log('loading exam');
        //create test (maybe include a new function so it's not that cluttered
        setDisplay('exam-screen','none');
        var examInfo = responseObj; 
        var linebreak = document.createElement("br");
        console.log(examInfo);
        //title of exam
        var examContainer = document.getElementById("takeExam");
        var examTitle = document.createElement("H1");
        var examTitleText = document.createTextNode(examInfo.exam_name['1']); 
        examTitle.appendChild(examTitleText);
        examContainer.appendChild(examTitle);
        //build the questions with their textboxes + scores 
        var i, count = Object.keys(examInfo.exam_questions).length;
        for (i = 1; i <= count; i++) {
          var examQuestions = document.createElement("p");
          var examQuestionsText = document.createTextNode(i + ": " + examInfo.exam_questions[i]); //add some style later to the questions themselves
          var examQuestionScore = document.createTextNode(" Worth " + examInfo.score[i] + " points");
          examQuestions.appendChild(examQuestionsText);
          examQuestions.appendChild(examQuestionScore);
          examQuestions.appendChild(linebreak);
          examContainer.appendChild(examQuestions);
          var examQuestionField = document.createElement("TEXTAREA");
          examQuestionField.setAttribute("id", "answer" + i);
          examQuestions.appendChild(examQuestionField);
        }
        //add submit button 
        var submit_btn = document.createElement("BUTTON");
        submit_btn.setAttribute("id", "submit_exam");
        submit_btn.setAttribute("onclick", "submitExam()");
        submit_btn.innerHTML = "submit exam";
        examContainer.appendChild(submit_btn);
      }
      if (responseObj.msg == 'could not load exam') {
        console.log('exam unable to load');
        console.log(request.responseText);
      }
    };
}

function getExamReview() {

}
