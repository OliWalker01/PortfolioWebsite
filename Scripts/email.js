const form = document.querySelector("form");
const firstName = document.getElementById("fname");
const secondName = document.getElementById("sname");
const companyName = document.getElementById("company");
const phoneNumber = document.getElementById("pnumber");
const emailAddress = document.getElementById("email");
const querySubject = document.getElementById("subject");

function sendEmail() {
    const bodyMessage = `Full Name: ${firstName.value} ${secondName.value}<br> Company Name: ${companyName.value}<br> Phone Number: ${phoneNumber.value}<br> Email: ${emailAddress.value}<br> Subject: ${querySubject.value}<br>`;

    // Corrected variable names and null checks
    if (!firstName.value.trim() || !secondName.value.trim() || !phoneNumber.value.trim() || !emailAddress.value.trim() || !querySubject.value.trim()) {
        alert("Error! Cannot Send Message, information is missing!");
        return; // Exit the function if validation fails
    }

    Email.send({
        SecureToken: "a18f3b56-30c3-44d6-96e3-e1a5ae9767c0",
        To: "oliver.walker01@hotmail.co.uk",
        From: "oliver.walker01@gmail.com",
        Subject: "Contact Made from Portfolio",
        Body: bodyMessage
    }).then(
        message => alert("Message Sent Successfully")
    ).catch(
        error => alert("Failed to send message: " + error.message)
    );
}

form.addEventListener("submit", (e) => {
    e.preventDefault();
    sendEmail();
});
