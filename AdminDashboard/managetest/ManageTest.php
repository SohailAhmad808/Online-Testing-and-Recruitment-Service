<?php
  require_once '../Connect.php';

  // ================= DELETE PAPER ==================
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Delete_Paper'])){


    $qp_id = intval($_POST['post_id']);  // this is qp_id from form



    // Delete the actual paper
    $delete_paper_sql = "DELETE FROM question_papers WHERE qp_id = ?";
    $stmt_paper = $conn->prepare($delete_paper_sql);
    $stmt_paper->bind_param("i", $qp_id);

    if($stmt_paper->execute()){
        echo "<script>alert('Paper Deleted Successfully');</script>";
        echo "<script>window.location.href='".$_SERVER['PHP_SELF']."';</script>";
        exit;
    } else {
        echo "<script>alert('Error deleting paper');</script>";
    }
}


  // ================= SAVE PAPER ==================
if(isset($_POST['create_paper'])){
    $combined = $_POST['JobSelect'];
    list($post_id, $qp_title) = explode('|', $combined);
    $num_ques = intval($_POST['number_of_questions']);
    $duration = intval($_POST['duration']);
    $start_time = $_POST['start_time'];          // Get the input value
    $start_time = str_replace('T', ' ', $start_time);  // Convert to MySQL format
    $start_time .= ':00';
    $selected_questions = $_POST['selected_questions'] ?? [];

    if(count($selected_questions) != $num_ques){
        echo "<script>alert('You must select exactly $num_ques questions!');</script>";
    } else {
        // Insert new paper
        $insertPaper = "INSERT INTO question_papers (post_id, qp_title, number_of_questions, duration, test_start_time)
                        VALUES(?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertPaper);
        $stmt->bind_param(
          "isiis",
          $post_id,
          $qp_title,
          $num_ques,
          $duration,
          $start_time
        );
        $stmt->execute();

        $paper_id = $conn->insert_id;

        // Insert selected MCQs
        foreach($selected_questions as $qid){
            $insertPQ = "INSERT INTO question_paper_items (qp_id, question_id) VALUES(?, ?)";
            $stmt2 = $conn->prepare($insertPQ);
            $stmt2->bind_param(
              "ii",
              $paper_id,
              $qid
            );
            $stmt2->execute();
        }

        echo "<script>alert('Paper Created Successfully'); window.location='ManageTest.php';</script>";
    }
  }

  // Getting the all post
  $myposts_sqll = "SELECT post_id, title FROM posts WHERE typ = 'cbt'";
  $mypost_stmt = $conn->prepare($myposts_sqll);
  $mypost_stmt->execute();
  $result = $mypost_stmt->get_result();

  // Retrieving all questions from question paper
  $myquestions_sql = "SELECT * From questions_pool";
  $myquestions_stmt = $conn->prepare($myquestions_sql);
  $myquestions_stmt->execute();
  $myq = $myquestions_stmt->get_result();




  
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
    <main class="col-md-9 col-lg-10">
      
      <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-header bg-primary-subtle">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="fs-4">My Question papers</h3>
                            <button class="btn btn-outline-primary btn-sm openCreateModal"
                              data-bs-toggle="modal" data-bs-target="#CreatingQuestionPaper">
                              + Create new
                            </button>
                        </div>
                    </div>
                </div>
<!-- The List of All Question paper Starts Here  -->
                
                  <?php 
                  // Fetch all question papers
                    $papers_sql = "SELECT qp.qp_id, p.title AS post_title, qp.number_of_questions, qp.duration, qp.test_start_time 
                      FROM question_papers qp
                      JOIN posts p ON p.post_id = qp.post_id
                      ORDER BY qp.qp_id DESC";
                    $papers_stmt = $conn->prepare($papers_sql);
                    $papers_stmt->execute();
                    $papers_result = $papers_stmt->get_result();
                  ?>
                <div class="accordion accordion-flush" id="accordionFlushExample">
                  <?php
                    $counter = 1;
                    while($paper = $papers_result->fetch_assoc()) {
                        $qp_id = $paper['qp_id'];

                        // Fetch questions for this paper
                        $questions_sql = "SELECT q.* 
                                          FROM question_paper_items qpi
                                          JOIN questions_pool q ON q.question_id = qpi.question_id
                                          WHERE qpi.qp_id = ?";
                        $questions_stmt = $conn->prepare($questions_sql);
                        $questions_stmt->bind_param("i", $qp_id);
                        $questions_stmt->execute();
                        $questions_result = $questions_stmt->get_result();
                    ?>
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="<?= $counter ?>">
                      <button class="accordion-button collapsed text-wrap" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $counter ?>" aria-expanded="false" aria-controls="flush-collapse<?= $counter ?>">
                        <?php 
                          echo $paper['post_title'] . " (Paper #" . $counter . ")"; 
                        ?>
                      </button>
                    </h2>
                    <div id="flush-collapse<?= $counter ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $counter ?>" data-bs-parent="#accordionFlushExample">
                      <div class="accordion-body container-fluid w-100">
                        <div class="container-fluid w-100 ">
                        <p><strong>Paper Title:</strong> <?= $paper['post_title'] ?></p>
                        <p><strong>Date:</strong> <?= date('d/m/Y', strtotime($paper['test_start_time'])) ?></p>
                        <p><strong>Duration(Minutes):</strong> <?= $paper['duration'] ?> minutes</p>
                        <p><strong>Test Start Time:</strong> <?= $paper['test_start_time'] ?></p>
                        <p><strong>Total Questions:</strong> <?= $paper['number_of_questions'] ?></p>
                        <!-------------- Form for the deleting the -->
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                          <input type="hidden" name="post_id" value="<?php echo $paper['qp_id']; ?>">
                          <div class="text-center">
                            <button class="btn btn-outline-danger" name="Delete_Paper" >Delete</button>
                          </div>
                        </form>
                        <hr>
                        <ul class="list-group ">
                          <?php
                            $qnum = 1;
                            while($q = $questions_result->fetch_assoc()) {
                          ?>
                          <li class="list-group-item">
                            <p><strong> <?= $qnum ?>. <?= $q['question_text'] ?></strong></p>
                            <div class="ms-3">
                              <p>A. <?= $q['option_a'] ?></p>
                              <p>B. <?= $q['option_b'] ?></p>
                              <p>C. <?= $q['option_c'] ?></p>
                              <p>D. <?= $q['option_d'] ?></p>
                            </div>
                          </li>
                          <?php
                            $qnum++;
                          }
                          ?>
                        </ul>
                      </div>
                      </div>
                    </div>
                  </div>
                  <?php 
                  $counter++;
                    }
                  ?>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="CreatingQuestionPaper" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel"><i class="fa-solid fa-file-pen me-2"></i> Create Question paper</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" id="question_creations" method="POST">
                            <div class="row">
                                <div class="col-md-6 my-2">
                                  
                                    <label for="SelectPost" class="form-label">Select Post</label>
                                    <select name="JobSelect" id="Myjob" class="form-select" required>
                                      <option selected disabled>--Select title--</option>
                                      <?php
                                          while($row = mysqli_fetch_assoc($result)){

                                        ?>
                                      <option value="<?php echo $row['post_id'].'|'.$row['title']; ?>"><?php echo $row['title'];  ?></option>
                                      <?php 
                                          }
                                      ?>
                                    </select>
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="NoQuestion" class="form-label">Number of Questions</label>
                                    <input type="text" id="NoQuestion" class="form-control" name="number_of_questions" placeholder="e.g 20">
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="Time" class="form-label">Duration(Minutes)</label>
                                    <input type="text" id="Time" class="form-control" name="duration" placeholder="e.g 30">
                                </div>
                                <div class="col-md-6 my-2">
                                    <label for="starttime" class="form-label">Test Start time</label>
                                    <input type="datetime-local" id="starttime" name="start_time" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                              <h6 class="fw-bold mb-3">Select Questions from Pool</h6>
                              <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table">
                                  <thead>
                                    <tr>
                                      <th style="width: 5%;"><input type="checkbox" name="" id="selectAll"></th>
                                      <th style="width: 30%;">Question</th>
                                      <th>Option A</th>
                                      <th>Option B</th>
                                      <th>Option C</th>
                                      <th>Option D</th>
                                      <th>Correct</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                          while($row2 = mysqli_fetch_assoc($myq)){

                                        ?>
                                    <tr>
                                      <td>
                                        <input type="checkbox" 
                                        class="form-check-input question-select"
                                        name="selected_questions[]" 
                                        value="<?= $row2['question_id']; ?>"
                                        >
                                      </td>
                                      <td><?php echo $row2['question_text'];  ?></td>
                                      <td><?php echo $row2['option_a'];  ?></td>
                                      <td><?php echo $row2['option_b'];  ?></td>
                                      <td><?php echo $row2['option_c'];  ?></td>
                                      <td><?php echo $row2['option_d'];  ?></td>
                                      <td><?php echo $row2['correct_option'];  ?></td>
                                    </tr>
                                    <?php
                                          }  
                                    ?>
                                    
                                  </tbody>
                                </table>

                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" name="create_paper" form="question_creations"  class="btn btn-outline-primary"><i class="fa-solid fa-floppy-disk me-1"></i>Create Paper</button>
                          </div>

                        </form>
                    </div>
                    
                    </div>
                </div>
                </div>
            </div>


        </div>

    </main>
  </div>
    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  <script>
  // Simple "Select All" checkbox
  document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.question-select');
    checkboxes.forEach(cb => cb.checked = this.checked);
  });
</script>

  </body>
</html>