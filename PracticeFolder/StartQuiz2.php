<?php
$conn = new mysqli("localhost", "root", "", "fyp");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$qp_id = 12;

// Fetch questions
$q_sql = "SELECT q.question_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option
        FROM question_paper_items qpi
        JOIN questions_pool q ON q.question_id = qpi.question_id
        WHERE qpi.qp_id = ?
        ORDER BY qpi.qpi_id ASC";
$q_stmt = $conn->prepare($q_sql);
$q_stmt->bind_param("i", $qp_id);
$q_stmt->execute();
$questions = $q_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$total_questions = count($questions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attempt Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <main class="">
        <h2 class="text-center bg-primary-subtle p-3">Quiz</h2>
        <div class="card col-md-9 col-lg-8 mx-auto my-md-4">
            <div class="card-body" id="MyQuestionArea">
                <!-- Question will be loaded here by JavaScript -->
            </div>
            <div class="card-footer text-muted text-end">
                <button id="prevBtn" class="btn btn-secondary me-2">Previous</button>
                <button id="nextBtn" class="btn btn-primary">Next</button>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        const questions = <?= json_encode($questions) ?>;
        const totalQuestions = <?= $total_questions ?>;
        
        // Get current question from localStorage or start from 0
        let currentQuestionIndex = parseInt(localStorage.getItem('currentQuestionIndex')) || 0;

        // Initialize the quiz
        $(document).ready(function() {
            showQuestion(currentQuestionIndex);
            updateButtonStates();
            
            // Next button click
            $('#nextBtn').click(function() {
                if (currentQuestionIndex < totalQuestions - 1) {
                    currentQuestionIndex++;
                    saveProgress();
                    showQuestion(currentQuestionIndex);
                    updateButtonStates();
                }
            });
            
            // Previous button click
            $('#prevBtn').click(function() {
                if (currentQuestionIndex > 0) {
                    currentQuestionIndex--;
                    saveProgress();
                    showQuestion(currentQuestionIndex);
                    updateButtonStates();
                }
            });
        });

        function showQuestion(index) {
            const question = questions[index];
            
            let optionsHtml = '';
            const options = [
                { key: 'A', value: question.option_a },
                { key: 'B', value: question.option_b },
                { key: 'C', value: question.option_c },
                { key: 'D', value: question.option_d }
            ];
            
            options.forEach(option => {
                optionsHtml += `
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="selected_option" 
                               value="${option.key}" id="option_${option.key.toLowerCase()}">
                        <label class="form-check-label" for="option_${option.key.toLowerCase()}">
                            ${option.value}
                        </label>
                    </div>
                `;
            });
            
            const questionHtml = `
                <h5>Question ${index + 1} of ${totalQuestions}</h5>
                <h6 class="mt-3">${question.question_text}</h6>
                <div class="mt-3">
                    ${optionsHtml}
                </div>
            `;
            
            $('#MyQuestionArea').html(questionHtml);
        }

        function updateButtonStates() {
            $('#prevBtn').prop('disabled', currentQuestionIndex === 0);
            $('#nextBtn').prop('disabled', currentQuestionIndex === totalQuestions - 1);
            
            // Change next button text on last question
            if (currentQuestionIndex === totalQuestions - 1) {
                $('#nextBtn').text('Last Question');
            } else {
                $('#nextBtn').text('Next');
            }
        }

        function saveProgress() {
            localStorage.setItem('currentQuestionIndex', currentQuestionIndex);
        }

        // Save progress when page is about to be unloaded (refresh/close)
        window.addEventListener('beforeunload', saveProgress);
    </script>
</body>
</html>