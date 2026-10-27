<?php
    $conn = new mysqli("localhost", "root", "", "fyp");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
}
    $qp_id = 12;
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
    echo $total_questions;
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
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
    <script src="../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
</body>
</html>