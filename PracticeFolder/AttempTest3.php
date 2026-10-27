<?php
require_once 'connect.php';
$qp_id = isset($_GET['qp_id']) ? intval($_GET['qp_id']) : 0;
$questions = [];
if($qp_id > 0) {
    // Fetch questions for this paper from DB
    $questions_sql = "SELECT q.question_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d 
                      FROM question_paper_items qpi
                      JOIN questions_pool q ON qpi.question_id = q.question_id
                      WHERE qpi.qp_id = ?";
    $stmt = $conn->prepare($questions_sql);
    $stmt->bind_param("i", $qp_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Attempt Test</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../AttachtableFile/bootstrap-5.3.7-dist/css/bootstrap.css">
</head>
<body>

  <!-- Main content -->
  <main class="col-md-9 col-lg-10 mx-auto">
    <div class="container-fluid">
      <h2 class="text-center bg-primary-subtle p-3">Quiz</h2>
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div id="quiz-card" class="card">
            <div class="card-header text-end">
              Time left: <span id="time">30</span> seconds
            </div>
            <div class="card-body">
              <h5 id="question-text"></h5>
              <div id="options-container"></div>
            </div>
            <div class="card-footer text-end">
              <button id="nextBtn" class="btn btn-primary">Save &amp; Next</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Quiz logic using JavaScript -->
  <script>
    // Parse PHP questions array into JavaScript
    var questions = <?php echo json_encode($questions); ?>;
    var currentQuestion = 0;
    var timerInterval;

    // Display the current question and options
    function showQuestion() {
        if (currentQuestion >= questions.length) {
            finishQuiz();
            return;
        }
        // Clear previous question content
        var questionText = document.getElementById("question-text");
        var optionsContainer = document.getElementById("options-container");
        questionText.innerHTML = "";
        optionsContainer.innerHTML = "";

        // Set question text
        questionText.textContent = questions[currentQuestion].question_text;

        // Create radio options for A, B, C, D
        var opts = ['option_a','option_b','option_c','option_d'];
        opts.forEach(function(opt, index) {
            var optionText = questions[currentQuestion][opt];
            var div = document.createElement('div');
            div.className = 'form-check';
            var input = document.createElement('input');
            input.className = 'form-check-input';
            input.type = 'radio';
            input.name = 'answer';
            input.id = 'opt' + index;
            input.value = opt;
            var label = document.createElement('label');
            label.className = 'form-check-label';
            label.htmlFor = 'opt' + index;
            label.textContent = optionText;
            div.appendChild(input);
            div.appendChild(label);
            optionsContainer.appendChild(div);
        });

        // Reset and start the timer
        resetTimer();
        startTimer();
    }

    // Start countdown from 30 seconds
    function startTimer() {
        var timeEl = document.getElementById('time');
        var time = parseInt(timeEl.textContent);
        timerInterval = setInterval(function() {
            time--;
            timeEl.textContent = time;
            if (time <= 0) {
                clearInterval(timerInterval);
                nextQuestion();  // Auto-move to next question on timeout
            }
        }, 1000);
    }

    // Reset the timer display to 30
    function resetTimer() {
        clearInterval(timerInterval);
        document.getElementById('time').textContent = 30;
    }

    // Handle the “Save & Next” button click
    document.getElementById('nextBtn').addEventListener('click', function() {
        clearInterval(timerInterval);
        nextQuestion();
    });

    // Move to next question or end quiz if done
    function nextQuestion() {
        currentQuestion++;
        if (currentQuestion < questions.length) {
            showQuestion();
        } else {
            finishQuiz();
        }
    }

    // When quiz is complete, replace card with completion message
    function finishQuiz() {
        var card = document.getElementById('quiz-card');
        card.innerHTML = '<div class="card-body text-center"><h3>Quiz Completed!</h3><p>You have finished the quiz.</p></div>';
    }

    // Initialize or show “no questions” message
    if (questions.length > 0) {
        showQuestion();
    } else {
        document.getElementById('quiz-card').innerHTML =
          '<div class="card-body text-center"><h3>No questions available.</h3></div>';
    }
  </script>

  <!-- Bootstrap JS bundle -->
  <script src="../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
</body>
</html>
