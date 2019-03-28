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
  const requestData = `exam_name=${exam}`;
  
  request.open('POST', 'selectExam.php');
  //request.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  //console.log(requestData);
  request.send(requestData);
  
   function handleResponse(responseObj) {
      if (responseObj.msg == 'loading exam') { //load exam for student to take
        console.log('loading exam');
        console.log(request.responseText);
      }
      if (responseObj.msg == 'could not load exam') {
        console.log('exam unable to load');
        console.log(request.responseText);
      }
    };
}

function getExamReview() {

}
