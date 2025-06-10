<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php';
include 'resheadteacher.php';

$teacher_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Assigned Lessons</title>
    <link rel="stylesheet" href="../cssfile/Teachermain.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
    <link rel="stylesheet" href="../cssfile/assigned_lessons.css">
    <style>
        .delete-comment {
            color: #ff0000;
            cursor: pointer;
            margin-left: 10px;
            font-size: 14px;
        }
        .delete-comment:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Assigned Lessons by Class</h1>
        
        <?php
        // Handle comment deletion if requested
        if (isset($_GET['delete_comment'])) {
            $comment_id = $_GET['delete_comment'];
            $delete_sql = "DELETE FROM content_comments WHERE comment_id = ? AND sender_id = ?";
            $stmt = $connect->prepare($delete_sql);
            $stmt->bind_param("ss", $comment_id, $teacher_id);
            $stmt->execute();
            
            // Redirect back to avoid resubmission
            header("Location: assigned_lessons.php");
            exit();
        }

        $classQuery = "SELECT c.class_id, c.class_name 
                      FROM class c
                      JOIN teacher_class tc ON c.class_id = tc.class_id
                      WHERE tc.teacher_id = ?";
        $stmt = $connect->prepare($classQuery);
        $stmt->bind_param("s", $teacher_id);
        $stmt->execute();
        $classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        foreach ($classes as $class):
            $lessonQuery = "SELECT l.*, cw.expire_date, cw.availability_id 
                            FROM lessons l
                            JOIN class_work cw ON l.lesson_id = cw.lesson_id
                            WHERE cw.class_id = ?
                            ORDER BY cw.expire_date ASC";
            $stmt = $connect->prepare($lessonQuery);
            $stmt->bind_param("s", $class['class_id']);
            $stmt->execute();
            $lessons = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        ?>
            <div class="class-container">
                <h2 class="class-header"><?= htmlspecialchars($class['class_name']) ?></h2>
                
                <?php if (empty($lessons)): ?>
                    <p class="no-lessons">No lessons assigned to this class yet.</p>
                <?php else: ?>
                    <?php foreach ($lessons as $lesson): ?>
                    <div class="lesson-item">
                        <h3 class="lesson-title"><?= htmlspecialchars($lesson['title']) ?></h3>
                        <div class="lesson-meta">
                            <span>Deadline: <?= date('Y-m-d H:i', strtotime($lesson['expire_date'])) ?></span> | 
                            <span>Created: <?= date('Y-m-d', strtotime($lesson['create_time'])) ?></span>
                        </div>
                        
                        <!-- 留言板 -->
                        <div class="comment-section">
                            <h4>Discussion Board</h4>
                            
                            <!-- 留言表单 -->
                            <form method="POST" action="post_comment.php" class="comment-form">
                                <input type="hidden" name="content_type" value="lesson">
                                <input type="hidden" name="availability_id" value="<?= $lesson['availability_id'] ?>">
                                <textarea name="message" placeholder="Write your comment..." required></textarea>
                                <button type="submit" class="submit-btn">Post Comment</button>
                            </form>
                            
                            <!-- 留言列表 -->
                            <div class="comment-list">
                                <?php
                                $commentQuery = "SELECT cc.*, 
                                                IF(cc.sender_type='teacher', t.T_Username, s.S_Username) AS username
                                                FROM content_comments cc
                                                LEFT JOIN teacher t ON cc.sender_id = t.teacher_id AND cc.sender_type = 'teacher'
                                                LEFT JOIN student s ON cc.sender_id = s.student_id AND cc.sender_type = 'student'
                                                WHERE cc.content_type = 'lesson' 
                                                AND cc.availability_id = ?
                                                ORDER BY cc.created_at DESC";
                                $stmt = $connect->prepare($commentQuery);
                                $stmt->bind_param("s", $lesson['availability_id']);
                                $stmt->execute();
                                $comments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                
                                if (empty($comments)): ?>
                                    <p>No comments yet. Be the first to comment!</p>
                                <?php else: ?>
                                    <?php foreach ($comments as $comment): ?>
                                    <div class="comment">
                                        <div class="comment-header">
                                            <div class="comment-author"><?= htmlspecialchars($comment['username']) ?></div>
                                            <?php if ($comment['sender_id'] == $teacher_id && $comment['sender_type'] == 'teacher'): ?>
                                                <a href="?delete_comment=<?= $comment['comment_id'] ?>" class="delete-comment" 
                                                   onclick="return confirm('Are you sure you want to delete this comment?')">× Delete</a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="comment-text"><?= htmlspecialchars($comment['message']) ?></div>
                                        <div class="comment-time">
                                            <?= date('Y-m-d H:i', strtotime($comment['created_at'])) ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>