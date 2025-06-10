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

function showPassword() {
    const passwordInput = document.getElementById('U_Password');
    const icon = document.getElementById('showPassword_icon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.textContent = 'visibility'; 
    } else {
        passwordInput.type = 'password';
        icon.textContent = 'visibility_off'; 
    }
}

function showConfirmedPassword() {
    const confirmedPasswordInput = document.getElementById('U_Confirmed_Password');
    const icon = document.getElementById('showConfirmedPassword_icon');

    if (confirmedPasswordInput.type === 'password') {
        confirmedPasswordInput.type = 'text';
        icon.textContent = 'visibility'; 
    } else {
        confirmedPasswordInput.type = 'password';
        icon.textContent = 'visibility_off'; 
    }
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
            L_isValid = true;
        } else {
            length.textContent = "close";
            L_isValid = false;
        }

        if (/\d/.test(value)) {
            digit.textContent = "check";
            D_isValid = true;
        } else {
            digit.textContent = "close";
            D_isValid = false;
        }

        if (/[A-Z]/.test(value)) {
            upper.textContent = "check";
            UPPER_isValid = true;
        } else {
            upper.textContent = "close";
            UPPER_isValid = false;
        }

        if (/[a-z]/.test(value)) {
            lower.textContent = "check";
            LOWER_isValid = true;
        } else {
            lower.textContent = "close";
            LOWER_isValid = false;
        }

        if (/[^a-zA-Z0-9\s]/.test(value)) {
            symbol.textContent = "check";
            S_isValid = true;
        } else {
            symbol.textContent = "close";
            S_isValid = false;
        }

        if(L_isValid && D_isValid && UPPER_isValid && LOWER_isValid && S_isValid){
            P_isValid = true;
        }else{
            P_isValid = false
        }
    });
}

function setupSubmitValidation() {
    const submitButton = document.getElementById("submit_btn"); 
    
    submitButton.addEventListener("click", function(event) {
        if (!P_isValid) {
            event.preventDefault();
            alert("Please ensure your password meets all requirements.");
            return false;
        }
    });
}

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