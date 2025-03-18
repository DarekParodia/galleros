var username_field;
var password_field;
var password_confirm_field;
var show_password_field;

document.addEventListener("DOMContentLoaded", async (e) => {
    await getFields();
    addEventListeners();
    console.log("Main Function Done");

})

async function getFields() {
    username_field = document.getElementById("username");
    password_field = document.getElementById("password");
    password_confirm_field = document.getElementById("password-confirm");
    show_password_field = document.getElementById("password-show");
}

async function addEventListeners() {
    show_password_field.addEventListener("change", (e) => {
        console.log(show_password_field.checked);

        if (show_password_field.checked) {
            password_field.type = "text"
            password_confirm_field.type = "text"
        } else {
            password_field.type = "password"
            password_confirm_field.type = "password"
        }
    })
}