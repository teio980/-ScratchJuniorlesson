<?php
include 'connect_DB.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_Password = $_POST["old_Password"];
    $new_Password = $_POST["new_Password"];
    $new_Confirmed_Password = $_POST["confirmed_new_Password"];
    $student_id = $_POST["student_id"];

    if (strlen($new_Password) <= 8 || strlen($new_Password) >= 12) {
        echo "<script>
        alert('Password length must be between 8-12 characters');
        window.location.href = '../Student/Personal_Profile.php';
        </script>";
        exit();
    }
    
    if (!preg_match('/\d/', $new_Password)) {
        echo "<script>
            alert('Password must contain at least one number');
            window.location.href = '../Student/Personal_Profile.php';
            </script>";
        exit();
    }
    
    if (!preg_match('/[A-Z]/', $new_Password)) {
        echo "<script>
            alert('Password must contain at least one Uppercase');
            window.location.href = '../Student/Personal_Profile.php';
            </script>";
        exit();
    }
    
    if (!preg_match('/[a-z]/', $new_Password)) {
        echo "<script>
            alert('Password must contain at least one Lowercase');
            window.location.href = '../Student/Personal_Profile.php';
            </script>";
        exit();
    }
    
    if (!preg_match('/[^a-zA-Z0-9\s]/', $new_Password)) {
        echo "<script>
            alert('Password must contain at least one symbol');
            window.location.href = '../Student/Personal_Profile.php';
            </script>";
        exit();
    }

    $check_password_sql = "SELECT S_Password FROM student WHERE student_id = :ID";
    $check_password_stmt = $pdo->prepare($check_password_sql);
    $check_password_stmt->bindParam(':ID',$student_id);
    $check_password_stmt->execute();
    $old_Confirmed_Password = $check_password_stmt->fetch();

    if(password_verify($old_Password, $old_Confirmed_Password['S_Password'])){
        $hashedPassword = password_hash($new_Password, PASSWORD_DEFAULT);
        $update_password_sql = "UPDATE student SET S_Password = :password WHERE student_id = :ID";
        $update_password_stmt = $pdo->prepare($update_password_sql);
        $update_password_stmt->bindParam(':password',$hashedPassword);
        $update_password_stmt->bindParam(':ID',$student_id);
        if($update_password_stmt->execute()){
            echo "<script>
                alert('New Password Sucessful Updated!');
                window.location.href = '../Student/Personal_Profile.php';
                </script>";
                exit();
        }else{
            echo "<script>
                alert('New Password Failed to Updated!\nPlease Try Again Later.');
                window.location.href = '../Student/Personal_Profile.php';
                </script>";
                exit();
        }
    }else{
        echo "<script>
        alert('Old password is incorrect');
        window.location.href = '../Student/Personal_Profile.php';
        </script>";
        exit();
    }
}
?>