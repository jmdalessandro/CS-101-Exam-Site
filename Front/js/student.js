function SwapDivs(div1, div2) {
  d1 = document.getElementById(div1);
  d2 = document.getElementById(div2);
  if (d2.style.display == "none") {
    d1.style.display = "none";
    d2.style.display = "block";
  }
  else {
    d1.style.display = "block";
    d2.style.display = "none";
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

function selectExam() {

}

function getExamReview() {

}

