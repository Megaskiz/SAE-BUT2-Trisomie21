const colorPicker = document.getElementById("color-picker");
const body = document.querySelector("body");

colorPicker.addEventListener("change", function() {
  sessionStorage.setItem("bg-color", colorPicker.value);
  body.style.background = colorPicker.value;
  
});

document.querySelector("body").style.backgroundColor = localStorage.getItem("bg-color") || "#afeeee";



