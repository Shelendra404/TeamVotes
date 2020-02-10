document.addEventListener('DOMContentLoaded', accordion, false);

function saveWinner() {
  var winner = document.getElementById('show-winner');
  var data = winner.value;
  var xhttp;

  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("show-winner").value = this.responseText;
    }
  };
  xhttp.open("GET", "ajax.php?saveID=" + data, true);
  xhttp.send();
}

function getWinner() {
  var teams = document.getElementsByClassName('active-choice');

  if (teams[0]) {
    var data = teams[0].id;
    var xhttp;

    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("show-winner").value = this.responseText;
      }
    };
    xhttp.open("GET", "ajax.php?q=" + data, true);
    xhttp.send();
  }
}

function share(data) {
  var input = document.getElementById("share-to-friend");
  if (input.style.display == "none" || input.style.display == "") {
    input.style.display = "block";
    data.style.display = "none";
  } else {
    input.style.display = "none";
  }
}

function change(radio) {
  if (radio.checked && radio.id == "team-1") {
    vote('1');
  } else {
    vote('2');
  }
}

function accordion() {
  var acc = document.getElementsByClassName("accordion");
  var i;

  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("active");
      var panel = this.nextElementSibling;
      if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
        panel.style.minHeight = null;
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
        panel.style.minHeight = 40 + "px";

      }
    });
  }
}

function vote(data) {
  var radiobtn = document.getElementById('team-' + data);
  if (radiobtn) {
    radiobtn.checked = true;
  }

  var currentChoice = document.querySelectorAll('.team-choice');
  for (var i = 0; i < currentChoice.length; i++) {
    currentChoice[i].classList.remove("active-choice");
  }
  var element = document.getElementById(data);
  element.classList.add("active-choice");
  team = data;
}