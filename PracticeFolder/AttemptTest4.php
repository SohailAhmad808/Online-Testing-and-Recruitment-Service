<?php
require_once 'connect.php';

// ============= CONFIG ==================
$user_id = 1; // Replace with your actual logged-in user ID
$qp_id = isset($_GET['qp_id']) ? intval($_GET['qp_id']) : 0;
if ($qp_id <= 0) {
    die("Invalid quiz ID.");
}

// ============= INITIALIZE QUIZ SESSION ==================
if (!isset($_SESSION['quiz'][$qp_id])) {
    $_SESSION['quiz'][$qp_id] = [
        'current' => 0,
        'answers' => [],
        'start_time' => time()
    ];
}

// ============= HANDLE ANSWER SUBMISSION ==================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    $answer = $_POST['answer'];
    $question_id = intval($_POST['question_id']);
    $_SESSION['quiz'][$qp_id]['answers'][$question_id] = $answer;

    // Save answer in DB
    $save_sql = "INSERT INTO quiz_results (user_id, qp_id, question_id, answer)
                 VALUES (?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE answer = ?";
    $stmt = $conn->prepare($save_sql);
    $stmt->bind_param("iiiss", $user_id, $qp_id, $question_id, $answer, $answer);
    $stmt->execute();

    // Move to next question
    $_SESSION['quiz'][$qp_id]['current']++;
    header("Location: attempt.php?qp_id=" . $qp_id);
    exit;
}

// ============= FETCH CURRENT QUESTION ==================
$currentIndex = $_SESSION['quiz'][$qp_id]['current'];
$total_sql = "SELECT COUNT(*) AS total FROM question_paper_items WHERE qp_id = ?";
$stmt = $conn->prepare($total_sql);
$stmt->bind_param("i", $qp_id);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];

if ($currentIndex >= $total) {
    // Quiz completed
    echo "<!DOCTYPE html><html lang='en'><head>
          <meta charset='UTF-8'>
          <title>Quiz Completed</title>
          <link rel='stylesheet' href='../AttachtableFile/bootstrap-5.3.7-dist/css/bootstrap.css'>
          </head><body class='bg-light'>
          <div class='container mt-5 text-center'>
            <div class='card p-5 shadow'>
              <h2>🎉 Quiz Completed!</h2>
              <p>Your responses have been saved successfully.</p>
              <a href='results.php?qp_id=$qp_id' class='btn btn-success'>View Results</a>
            </div>
          </div>
          </body></html>";
    unset($_SESSION['quiz'][$qp_id]);
    exit;
}

// Get the current question
$question_sql = "SELECT q.question_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d
                 FROM question_paper_items qpi
                 JOIN questions_pool q ON qpi.question_id = q.question_id
                 WHERE qpi.qp_id = ?
                 LIMIT 1 OFFSET ?";
$stmt = $conn->prepare($question_sql);
$stmt->bind_param("ii", $qp_id, $currentIndex);
$stmt->execute();
$question = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Attempt Quiz</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../AttachtableFile/bootstrap-5.3.7-dist/css/bootstrap.css">
  <style>
    body { background-color: #f8f9fa; }
    .quiz-card { max-width: 700px; margin: auto; margin-top: 60px; }
  </style>
</head>
<body>

  <main class="col-md-9 col-lg-10 mx-auto">
    <div class="quiz-card card shadow-lg">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Question <?= $currentIndex + 1 ?> of <?= $total ?></h5>
        <span>Time Left: <span id="timer">30</span>s</span>
      </div>
      <div class="card-body">
        <form method="POST" id="quizForm">
          <h5><?= htmlspecialchars($question['question_text']) ?></h5>
          <hr>
          <?php foreach (['a','b','c','d'] as $opt):
            $field = "option_$opt"; ?>
            <div class="form-check my-2">
              <input class="form-check-input" type="radio" name="answer" id="opt<?= $opt ?>" value="<?= $field ?>" required>
              <label class="form-check-label" for="opt<?= $opt ?>">
                <?= htmlspecialchars($question[$field]) ?>
              </label>
            </div>
          <?php endforeach; ?>
          <input type="hidden" name="question_id" value="<?= $question['question_id'] ?>">
          <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Save & Next</button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <script>
    // Timer logic — auto submit after 30 seconds
    let timeLeft = 30;
    const timerEl = document.getElementById("timer");
    const form = document.getElementById("quizForm");

    const countdown = setInterval(() => {
      timeLeft--;
      timerEl.textContent = timeLeft;
      if (timeLeft <= 0) {
        clearInterval(countdown);
        form.submit();
      }
    }, 1000);
  </script>

  <script src="../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
</body>
</html>
