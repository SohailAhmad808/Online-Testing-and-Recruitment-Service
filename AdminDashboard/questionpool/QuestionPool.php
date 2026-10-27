<?php
  require_once '../Connect.php';

  // Checking of questions are passed are not
  if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['numQuestions']) && $_POST['numQuestions'] > 0){
    // Getting number of questions
    $numQuestions = $_POST['numQuestions'];
    // Prepare SQL statement
    $sql = "INSERT INTO questions_pool (question_text, option_a, option_b, option_c, option_d, correct_option)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    // Loop through each question fields
    for ($i = 1; $i <= $numQuestions; $i++) {
            $question_text = trim($_POST["question_$i"]);
            $option_a = trim($_POST["optionA_$i"]);
            $option_b = trim($_POST["optionB_$i"]);
            $option_c = trim($_POST["optionC_$i"]);
            $option_d = trim($_POST["optionD_$i"]);
            $correct_letter = trim($_POST["correct_$i"]);

              switch ($correct_letter) {
                  case "A":
                      $correct_option = $option_a;
                      break;
                  case "B":
                      $correct_option = $option_b;
                      break;
                  case "C":
                      $correct_option = $option_c;
                      break;
                  case "D":
                      $correct_option = $option_d;
                      break;
              }

            $stmt->bind_param(
              "ssssss",
              $question_text,
              $option_a,
              $option_b,
              $option_c,
              $option_d,
              $correct_option
            );
            $stmt->execute();
        }
        $stmt->close();
        echo "<script>alert('All Questions Saved Successfully!'); window.location.href='QuestionPool.php';</script>";
        exit;
  }
  // Deleting specific question start here
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'])){
    $question_id = intval($_POST['question_id']);
    // Deleting query
    $sqlDelete = "DELETE FROM questions_pool WHERE question_id = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param(
      "i",
      $question_id
    );
    // If query was executed
    if ($stmtDelete->execute()) {
        echo "<script>alert('Question Deleted Successfully!'); window.location.href='QuestionPool.php';</script>";
        exit;
    } else {
        echo "<script>alert('Failed to Delete Question!');</script>";
    }
    $stmtDelete->close();

  }
  $sql2 = "SELECT * FROM questions_pool ORDER BY question_id DESC";
  $stmt2 = $conn->prepare($sql2);
  $stmt2->execute();
  $result2 = $stmt2->get_result();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Header</title>
    <link rel="stylesheet" href="../../AttachtableFile/bootstrap-5.3.7-dist/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
  </head>
  <body>  
    <!--     Admin Header bar    -->
     <?php
        require_once '../alluses/AdminHeader.php';
     ?>
     <!--   Admin Sidebar   -->
      <?php
        require_once '../alluses/AdminSidebar.php';
      ?>
    <main class="col-md-9 col-lg-9">
        
      <div class="container mt-3">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add question to the list</h5>
          </div>
          <div class="card-body">
            <form id="quizForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

              <!-- Number of Questions -->
              <div class="mb-3">
                <label for="numQuestions" class="form-label fw-semibold">Number of Questions</label>
                <input type="number" class="form-control" id="numQuestions" name="numQuestions" placeholder="Enter number of MCQs" min="1" max="50" required>
              </div>

              <!-- Questions Section -->
              <div id="questionsContainer"></div>

              <!-- Generate Questions Button -->
              <div class="text-end">
                <button type="button" class="btn btn-outline-primary" id="generateQuestions">Generate Questions</button>
              </div>

              <hr>

              <!-- Submit Button -->
              <div class="text-center mt-4">
                <button type="submit" name="savbtn" class="btn btn-success px-4">Save questions</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="container py-4">
        <div class="card border-0 shadow-lg">
          <div class="card-header bg-primary text-white text-white text-center">
            <h4 class="mb-0">Questions From the Database</h4>
          </div>

          <div class="card-body">

            <!-- ✅ Search Bar -->
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Search question...">
              <button class="btn btn-primary">Search</button>
            </div>

            <!-- ✅ Questions List -->
            <div class="accordion" id="questionAccordion">

              <!-- 🔽 Repeat this block using PHP loop -->
               <?php
                $qNo = 1;
                  if($result2->num_rows > 0){
                    while($row2 = $result2->fetch_assoc()){
                        $question_id = $row2['question_id'];
                        $option_a = htmlspecialchars($row2['option_a']);
                        $option_b = htmlspecialchars($row2['option_b']);
                        $option_c = htmlspecialchars($row2['option_c']);
                        $option_d = htmlspecialchars($row2['option_d']);
                        $question_text = htmlspecialchars($row2['question_text']);
                        $correct_option = $row2['correct_option'];  
              ?>
              <div class="accordion-item mb-2 shadow-sm">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#q<?php echo $qNo; ?>">
                     <?php echo "Q" . $qNo . ": " . $question_text; ?>
                  </button>
                </h2>
                <div id="q<?php echo $qNo; ?>" class="accordion-collapse collapse" data-bs-parent="#questionAccordion">
                  <div class="accordion-body">

                    <ul class="list-group mb-3">
                      <li class="list-group-item <?= $correct_option=='A'?'list-group-item-success fw-bold':'' ?>" >A. <?= $option_a ?> <?= $correct_option=='A'?'✅':'' ?></li>
                      <li class="list-group-item <?= $correct_option=='B'?'list-group-item-success fw-bold':'' ?>" >B. <?= $option_b ?> <?= $correct_option=='B'?'✅':'' ?></li>
                      <li class="list-group-item <?= $correct_option=='C'?'list-group-item-success fw-bold':'' ?>" >C. <?= $option_c ?> <?= $correct_option=='C'?'✅':'' ?></li>
                      <li class="list-group-item <?= $correct_option=='D'?'list-group-item-success fw-bold':'' ?>" >D. <?= $option_d ?> <?= $correct_option=='D'?'✅':'' ?></li>
                    </ul>

                    <div class="text-end">
                      <form method="POST" action="QuestionPool.php">
                        <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this question?');">
                            Delete
                        </button>
                    </form>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- 🔼 End Repeat Block -->
               <?php
                      $qNo++;
                    }
                  }
               ?>

            </div>

          </div>
        </div>
      </div>

      <script>
        document.querySelector('button[name="savbtn"]').disabled = true;
        // Generate question fields dynamically
        document.getElementById('generateQuestions').addEventListener('click', function() {
          const num = document.getElementById('numQuestions').value;
          const container = document.getElementById('questionsContainer');
          container.innerHTML = ''; // clear old content

          if (num <= 0) {
            alert("Please enter a valid number of questions.");
            return;
          }


          for (let i = 1; i <= num; i++) {
            const questionHTML = `
              <div class="border rounded p-3 mb-3 bg-light">
                <h6 class="fw-semibold">Question ${i}</h6>
                <div class="mb-2">
                  <label class="form-label">Question Text</label>
                  <input type="text" class="form-control" name="question_${i}" placeholder="Enter question ${i}" required>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-2">
                    <label class="form-label">Option A</label>
                    <input type="text" class="form-control" name="optionA_${i}" required>
                  </div>
                  <div class="col-md-6 mb-2">
                    <label class="form-label">Option B</label>
                    <input type="text" class="form-control" name="optionB_${i}" required>
                  </div>
                  <div class="col-md-6 mb-2">
                    <label class="form-label">Option C</label>
                    <input type="text" class="form-control" name="optionC_${i}" required>
                  </div>
                  <div class="col-md-6 mb-2">
                    <label class="form-label">Option D</label>
                    <input type="text" class="form-control" name="optionD_${i}" required>
                  </div>
                </div>
                <div class="mb-2">
                  <label class="form-label">Correct Answer</label>
                  <select class="form-select" name="correct_${i}" required>
                    <option value="" disabled selected>Select correct option</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                  </select>
                </div>
              </div>
            `;
            container.insertAdjacentHTML('beforeend', questionHTML);
          }
          document.querySelector('button[name="savbtn"]').disabled = false;
        });
        


        
      </script>



        </main>
  </div>
    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  </body>
</html>