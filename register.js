let P_isValid = true ;

function checkInput(inputId, errMessageId){
    const input = document.getElementById(inputId);
    const errMessage = document.getElementById(errMessageId);

    input.addEventListener("input", function () {
        if (input.value.trim() === '') {
            errMessage.style.display = 'block'; 
        } else {
            errMessage.style.display = 'none'; 
        }
    });
}

function validatePasswordCondition(){
    const input = document.getElementById("U_Password");

    const length = document.getElementById("password_condition_length").querySelector("span");
    const digit = document.getElementById("password_condition_digit").querySelector("span");
    const upper = document.getElementById("password_condition_upper").querySelector("span");
    const lower = document.getElementById("password_condition_lower").querySelector("span");
    const symbol = document.getElementById("password_condition_symbol").querySelector("span");

    input.addEventListener("input", function () {
        const value = input.value;

        if (value.length >= 8 && value.length <= 12) {
            length.textContent = "check"; 
            P_isValid = true;
        } else {
            length.textContent = "close";
            P_isValid = false;
        }

        if (/\d/.test(value)) {
            digit.textContent = "check";
            P_isValid = true;
        } else {
            digit.textContent = "close";
            P_isValid = false;
        }

        if (/[A-Z]/.test(value)) {
            upper.textContent = "check";
            P_isValid = true;
        } else {
            upper.textContent = "close";
            P_isValid = false;
        }

        if (/[a-z]/.test(value)) {
            lower.textContent = "check";
            P_isValid = true;
        } else {
            lower.textContent = "close";
            P_isValid = false;
        }

        if (/[^a-zA-Z0-9\s]/.test(value)) {
            symbol.textContent = "check";
            P_isValid = true;
        } else {
            symbol.textContent = "close";
            P_isValid = false;
        }
    });
}

/*function validateDataSubmit(){
    let isValid = true;

    if (document.getElementById("U_Username").value.length <= 5 && document.getElementById("U_Username").value.length >= 13) {
        alert("Username's length is too short or too long. Please Try Again.");
        isValid = false;
    }

    if (document.getElementById("U_Username").value.trim() === '') {
        document.getElementById("errMessage_Username").style.display = 'block';
        isValid = false;
    }

    if (document.getElementById("U_Email").value.trim() === '') {
        document.getElementById("errMessage_Email").style.display = 'block';
        isValid = false;
    }

    if (document.getElementById("U_Password").value.trim() === '') {
        document.getElementById("errMessage_Password").style.display = 'block';
        isValid = false;
    }

    if (document.getElementById("U_Confirmed_Password").value.trim() === '') {
        document.getElementById("errMessage_Confirmed_Password").style.display = 'block';
        isValid = false;
    }

    if (!P_isValid) {
        isValid = false;
    }

    return isValid;
}*/

checkInput('U_Username', 'errMessage_Username');
checkInput('U_Email', 'errMessage_Email');
checkInput('U_Password', 'errMessage_Password');
checkInput('U_Confirmed_Password', 'errMessage_Confirmed_Password');
validatePasswordCondition();

document.getElementById("Register_form").addEventListener("submit", function (event) {
    event.preventDefault(); 

    const password = document.getElementById("U_Password").value;
    const confirmedPassword = document.getElementById("U_Confirmed_Password").value;
    if (password !== confirmedPassword) {
        alert("Password do not match the Confirmed Password");
        return;
    }

    if (P_isValid) {
        this.submit();
    } else {
        alert("Please ensure the password meets all requirements.");
    }
});