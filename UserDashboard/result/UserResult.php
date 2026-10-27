<?php
    require_once '../connect/connect.php';
    // ===============================
        // FETCH ALL PREVIOUS USER RESULTS
        // ===============================
        $sql = "
            SELECT 
            tr.*, 
            qp.*, 
            p.title AS post_name
            FROM quiz_results tr
            JOIN question_papers qp ON qp.qp_id = tr.qp_id
            JOIN posts p ON p.post_id = qp.post_id
            WHERE tr.id = ?
            ORDER BY tr.attempt_date DESC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "i",
            $user_id
        );
        $stmt->execute();
        $results = $stmt->get_result();
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
        <h3 class="fw-bold text-secondary">Previous Test Results</h3>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">PostName</th>
                        <th scope="col">Date</th>
                        <th scope="col">Obtain marks</th>
                        <th scope="col">Total marks</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($results->num_rows > 0): ?>
                        <?php while($row = $results->fetch_assoc()): ?>
                            <?php $modalId = "resultModal" . $row['result_id']; ?>
                            <tr>
                                <td><?= htmlspecialchars($row['post_name']) ?></td>
                                <td><?= htmlspecialchars($row['attempt_date']) ?></td>
                                <td><?= htmlspecialchars($row['obtained_marks']) ?></td>
                                <td><?= htmlspecialchars($row['total_marks']) ?></td>
                                <td>
                                    <!-- Button triggers modal -->
                                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">
                                        View Details
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal for this specific row -->
                        <?php endwhile; ?>
                    <?php endif; ?>
                    </tbody>
                    </table>

                    <!-- Modals should be outside the table -->
                    <?php
                    $results->data_seek(0); // Reset result pointer
                    while($row = $results->fetch_assoc()):
                        $modalId = "resultModal" . $row['result_id'];
                    ?>
                    <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="<?= $modalId ?>Label"><?= htmlspecialchars($row['post_name']) ?> Result</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Post Name:</strong> <?= htmlspecialchars($row['post_name']) ?></p>
                                    <p><strong>Date:</strong> <?= htmlspecialchars($row['attempt_date']) ?></p>
                                    <p><strong>Obtained Marks:</strong> <?= htmlspecialchars($row['obtained_marks']) ?></p>
                                    <p><strong>Total Marks:</strong> <?= htmlspecialchars($row['total_marks']) ?></p>
                                    <p><strong>Percentage:</strong> <?= round(($row['obtained_marks'] / $row['total_marks']) * 100, 2) ?>%</p>
                                    <p><strong>Status:</strong> <?= ($row['obtained_marks'] >= ($row['total_marks'] * 0.5)) ? 'Pass' : 'Fail' ?></p>
                                    <p><strong>Remarks:</strong> <?= ($row['obtained_marks'] >= ($row['total_marks'] * 0.8)) ? 'Excellent performance' : 'Good performance' ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>

                  
        </div>
    </div>
    </main>
  </div>
    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  </body>
</html>