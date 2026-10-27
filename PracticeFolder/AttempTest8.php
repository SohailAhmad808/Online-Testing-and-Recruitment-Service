<?php
require_once 'connect.php';
date_default_timezone_set('Asia/Karachi');

$user_id = 1; // Replace with session user ID
$qp_id = isset($_GET['qp_id']) ? intval($_GET['qp_id']) : 0;

// Validate quiz
if ($qp_id <= 0) die("Invalid Test Selected.");

// 1️⃣ Check if attempt already exists
$attempt_sql = "SELECT * FROM quiz_attempts WHERE user_id = ? AND qp_id = ? AND status = 'in_progress'";
$stmt = $conn->prepare($attempt_sql);
$stmt->bind_param("ii", $user_id, $qp_id);
$stmt->execute();
$attempt = $stmt->get_result()->fetch_assoc();

if (!$attempt) {
    // Create new attempt
    $start_time = date('Y-m-d H:i:s');
    $insert_attempt = "INSERT INTO quiz_attempts (user_id, qp_id, start_time) VALUES (?, ?, ?)";
    $stmt2 = $conn->prepare($insert_attempt);
    $stmt2->bind_param("iis", $user_id, $qp_id, $start_time);
    $stmt2->execute();
    $attempt_id = $conn->insert_id;
    $current_index = 0;
} else {
    $attempt_id = $attempt['attempt_id'];
    $current_index = $attempt['current_question_index'];
}

// 2️⃣ Fetch question list for this paper
$q_sql = "SELECT q.question_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option
          FROM question_paper_items qpi
          JOIN questions_pool q ON q.question_id = qpi.question_id
          WHERE qpi.qp_id = ?
          ORDER BY qpi.qpi_id ASC";
$q_stmt = $conn->prepare($q_sql);
$q_stmt->bind_param(
    "i",
    $qp_id
);
$q_stmt->execute();
$questions = $q_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$total_questions = count($questions);
if ($current_index >= $total_questions) {
    header("Location: quiz_result.php?attempt_id=$attempt_id");
    exit;
}

$current_question = $questions[$current_index];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Attempt Test</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<main class="container py-4">
    <h2 class="text-center bg-primary-subtle p-3">Quiz</h2>
    <div class="card col-md-9 col-lg-8 mx-auto my-md-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 id="questionNumber">Question <?= $current_index + 1 ?> / <?= $total_questions ?></h5>
                <span class="fs-6"><i class="fa-solid fa-clock me-1"></i><span id="timeLeft">30</span>s</span>
            </div>
        </div>
        <div class="card-body">
            <form id="quizForm">
                <input type="hidden" name="attempt_id" value="<?= $attempt_id ?>">
                <input type="hidden" name="question_id" value="<?= $current_question['question_id'] ?>">
                <input type="hidden" name="qp_id" value="<?= $qp_id ?>">
                
                <h5><?= htmlspecialchars($current_question['question_text']) ?></h5>
                <div class="mt-3">
                    <?php foreach (['a','b','c','d'] as $opt): ?>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="selected_option" value="<?= strtoupper($opt) ?>" id="<?= $opt ?>">
                            <label class="form-check-label" for="<?= $opt ?>"><?= htmlspecialchars($current_question['option_'.$opt]) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>
        <div class="card-footer text-end">
            <button id="nextBtn" class="btn btn-primary">Save & Next</button>
        </div>
    </div>
</main>

<script>
let timeLeft = 30;
let timer = setInterval(() => {
    document.getElementById('timeLeft').textContent = timeLeft;
    if (--timeLeft < 0) {
        clearInterval(timer);
        saveAndNext();
    }
}, 1000);

$("#nextBtn").click(function(e){
    e.preventDefault();
    saveAndNext();
});

function saveAndNext() {
    $.ajax({
        url: "save_answer.php",
        type: "POST",
        data: $("#quizForm").serialize(),
        success: function(response){
            window.location.reload(); // load next question
        }
    });
}
</script>
</body>
</html>
