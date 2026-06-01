window.addEventListener("DOMContentLoaded", function(){

const searchInput =
document.getElementById("globalSearch");

if(searchInput){

searchInput.addEventListener("keyup", function(){

let value =
this.value.toLowerCase();

let items =
document.querySelectorAll(
".task, .modern-task, .project-card"
);

items.forEach(item => {

let text =
item.innerText.toLowerCase();

if(text.includes(value)){

item.style.display = "";

}

else{

item.style.display = "none";

}

});

});

}

});