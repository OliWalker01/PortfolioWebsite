const form = document.querySelector("form");
const firstName = document.getElementById("fname");
const secondName = document.getElementById("sname");
const companyName = document.getElementById("company");
const phoneNumber = document.getElementById("pnumber");
const emailAddress = document.getElementById("email");
const querySubject = document.getElementById("subject");

const emailValid = true;

function sendEmail() {
    const bodyMessage = `Full Name: ${firstName.value} ${secondName.value}<br> Company Name: ${companyName.value}<br> Phone Number: ${phoneNumber.value}<br> Email: ${emailAddress.value}<br> Subject: ${querySubject.value}<br>`;

    // Corrected variable names and null checks
    if (!firstName.value.trim() || !secondName.value.trim() || !phoneNumber.value.trim() || !emailAddress.value.trim() || !querySubject.value.trim()) {
        alert("Error! Cannot Send Message, information is missing!");
        return; // Exit the function if validation fails
    }

    if (!emailAddress.value.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)){
        alert("Error! Cannot Send Message, email is invalid!")
        return;
    }

    if (!phoneNumber.value.match(/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im)) {
        alert("Error!, Cannot Send Message, phone number is invalid!")
        return;
    }

    Email.send({
        SecureToken: "19b4e211-c4f5-4b0d-94a3-c6a774dd67f5",
        To : 'oliver.walker01@hotmail.co.uk',
        From : "info@oliverwalkerportfolio.co.uk",
        Subject : "Contact has been made",
        Body : bodyMessage
    }).then(
        message => alert("Message Sent Successfully"),
    ).catch(
        error => alert("Failed to send message: " + error.message),
    );

    openConfirmedPage();
}

function printMessage()
{
    alert("Button Clicked")
}

form.addEventListener("submit", (e) => {
    e.preventDefault();

    //printMessage();

    sendEmail();
});

function openConfirmedPage() {
    window.location='contactmesuccess.html';
}