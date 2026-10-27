<?php 
require_once '../connect/connect.php';
date_default_timezone_set('Asia/Karachi');


// Total time per question
$question_time = 30;

// Each question has its own timer key
$timer_key = "timer_q_" . $_SESSION['q_index'];

// If starting this question for first time → set start time
if(!isset($_SESSION[$timer_key])){
    $_SESSION[$timer_key] = time();
}

// Calculate elapsed and remaining time
$elapsed = time() - $_SESSION[$timer_key];
$remaining = $question_time - $elapsed;

// If time expired → move to next question
if ($remaining <= 0) {
    $_SESSION['q_index']++;
    unset($_SESSION[$timer_key]);  // Reset timer for next question
    header("Location: AttemptTest.php");
    exit;
}

// Step 1: Receive POST only once
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["qp_id"])) {
    $_SESSION['qp_id'] = intval($_POST['qp_id']);
    // Check if user already attempted
    $check_sql = "SELECT attempted FROM validate_attempt WHERE user_id = ? AND qp_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $_SESSION['qp_id']);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $check = $result->num_rows > 0;
    if (!$check && !isset($_SESSION['q_index'])) {
        // Mark user as attempted
        $update_sql = "INSERT INTO validate_attempt (user_id, qp_id, attempted) VALUES (?, ?, 1)";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $user_id, $_SESSION['qp_id']);
        $update_stmt->execute();
        
        $_SESSION['q_index'] = 0;
        $_SESSION['test_start_time'] = time(); // Initialize test timing
        header("Location: AttemptTest.php");
        exit;
    } else {
        echo "<script>alert('Already attempted!'); window.location.href='UserTest.php';</script>";
        exit;
    }
    
}

// Step 3: On refresh, load qp_id from session
if (!isset($_SESSION['qp_id']) || $_SESSION["qp_id"] <= 0) {
    die("qp_id not set");
}
$qp_id = $_SESSION['qp_id'];

// session creation for the starting question loading in the page.
if (!isset($_SESSION['q_index'])) {
    $_SESSION['q_index'] = 0;
}
$q_index = $_SESSION['q_index'];



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
// If finished
if ($q_index >= $total_questions) {
    echo "<h2 class='text-center mt-5'>Quiz Completed!</h2>";
    // Reset session or destroy
        unset($_SESSION['score']);
        unset($_SESSION['qp_id']);
        unset($_SESSION['q_index']);
        unset($_SESSION['test_start_time']);
        unset($_SESSION[$timer_key]);
        ?>
        <div class="text-center">
        <a class="btn btn-primary" href="UserTest.php" role="button">Link</a>
        </div>

    <?php
    
}
// Current question
$current = $questions[$q_index];
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
                    <h5 class="mb-0" id="questionNumber">Question <?php echo $q_index + 1; ?> / <?php echo $total_questions ?></h5>
                    <span class=" fs-6">
                    <i class="fa-solid fa-clock me-1"></i><span id="timeLeft">Time left : 30</span>s
                    </span>
                </div>
            <div class="progress" style="height: 20px;">
                    <div class="progress-bar progress-bar-striped" 
                    role="progressbar" 
                    style="width: <?php echo (($q_index + 1) / $total_questions) * 100; ?>%;" 
                    aria-valuenow="25" 
                    aria-valuemin="0" 
                    aria-valuemax="100">
                    <?php echo $q_index + 1; ?>/<?php echo $total_questions ?>
                </div>
                </div>
            </div>
        <form action="process_test.php" method="POST">
            <div class="card-body">
                <h5 id="question-text"><?php echo $current['question_text']; ?></h5>
                <input type="hidden" name="question_id" value="<?php echo $current['question_id']; ?>">
                <div class="mt-3">
                    <div class="form-check">
                        <input type="radio" name="option" class="form-check-input" id="a" value="<?php echo $current['option_a']; ?>"/>
                        <label class="form-check-label" for="a" ><?php echo $current['option_a']; ?></label>
                        
                    </div>
                    <div class="form-check">
                        <input type="radio" name="option" class="form-check-input" id="b" value="<?php echo $current['option_b']; ?>" />
                        <label class="form-check-label" for="b" ><?php echo $current['option_b']; ?></label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="option" class="form-check-input" id="c" value="<?php echo $current['option_c']; ?>" />
                        <label class="form-check-label" for="c" ><?php echo $current['option_c']; ?></label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="option" class="form-check-input" id="d" value="<?php echo $current['option_d']; ?>"/>
                        <label class="form-check-label" for="d" ><?php echo $current['option_d']; ?></label>
                    </div>
                </div>
            </div>
            <div class="card-footer text-muted text-end">
                <button type="submit" id="nextBtn" name="saveNext" class="btn btn-primary">Save &amp; Next</button>
            </div>
        </form>
        </div>
    </main>
    
<script>
let timeLeft = <?php echo $remaining; ?>;

let timer = setInterval(() => {
    document.getElementById("timeLeft").innerText = timeLeft;

    if (timeLeft <= 0) {
        clearInterval(timer);
        document.querySelector("form").submit();
    }

    timeLeft--;
}, 1000);
</script>


</body>
<script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
</html>