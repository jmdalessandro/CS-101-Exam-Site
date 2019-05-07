function setDisplay(div, displayOpt) {
  d = document.getElementById(div);
  var opt = displayOpt;
  d.style.display = opt;
}

function acceptTabs() {
  var textareas = document.getElementsByTagName('textarea');

  if ( textareas ) {
    for ( var i = 0; i < textareas.length; i++ ) {
      textareas[i].addEventListener( 'keydown', function ( e ) {
      if ( e.which != 9 ) return;

      var start = this.selectionStart;
      var end	= this.selectionEnd;

      //this.value 			= this.value.substr( 0, start ) + "    " + this.value.substr( end ); //tab = 4 spaces
      this.value = this.value.substr( 0, start ) + "\t" + this.value.substr( end ); //tab = 8 spaces(defualt)
      this.selectionStart = this.selectionEnd = start + 1;

      e.preventDefault();
      return false;
      });
    }
  }
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
  form = {
    msg : document.getElementById("form-msgs"),
  };
  var fieldCount = document.getElementsByTagName("textarea");
  var fieldData = {};
  //gets answers from the inputted fields 
  var examName = document.getElementById("examName").innerHTML;
  for (var i = 1; i <= fieldCount.length; i++) {
    var id = "answer" + i;
    answer = document.getElementById(id).value;
    fieldData[id] = answer; 
  }
  fieldData["examName"] = examName;
  console.log("submit exam sender");
  console.log(JSON.stringify(fieldData));
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
  request.setRequestHeader('Content-Type', 'application/json');
  request.send(JSON.stringify(fieldData));
  
  function handleResponse(responseObj) {
      if (responseObj.msg == "submission passed") {
        while (form.msg.firstChild) {
          form.msg.removeChild(form.msg.firstChild);
        }
            const li = document.createElement('li');
            li.textContent = 'EXAM SUBMITTED';
            form.msg.appendChild(li);

        form.msg.style.display = "block";
      }
      else if (responseObj.msg == "submission failed") {
        while (form.msg.firstChild) {
          form.msg.removeChild(form.msg.firstChild);
        }
            const li = document.createElement('li');
            li.textContent = 'EXAM UNABLE TO SUBMIT ';
            form.msg.appendChild(li);

        form.msg.style.display = "block";
      }
      else {
        while (form.msg.firstChild) {
          form.msg.removeChild(form.msg.firstChild);
        }
            const li = document.createElement('li');
            li.textContent = 'SOME FOREIGN ERROR OCCURED';
            form.msg.appendChild(li);

        form.msg.style.display = "block";
      }
  }
}

function selectExam(button) {
  
  var exam = button.innerHTML;
  //console.log(exam);
  
  const request = new XMLHttpRequest();
  var requestData = '';
  
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
  //requestData = JSON.stringify('{\"examName\":\"' + exam + '\"}');
  requestData = JSON.stringify('{\"examName\":\"' + exam + ' \"}');
  console.log(requestData);
  
  request.open("POST", "selectExam.php");
  request.setRequestHeader('Content-Type','application/json');
  request.send(requestData);
  
   function handleResponse(responseObj) {
      if (responseObj.msg == 'loading exam') { 
        setDisplay('exam-screen','none');
        var examInfo = responseObj; 
        console.log(examInfo);
        //title of exam
        var examContainer = document.getElementById("takeExam");
        var examTitle = document.createElement("H1");
        examTitle.setAttribute("id", "examName");
        var examTitleText = document.createTextNode(examInfo.exam_name['1']); 
        examTitle.appendChild(examTitleText);
        examContainer.appendChild(examTitle);
        //build the questions with their textboxes + scores 
        var i, count = Object.keys(examInfo.exam_questions).length;
        for (i = 1; i <= count; i++) {
          var examQuestions = document.createElement("p");
          var examQuestionsText = document.createTextNode(i + ": " + examInfo.exam_questions[i]); //add some style later to the questions themselves
          var examQuestionScore = document.createTextNode(" (" + examInfo.score[i] + " pts)");
          examQuestions.appendChild(examQuestionsText);
          examQuestions.appendChild(examQuestionScore);
          examContainer.appendChild(examQuestions);
          var examQuestionField = document.createElement("TEXTAREA");
          examQuestionField.setAttribute("id", "answer" + i); 
          examContainer.appendChild(examQuestionField);
        }
        
        //add submit button 
        var submit_btn = document.createElement("BUTTON");
        submit_btn.setAttribute("id", "submit_exam");
        submit_btn.setAttribute("onclick", "submitExam();gradeExam();");
        submit_btn.innerHTML = "submit exam";
        examContainer.appendChild(submit_btn);
        var msgs = document.createElement("UL");
        msgs.setAttribute("id", "form-msgs");
        examContainer.appendChild(msgs);
      }
      if (responseObj.msg == 'could not load exam') {
        console.log('exam unable to load');
        console.log(request.responseText);
      }
      acceptTabs();
    };
}

function gradeExam() {
  var examName = document.getElementById("examName").innerText;

  const request = new XMLHttpRequest();
  var requestData = "{\"examName\":\"" + examName + "\"}";
  console.log("gradeExam request data: ");
  console.log(requestData);
  var responseObj = null;
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
  request.open("POST", "gradeExam.php");
  request.setRequestHeader('Content-Type', 'application/json');
  request.send(requestData);
  
  function handleResponse(responseObj) {
    if (responseObj.msg == 'graded') {
      console.log("graded");
    }
    else if (responseObj.msg == 'not graded') {
      console.log("not graded");
    }
    else {
      console.error('json couldnt be handled: ');
      console.log(request.responseText);
    }
  };
}

function getExamReview() { //get final exam review
  //check if exam is released first?
  
  //build exam list
  const request = new XMLHttpRequest();

  request.onload = function() {
    document.getElementById('result-table').innerHTML = this.responseText;
  };
  request.open("GET", "getExam2.php");
  request.send();
}

function displayExam(button) {
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
        var examInfo = responseObj;
        console.log("Exam object:\n");
        console.log(examInfo);
        var scores = examInfo.exam_scores[1]*1 + examInfo.exam_scores[2]*1 + examInfo.exam_scores[3]*1;
        var total = examInfo.totalScore[1]*1 + examInfo.totalScore[2]*1 + examInfo.totalScore[3]*1;
        if (typeof examInfo.partials !== "undefined") {
          partial1 = examInfo.partials[1].split(",");
          partial2 = examInfo.partials[2].split(",");
          partial3 = examInfo.partials[3].split(",");
        }
        setDisplay('result-table', 'none');

        //building table 
        var examContainer = document.getElementById("results-screen");
        var table = document.getElementById('review-table'); //get table from html
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
            cell2.innerHTML = "<textarea rows='10' cols='75' disabled>" + examInfo.exam_answers[1] + "</textarea>";
            cell3.innerHTML = "<textarea rows='15' cols='75' name='qComments' disabled>" + "1. " + examInfo.exam_comments[0] + "\n\n2." + examInfo.exam_comments[1] +
                              "\n\n3." + examInfo.exam_comments[2] + "\n\n4." + examInfo.exam_comments[3] + "\n\n5." + examInfo.exam_comments[4] + "</textarea>";
            cell4.innerHTML = "1.<table><tr><td><input type='text' name='editPartial' value='" + partial1[0] + "' disabled></td></tr>";
            cell4.innerHTML += "2.<table><tr><td><input type='text' name='editPartial' value='" + partial1[1] + "' disabled></td></tr>";
            cell4.innerHTML += "3.<table><tr><td><input type='text' name='editPartial' value='" + partial1[2] + "' disabled></td></tr>";
            cell4.innerHTML += "4.<table><tr><td><input type='text' name='editPartial' value='" + partial1[3] + "' disabled></td></tr>";
            if (typeof partial1[4] === "undefined") {
              cell4.innerHTML += "5.<table><tr><td><input type='text' name='editPartial' value='" + partial1[4] + "' disabled></td></tr>";
            }
            else if (typeof partial1[4] !== "undefined") {
              cell4.innerHTML += "5. <table><tr><td><input type='text' name='editPartial' value='" + partial1[4] + "' disabled></td></tr>";
            }
            //dont pull this from the database, instead just add it up from the partials
            cell5.innerHTML = "<input type='text' name='editScore' disabled value='" + examInfo.exam_scores[1] + "' disabled>"; 
            cell6.innerHTML = "<input type='text' name='editTotal' disabled value='" + examInfo.totalScore[1] + "'disabled>";
            
        //second question
          var newRow = table.insertRow(table.length),
            cell1 = newRow.insertCell(0),
            cell2 = newRow.insertCell(1),
            cell3 = newRow.insertCell(2),
            cell4 = newRow.insertCell(3),
            cell5 = newRow.insertCell(4),
            cell6 = newRow.insertCell(5);
            cell1.innerHTML = examInfo.exam_questions[2];
            cell1.setAttribute("name", "exam_questions");
            cell2.innerHTML = "<textarea rows='10' cols='75' disabled>" + examInfo.exam_answers[2] + "</textarea>";
            cell3.innerHTML = "<textarea rows='15' cols='75' name='qComments' disabled>" + "1. " + examInfo.exam_comments[5] + "\n\n2." + examInfo.exam_comments[6] +
                              "\n\n3." + examInfo.exam_comments[7] + "\n\n4." + examInfo.exam_comments[8] + "\n\n5." + examInfo.exam_comments[9] + "</textarea>";
            cell4.innerHTML = "1.<table><tr><td><input type='text' name='editPartial' value='" + partial2[0] + "' disabled></td></tr>";
            cell4.innerHTML += "2.<table><tr><td><input type='text' name='editPartial' value='" + partial2[1] + "' disabled></td></tr>";
            cell4.innerHTML += "3.<table><tr><td><input type='text' name='editPartial' value='" + partial2[2] + "' disabled></td></tr>";
            cell4.innerHTML += "4.<table><tr><td><input type='text' name='editPartial' value='" + partial2[3] + "' disabled></td></tr>";
            if (typeof partial2[4] === "undefined") {
              cell4.innerHTML += "5.<table><tr><td><input type='text' name='editPartial' value='" + partial2[4] + "' disabled></td></tr>";
            }
            else if (typeof partial2[4] !== "undefined") {
              cell4.innerHTML += "5. <table><tr><td><input type='text' name='editPartial' value='" + partial2[4] + "' disabled></td></tr>";
            }
            cell5.innerHTML = "<input type='text' name='editScore' value='" + examInfo.exam_scores[2] + "' disabled>"; 
            cell6.innerHTML = "<input type='text' name='editTotal' value='" + examInfo.totalScore[2] + "'disabled>";
        
        //third question
        var newRow = table.insertRow(table.length),
            cell1 = newRow.insertCell(0),
            cell2 = newRow.insertCell(1),
            cell3 = newRow.insertCell(2),
            cell4 = newRow.insertCell(3),
            cell5 = newRow.insertCell(4),
            cell6 = newRow.insertCell(5);
            cell1.innerHTML = examInfo.exam_questions[3];
            cell1.setAttribute("name", "exam_questions");
            cell2.innerHTML = "<textarea rows='10' cols='75' disabled>" + examInfo.exam_answers[3] + "</textarea>";
            cell3.innerHTML = "<textarea rows='15' cols='75' name='qComments' disabled>" + "1. " + examInfo.exam_comments[10] + "\n\n2." + examInfo.exam_comments[11] +
                              "\n\n3." + examInfo.exam_comments[12] + "\n\n4." + examInfo.exam_comments[13] + "\n\n5." + examInfo.exam_comments[14] + "</textarea>";
            cell4.innerHTML = "1. <table><tr><td><input type='text' name='editPartial' value='" + partial3[0] + "' disabled></td></tr>";
            cell4.innerHTML += "2. <table><tr><td><input type='text' name='editPartial' value='" + partial3[1] + "' disabled></td></tr>";
            cell4.innerHTML += "3. <table><tr><td><input type='text' name='editPartial' value='" + partial3[2] + "' disabled></td></tr>";
            cell4.innerHTML += "4. <table><tr><td><input type='text' name='editPartial' value='" + partial3[3] + "' disabled></td></tr>";
            if (typeof partial3[4] === "undefined") {
              cell4.innerHTML += "5. <table><tr><td><input type='text' name='editPartial' value='" + partial3[4] + "' disabled></td></tr>";
            }
            else if (typeof partial3[4] !== "undefined") {
              cell4.innerHTML += "5. <table><tr><td><input type='text' name='editPartial' value='" + partial3[4] + "' disabled></td></tr>";
            }
            cell5.innerHTML = "<input type='text' name='editScore' value='" + examInfo.exam_scores[3] + "' disabled>"; 
            cell6.innerHTML = "<input type='text' name='editTotal' value='" + examInfo.totalScore[3] + "'disabled>";
        
        var footer = document.getElementById("footer");
        var finalScore = document.createElement("h2");
        var finalScoreText = document.createTextNode("Your Score: " + scores + "/" + total);
        finalScore.style.margin = "auto";
        finalScore.style.width = "50%";
        finalScore.style.padding = "10px";
        finalScore.style.textAlign = "center";
        finalScore.appendChild(finalScoreText);
        footer.appendChild(finalScore);
      }
      else {
        console.log("failed!" + request.responseText);
      }
    }
}
