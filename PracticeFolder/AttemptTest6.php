<?php
date_default_timezone_set('Asia/Karachi');
require_once 'connect.php';


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
          ORDER BY qpi.id ASC";
$q_stmt = $conn->prepare($q_sql);
$q_stmt->bind_param("i", $qp_id);
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attempt Test</title>
    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    rel="stylesheet"
  />
</head>
<body>
    <main class="">
        <h2 class="text-center bg-primary-subtle p-3">Quiz</h2>
        <div class="card col-md-9 col-lg-8 mx-auto my-md-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0" id="questionNumber">Question 1 / 5</h5>
                    <span class=" fs-6">
                    <i class="fa-solid fa-clock me-1"></i><span id="timeLeft">Time left : 30</span>s
                    </span>
                </div>
            <div class="progress" style="height: 20px;">
                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">1/5</div>
                </div>
            </div>
            <div class="card-body">
                <h5 id="question-text">1. What does CPU stand for?</h5>
                <div class="mt-3">
                    <div class="form-check">
                        <input type="radio" name="option" class="form-check-input" id="a" />
                        <label class="form-check-label" for="a">Central Processing Unit</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="option" class="form-check-input" id="b" />
                        <label class="form-check-label" for="b">Central Program Unit</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="option" class="form-check-input" id="c" />
                        <label class="form-check-label" for="c">Control Processing Unit</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="option" class="form-check-input" id="d" />
                        <label class="form-check-label" for="d">Central Processor Utility</label>
                    </div>
                </div>
            </div>
            <div class="card-footer text-muted text-end">
                <button id="nextBtn" class="btn btn-primary">Save &amp; Next</button>
            </div>
        </div>
    </main>
</body>
</html>