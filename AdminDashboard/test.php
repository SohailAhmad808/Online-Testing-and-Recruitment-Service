<?php
require_once 'Connect.php';

// CREATE QUESTION PAPER
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create_paper'])) {
    $post_id = $_POST['post_id'];
    $qp_title = $_POST['qp_title'];

    $sql = "INSERT INTO question_papers (post_id, qp_title) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $post_id, $qp_title);
    $stmt->execute();
    $stmt->close();
}

// ADD QUESTION TO PAPER
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_question'])) {
    $qp_id = $_POST['qp_id'];
    $question_id = $_POST['question_id'];

    // Prevent duplicates
    $check = $conn->prepare("SELECT * FROM question_paper_items WHERE qp_id=? AND question_id=?");
    $check->bind_param("ii", $qp_id, $question_id);
    $check->execute();
    $exists = $check->get_result()->num_rows;
    $check->close();

    if ($exists == 0) {
        $stmt = $conn->prepare("INSERT INTO question_paper_items (qp_id, question_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $qp_id, $question_id);
        $stmt->execute();
        $stmt->close();
    }
}

// DELETE QUESTION FROM PAPER
if (isset($_GET['delete_qpi'])) {
    $delete_id = $_GET['delete_qpi'];
    $conn->query("DELETE FROM question_paper_items WHERE qpi_id = $delete_id");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Tests</title>
  <link rel="stylesheet" href="../AttachtableFile/bootstrap-5.3.7-dist/css/bootstrap.css">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="text-center mb-4">🧠 Manage Job Tests</h2>

  <!-- JOB POSTS LIST -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">Available Job Posts</div>
    <div class="card-body">
      <?php
        $posts = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
        if ($posts->num_rows > 0):
      ?>
      <table class="table table-bordered">
        <thead>
          <tr class="table-secondary">
            <th>#</th>
            <th>Title</th>
            <th>Category</th>
            <th>Deadline</th>
            <th>Create Question Paper</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $no = 1;
            while ($row = $posts->fetch_assoc()):
          ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['categery']) ?></td>
            <td><?= htmlspecialchars($row['deadline']) ?></td>
            <td>
              <form method="POST" class="d-flex">
                <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                <input type="text" name="qp_title" class="form-control form-control-sm me-2" placeholder="Paper title" required>
                <button type="submit" name="create_paper" class="btn btn-success btn-sm">Create</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p>No job posts available.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- QUESTION PAPERS -->
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">All Question Papers</div>
    <div class="card-body">
      <?php
        $papers = $conn->query("
          SELECT qp.*, p.title AS post_title 
          FROM question_papers qp
          JOIN posts p ON qp.post_id = p.post_id
          ORDER BY qp.created_at DESC
        ");
        if ($papers->num_rows > 0):
          while ($paper = $papers->fetch_assoc()):
      ?>
      <div class="mb-4 border rounded p-3 bg-white">
        <h5><?= htmlspecialchars($paper['qp_title']) ?> 
          <small class="text-muted">(<?= htmlspecialchars($paper['post_title']) ?>)</small>
        </h5>

        <!-- Add Question -->
        <form method="POST" class="row g-2 align-items-center mb-3">
          <input type="hidden" name="qp_id" value="<?= $paper['qp_id'] ?>">
          <div class="col-md-6">
            <select name="question_id" class="form-select" required>
              <option value="">Select Question</option>
              <?php
                $questions = $conn->query("SELECT * FROM questions_pool ORDER BY question_id DESC");
                while ($q = $questions->fetch_assoc()):
              ?>
              <option value="<?= $q['question_id'] ?>">
                <?= htmlspecialchars(substr($q['question_text'], 0, 60)) ?>...
              </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-2">
            <button type="submit" name="add_question" class="btn btn-primary btn-sm">Add Question</button>
          </div>
        </form>

        <!-- List of Questions in Paper -->
        <table class="table table-sm table-bordered">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Question</th>
              <th>Correct Option</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $qp_id = $paper['qp_id'];
              $items = $conn->query("
                SELECT qpi.qpi_id, q.question_text, q.correct_option 
                FROM question_paper_items qpi
                JOIN questions_pool q ON qpi.question_id = q.question_id
                WHERE qpi.qp_id = $qp_id
              ");
              $count = 1;
              if ($items->num_rows > 0):
                while ($i = $items->fetch_assoc()):
            ?>
            <tr>
              <td><?= $count++ ?></td>
              <td><?= htmlspecialchars(substr($i['question_text'], 0, 100)) ?>...</td>
              <td><?= $i['correct_option'] ?></td>
              <td>
                <a href="?delete_qpi=<?= $i['qpi_id'] ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('Remove this question?');">Delete</a>
              </td>
            </tr>
            <?php endwhile; else: ?>
              <tr><td colspan="4" class="text-center text-muted">No questions added yet.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <?php endwhile; else: ?>
        <p>No question papers created yet.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
