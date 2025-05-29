<?php
session_start();

if (!isset($_SESSION['identity'])) {
    echo"<script>
    alert('You NEED to login before using this page.');
    window.location.href = '../login.php';
    </script>";
    exit();
}

if ($_SESSION['identity'] != 'admin' && $_SESSION['identity'] != 'superadmin') {
    echo"<script>
    alert('You have no access to this page.');
    window.location.href = '../bkindex.php';
    </script>";
    exit();
}

?>