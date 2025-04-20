<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/teacher_evaluation.css">
    <title>Evaluation Page</title>
</head>
<body>
    <form action="upload_teacher_evaluation.php" method="post">
        <div class="question">
          <label>1. The teacher explains the subject clearly and effectively.</label><br>
            <div class="radio-button-group">
                <input type="radio" name="q1" id="q1-1" value="1" hidden>
                <label for="q1-1" class="radio-button">[1] Strongly Disagree</label>

                <input type="radio" name="q1" id="q1-2" value="2" hidden>
                <label for="q1-2" class="radio-button">[2] Disagree</label>

                <input type="radio" name="q1" id="q1-3" value="3" hidden>
                <label for="q1-3" class="radio-button">[3] Neutral</label>

                <input type="radio" name="q1" id="q1-4" value="4" hidden>
                <label for="q1-4" class="radio-button">[4] Agree</label>

                <input type="radio" name="q1" id="q1-5" value="5" hidden>
                <label for="q1-5" class="radio-button">[5] Strongly Agree</label>
            </div>
        </div>
    
        <div class="question">
          <label>2. The teacher is well-prepared and organized for each lesson.</label><br>
            <div class="radio-button-group">
                <input type="radio" name="q2" id="q2-1" value="1" hidden>
                <label for="q2-1" class="radio-button">[1] Strongly Disagree</label>

                <input type="radio" name="q2" id="q2-2" value="2" hidden>
                <label for="q2-2" class="radio-button">[2] Disagree</label>

                <input type="radio" name="q2" id="q2-3" value="3" hidden>
                <label for="q2-3" class="radio-button">[3] Neutral</label>

                <input type="radio" name="q2" id="q2-4" value="4" hidden>
                <label for="q2-4" class="radio-button">[4] Agree</label>

                <input type="radio" name="q2" id="q2-5" value="5" hidden>
                <label for="q2-5" class="radio-button">[5] Strongly Agree</label>
            </div>
        </div>
    
        <div class="question">
          <label>3. The teacher treats students with respect and fairness.</label><br>
            <div class="radio-button-group">
                <input type="radio" name="q3" id="q3-1" value="1" hidden>
                <label for="q3-1" class="radio-button">[1] Strongly Disagree</label>

                <input type="radio" name="q3" id="q3-2" value="2" hidden>
                <label for="q3-2" class="radio-button">[2] Disagree</label>

                <input type="radio" name="q3" id="q3-3" value="3" hidden>
                <label for="q3-3" class="radio-button">[3] Neutral</label>

                <input type="radio" name="q3" id="q3-4" value="4" hidden>
                <label for="q3-4" class="radio-button">[4] Agree</label>

                <input type="radio" name="q3" id="q3-5" value="5" hidden>
                <label for="q3-5" class="radio-button">[5] Strongly Agree</label>
            </div>
        </div>
    
        <div class="question">
          <label>4. The teacher encourages student participation and questions.</label><br>
            <div class="radio-button-group">
                <input type="radio" name="q4" id="q4-1" value="1" hidden>
                <label for="q4-1" class="radio-button">[1] Strongly Disagree</label>

                <input type="radio" name="q4" id="q4-2" value="2" hidden>
                <label for="q4-2" class="radio-button">[2] Disagree</label>

                <input type="radio" name="q4" id="q4-3" value="3" hidden>
                <label for="q4-3" class="radio-button">[3] Neutral</label>

                <input type="radio" name="q4" id="q4-4" value="4" hidden>
                <label for="q4-4" class="radio-button">[4] Agree</label>

                <input type="radio" name="q4" id="q4-5" value="5" hidden>
                <label for="q4-5" class="radio-button">[5] Strongly Agree</label>
            </div>
        </div>
    
        <div class="question">
          <label>5. The teacher provides helpful feedback on assignments and exams.</label><br>
            <div class="radio-button-group">
                <input type="radio" name="q5" id="q5-1" value="1" hidden>
                <label for="q5-1" class="radio-button">[1] Strongly Disagree</label>

                <input type="radio" name="q5" id="q5-2" value="2" hidden>
                <label for="q5-2" class="radio-button">[2] Disagree</label>

                <input type="radio" name="q5" id="q5-3" value="3" hidden>
                <label for="q5-3" class="radio-button">[3] Neutral</label>

                <input type="radio" name="q5" id="q5-4" value="4" hidden>
                <label for="q5-4" class="radio-button">[4] Agree</label>

                <input type="radio" name="q5" id="q5-5" value="5" hidden>
                <label for="q5-5" class="radio-button">[5] Strongly Agree</label>
            </div>
        </div>
    
        <div class="question">
          <label>6. Please share any additional comments or suggestions for the teacher (if any):</label><br>
          <textarea name="comments"></textarea>
        </div>

        <button type="submit">submit</button>
      </form>
    </body>
</html>