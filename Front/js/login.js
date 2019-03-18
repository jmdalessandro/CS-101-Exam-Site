const form = {
  username: document.getElementById('username'),
  password: document.getElementById('password'),
  submit: document.getElementById('login-btn'),
  msg: document.getElementById('form-msgs')
};

form.submit.addEventListener('click', () => {
    const request = new XMLHttpRequest();

    request.onload = () => {
      console.log(request.responseText);
      let responseObject = null;

      try {
        responseObject = JSON.parse(request.responseText);
      } catch (e) {
        console.error('could not parse json');
      }
      if (responseObject) {
        handleResponse(responseObject);
      }
    };

    const requestData = `username=${form.username.value}&password=${form.password.value}`;
    //console.log(requestData);

    request.open('post', 'login.php');
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(requestData);

    function handleResponse(responseObject) {
      console.log(responseObject);
      if (responseObject.whoami == "student") {
        location.href = 'student.html';
      }
      if (responseObject.whoami == 'teacher') {
        location.href = 'teacher.html';
      }
      if (responseObject.msg == 'invalid login') {
        while (form.msg.firstChild) {
          form.msg.removeChild(form.msg.firstChild);
        }
            const li = document.createElement('li');
            li.textContent = 'incorrect combination of username/password';
            form.msg.appendChild(li);

        form.msg.style.display = "block";
      }
    }
});
