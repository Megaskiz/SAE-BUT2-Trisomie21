const colorPicker = document.getElementById("color-picker");
const body = document.querySelector("body");

colorPicker.addEventListener("change", function() {
  body.style.background = colorPicker.value;
});