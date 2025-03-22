function checkAnswer(correctAnswer) {
    const selectedAnswer = document.querySelector('input[name="answer"]:checked');

    if (!selectedAnswer) {
        alert("Please select an answer!");
        return;
    }

    const userAnswer = selectedAnswer.value;
    const body = document.body;

    if (userAnswer === correctAnswer) {
        body.classList.add('correct');
    } else {
        body.classList.add('incorrect');
    }

    setTimeout(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const level = urlParams.get('level');

        fetch('update_score.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                level: level,
                isCorrect: userAnswer === correctAnswer,
            }),
        }).then(() => {
            window.location.href = `question.php?level=${level}`;
        });
    }, 1000);
}