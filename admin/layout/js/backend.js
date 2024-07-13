let inputs = document.querySelectorAll('.form-control');
let dropDown = document.querySelector('.dropdown')
let list = document.querySelector('.dropdown-menu')
let showPasswordIcon = document.querySelector('.show-password');
let passwordInput = document.querySelector('.passwd');
let deleteButton = document.querySelector('.confirm-delete');
let deleteButton2 = document.querySelector('.confirm-delete-two');
dropDown.onmouseover = function () {
  list.style.display = 'block';
}
dropDown.onmouseout = function () {
  list.style.display = 'none';
}
inputs.forEach((e) => {
  let placeholder = ""; 
  e.onfocus = function () {
    placeholder = e.getAttribute('placeholder');
    e.setAttribute('placeholder', '');
  }
  e.onblur = function (){
    e.setAttribute('placeholder', placeholder);
  }
});

// make the * after the required elements

let requiredInputs = document.querySelectorAll('[required=required]');
requiredInputs.forEach(function (e) {
  let astrisk = document.createElement('span');
  requiredInputs.classList = requiredInputs.classList + ' required';
  let astriskText = document.createTextNode('*');
  astrisk.appendChild(astriskText);
  e.parentElement.appendChild(astrisk);
});

if(showPasswordIcon != null) {
  showPasswordIcon.onmouseover = function () {
    passwordInput.setAttribute('type', 'text');
  }
  showPasswordIcon.onmouseout = function () {
    passwordInput.setAttribute('type', 'password');
  }
}

if(deleteButton != null) {
  deleteButton.onclick = function () {
  
    return confirm("Do You Want To Delete The User?");
  }
}

if(deleteButton2 != null) {
  deleteButton2.onclick = function (){ 
    return confirm("Do YOu Want To Delete The Category?");
  }
}

$(function () {
  $("select").selectBoxIt();
})