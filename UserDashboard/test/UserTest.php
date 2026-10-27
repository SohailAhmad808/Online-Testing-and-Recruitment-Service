<?php
    require_once '../connect/connect.php';
    // ensure PHP uses Pakistan time
    date_default_timezone_set('Asia/Karachi');
    // Get current datetime
    $now = date('Y-m-d H:i:s');

    // ===================== Fetch Tests =====================
    // Ongoing Tests: tests that are currently active
    $ongoing_sql = "SELECT qp.qp_id, p.title AS post_title, qp.number_of_questions, qp.duration, qp.test_start_time,
                       DATE_ADD(qp.test_start_time, INTERVAL qp.duration MINUTE) AS test_end_time
                FROM question_papers qp
                JOIN posts p ON p.post_id = qp.post_id
                JOIN applications a ON a.post_id = qp.post_id
                WHERE a.id = ?
                AND a.status = 'Accepted'
                AND qp.test_start_time <= ?
                AND DATE_ADD(qp.test_start_time, INTERVAL qp.duration MINUTE) >= ?
                ORDER BY qp.test_start_time ASC";

                $ongoing_stmt = $conn->prepare($ongoing_sql);
                $ongoing_stmt->bind_param("iss", $user_id, $now, $now);
                $ongoing_stmt->execute();
                $ongoing_result = $ongoing_stmt->get_result();

    // Upcoming Tests: tests that start in the future
    $upcoming_sql = "SELECT qp.qp_id, p.title AS post_title, qp.number_of_questions, qp.duration, qp.test_start_time
                 FROM question_papers qp
                 JOIN posts p ON p.post_id = qp.post_id
                 JOIN applications a ON a.post_id = qp.post_id
                 WHERE a.id = ?
                 AND a.status = 'Accepted'
                 AND qp.test_start_time > ?
                 ORDER BY qp.test_start_time ASC";

    $upcoming_stmt = $conn->prepare($upcoming_sql);
    $upcoming_stmt->bind_param("is", $user_id, $now);
    $upcoming_stmt->execute();
    $upcoming_result = $upcoming_stmt->get_result();

    // Previous Tests: tests that ended in the past
    $previous_sql = "SELECT qp.qp_id, p.title AS post_title, qp.number_of_questions, qp.duration, qp.test_start_time,
                        DATE_ADD(qp.test_start_time, INTERVAL qp.duration MINUTE) AS test_end_time
                 FROM question_papers qp
                 JOIN posts p ON p.post_id = qp.post_id
                 JOIN applications a ON a.post_id = qp.post_id
                 WHERE a.id = ?
                 AND a.status = 'Accepted'
                 AND DATE_ADD(qp.test_start_time, INTERVAL qp.duration MINUTE) < ?
                 ORDER BY qp.test_start_time DESC";

                  $previous_stmt = $conn->prepare($previous_sql);
                  $previous_stmt->bind_param("is", $user_id, $now);
                  $previous_stmt->execute();
                  $previous_result = $previous_stmt->get_result();
    
    
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
    <!--
          Header
          -->
          <?php
          require '../others/UserHeader.php';
          ?>
      <!--
      Sidebar Start Here
      -->
    <?php
    require '../others/UserSidebar.php';
    ?>
    <main class="col-md-9 col-lg-10">

        <div class="container-fluid">

          <h2 class="text-center bg-primary-subtle p-3">
            <i class="fa-solid fa-clipboard-list me-2"></i>
            Available Tests
          </h2>
          <?php $row= 11; ?>

          <!--
            Ongoing Tests
          -->


          <section class="mb-4">
            <h4><i class="fa-solid fa-circle text-danger me-2"></i>Ongoing Tests</h4>
            <div class="row">
              <?php while($row = $ongoing_result->fetch_assoc()) { 
                $end_time = date('d M Y H:i', strtotime($row['test_end_time']));
              ?>
              <div class="col-sm-10 mx-auto my-2">
                <div class="card shadow-lg hover-shadow text-center">
                  <div class="card-body border-start border-4 border-danger">
                    <h5 class="card-title fw-semibold"><?= $row['post_title'] ?></h5>
                    <p class="text-muted small mb-2"><?= date('d M Y H:i', strtotime($row['test_start_time'])) ?> | End: <?= $end_time ?></p>
                    <span class="badge bg-danger mb-2">Open Now</span>
                    <div class="text-end">
                      <form method="POST" action="AttemptTest.php">
                        <input type="hidden" name="qp_id" value="<?= $row['qp_id'] ?>">
                        <button type="submit" class="btn btn-outline-danger">Attempt Test</button>
                    </form>
                    </div>
                  </div>
                </div>
              </div>
              <?php 
              }
              ?>
            </div>
          </section>

          <!--
            Upcoming Tests
          -->

          <section class="mb-4">
            <h4><i class="fa-solid fa-circle text-primary me-2"></i>Upcoming Tests</h4>
            <div class="row">
              <?php while($row = $upcoming_result->fetch_assoc()) { ?>
              <div class="col-sm-6 my-2">
                <div class="card ">
                  <div class="card-body border-start border-4 border-primary">
                    <h5 class="card-title"><?= $row['post_title'] ?></h5>
                    <p class="text-muted">Scheduled: <?= date('d M Y H:i', strtotime($row['test_start_time'])) ?></p>
                    <span class="badge bg-primary">Upcoming</span>
                    <?php
                        $remaining = strtotime($row['test_start_time']) - time();

                        if ($remaining > 0) {
                            $hours   = floor($remaining / 3600);
                            $minutes = floor(($remaining % 3600) / 60);
                            $seconds = $remaining % 60;
                        } else {
                            $hours = $minutes = $seconds = 0;
                        }
                    ?>
                    <p class="mt-2">
                        <strong>Starts In:</strong>
                        <span>
                            <?= $hours ?>h <?= $minutes ?>m <?= $seconds ?>s
                        </span>
                    </p>
                  </div>
                </div>
              </div>
              <?php
              }
              ?>
            </div>
          </section>

          <!--
            Previous Tests
          -->
          <section class="mb-4">
            <h4><i class="fa-solid fa-circle text-secondary me-2"></i>Previous Tests</h4>
            <div class="row">
              <?php while($row = $previous_result->fetch_assoc()) { ?>
              <div class="col-sm-6 my-2">
                <div class="card ">
                  <div class="card-body border-start border-4 border-secondary">
                    <h5 class="card-title"><?= $row['post_title'] ?></h5>
                    <p class="card-text">Your Previous enrolled Tests <?= date('d M Y H:i', strtotime($row['test_end_time'])) ?></p>
                    <div class="text-end">
                      <a href="../result/UserResult.php?= $row['qp_id'] ?>" class="btn btn-outline-secondary">View Result</a>
                    </div>
                  </div>
                </div>
              </div>
              <?php 
              }
              ?>
            </div>
          </section>
            
        </div>


    </main>
  </div>
    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  
  </body>
</html>