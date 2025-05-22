<?php
session_start();
include '../phpfile/connect.php';
include '../resheadAfterLogin.php';

$teacher_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Assigned Lessons</title>
    <link rel="stylesheet" href="../cssfile/Tmain.css">
    <style>
        .class-container {
            margin-bottom: 40px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .class-header {
            color: rgb(142, 60, 181);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .lesson-item {
            margin: 20px 0;
            padding: 15px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .lesson-title {
            color: #333;
            margin-bottom: 10px;
        }
        .lesson-meta {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 10px;
        }
        .comment-section {
            margin-top: 15px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        .comment-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .comment-list {
            margin-top: 15px;
        }
        .comment {
            padding: 10px;
            margin-bottom: 10px;
            background: white;
            border-radius: 4px;
            border-left: 3px solid rgb(142, 60, 181);
        }
        .comment-author {
            font-weight: bold;
            color: #333;
        }
        .comment-time {
            font-size: 0.8em;
            color: #999;
        }
        .no-lessons {
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Assigned Lessons by Class</h1>
        
        <?php
        // 获取老师负责的所有班级
        $classQuery = "SELECT c.class_id, c.class_name 
                      FROM class c
                      JOIN teacher_class tc ON c.class_id = tc.class_id
                      WHERE tc.teacher_id = ?";
        $stmt = $connect->prepare($classQuery);
        $stmt->bind_param("s", $teacher_id);
        $stmt->execute();
        $classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        foreach ($classes as $class):
            // 获取该班级分配的所有课程
            $lessonQuery = "SELECT l.*, cw.expire_date 
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
                                <input type="hidden" name="content_id" value="<?= $lesson['lesson_id'] ?>">
                                <input type="hidden" name="class_id" value="<?= $class['class_id'] ?>">
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
                                                AND cc.content_id = ? 
                                                AND cc.class_id = ?
                                                ORDER BY cc.created_at DESC";
                                $stmt = $connect->prepare($commentQuery);
                                $stmt->bind_param("ss", $lesson['lesson_id'], $class['class_id']);
                                $stmt->execute();
                                $comments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                
                                if (empty($comments)): ?>
                                    <p>No comments yet. Be the first to comment!</p>
                                <?php else: ?>
                                    <?php foreach ($comments as $comment): ?>
                                    <div class="comment">
                                        <div class="comment-author"><?= htmlspecialchars($comment['username']) ?></div>
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