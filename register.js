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

        // 1. Length Check (8-12 characters)
        if (value.length >= 8 && value.length <= 12) {
            length.textContent = "check"; 
        } else {
            length.textContent = "close";
        }

        // 2. Digit Check (Contains at least one number)
        if (/\d/.test(value)) {
            digit.textContent = "check";
        } else {
            digit.textContent = "close";
        }

        // 3. Uppercase Check
        if (/[A-Z]/.test(value)) {
            upper.textContent = "check";
        } else {
            upper.textContent = "close";
        }

        // 4. Lowercase Check
        if (/[a-z]/.test(value)) {
            lower.textContent = "check";
        } else {
            lower.textContent = "close";
        }

        // 5. Symbol Check (Special characters)
        if (/[^a-zA-Z0-9\s]/.test(value)) {
            symbol.textContent = "check";
        } else {
            symbol.textContent = "close";
        }
    });
}


checkInput('U_Username', 'errMessage_Username');
checkInput('U_Email', 'errMessage_Email');
checkInput('U_Password', 'errMessage_Password');
checkInput('U_Confirmed_Password', 'errMessage_Confirmed_Password');
validatePasswordCondition();