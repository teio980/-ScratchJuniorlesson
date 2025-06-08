<?php
include '../includes/connect_DB.php';
$teacher_id = $_SESSION['user_id'];
?>

<header class="teacher-header">
    <div class="header-content">
        <div class="logo-container">
            <h1 class="logo">LK Scratch Kids</h1>
        </div>
        
        <nav class="teacher-nav">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="Main_page.php" class="nav-link home-link">
                        <span class="material-symbols-outlined">Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../includes/logout.php" class="nav-link logout-link">
                        <span class="material-symbols-outlined">Sign Out</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>