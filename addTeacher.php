<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cssfile/add_teacher.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>Add Teacher</title>
</head>
<body>
    <div class="main">
        <form class="Register_form" id="Register_form" action="includes/addTeacherFunction.php" method="POST">
            
            <label for="U_Username">Username (6-12 Characters):</label>
            <input type="text" id="U_Username" name="U_Username" placeholder="Username" required minlength="6" maxlength="12">
            <p id="errMessage_Username" class="errMessage">Username is required</p>

            <label for="U_Email">Email:</label>
            <input type="email" id="U_Email" name="U_Email" placeholder="e.g.: abc123@gmail.com" required>
            <p id="errMessage_Email" class="errMessage">Email is required</p>

            <label for="U_Password">Password:</label>
            <div class="password-box">
                <input type="password" id="U_Password" name="U_Password" placeholder="Password" required>
                <span class="material-symbols-outlined" id="showPassword_icon" onclick="showPassword() ">visibility_off</span>
            </div>
            <div>
                <p id="password_condition_length" class="password_condition"><span class="material-symbols-outlined">close</span> Length is between 8-12 Characters</p>
                <p id="password_condition_digit" class="password_condition"><span class="material-symbols-outlined">close</span> Contain at least one number</p>
                <p id="password_condition_upper" class="password_condition"><span class="material-symbols-outlined">close</span> Contain at least one Uppercase</p>
                <p id="password_condition_lower" class="password_condition"><span class="material-symbols-outlined">close</span> Contain at least one Lowercase</p>
                <p id="password_condition_symbol" class="password_condition"><span class="material-symbols-outlined">close</span> Contain at least one Symbol</p>
            </div>
            <p id="errMessage_Password" class="errMessage">Password is required</p>

            <label for="U_Confirmed_Password">Confirmed Password:</label>
            <div class="password-box">
                <input type="password" id="U_Confirmed_Password" placeholder="Password" required>
                <span class="material-symbols-outlined" id="showConfirmedPassword_icon" onclick="showConfirmedPassword() ">visibility_off</span>
            </div>
            <p id="errMessage_Confirmed_Password" class="errMessage">Confirmed Password is required</p>

            <button type="submit">Register</button>
        </form>
    </div>
    <script src="register.js"></script>
</body>
</html>