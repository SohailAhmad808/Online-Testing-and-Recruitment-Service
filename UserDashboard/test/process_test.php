<?php 
    require_once '../connect/connect.php';
    date_default_timezone_set('Asia/Karachi');

    // Creating score
    if(!isset($_SESSION['score'])){
        $_SESSION['score'] = 0;
    }
    $qp_id = $_SESSION['qp_id'];
    //
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //store the index of the question in the question pool
        $question_id = $_POST['question_id'];

        // Storing the option that user that the user is selected
        $selected_option = isset($_POST['option']) ? $_POST['option'] : null;
        //
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
        
        //
        
        //
        $_SESSION['q_index']++;
        
        //
        $question_select_sql= "SELECT * FROM questions_pool WHERE question_id = ?";
        $question_select_stmt = $conn->prepare($question_select_sql);
        $question_select_stmt -> bind_param(
            "i",
            $question_id
        );
        $question_select_stmt->execute();
        $Result_question = $question_select_stmt->get_result();
        $question_data = $Result_question->fetch_assoc();

        $correct_option = $question_data['correct_option'];

       

       
        // Comparin if the user select option or not
        if($selected_option == $correct_option){
            $_SESSION['score']++;
            
        }

        if($_SESSION['q_index'] == $total_questions){

        // Get stored data
               
        $qp_id = $_SESSION['qp_id'];           // quiz id
        $score = $_SESSION['score'];           // obtained marks
        $total_marks = $total_questions;       // each question = 1 mark

        $title_sql = "SELECT qp_title FROM question_papers WHERE qp_id = ?";
        $title_stmt = $conn->prepare($title_sql);
        $title_stmt->bind_param("i", $qp_id);
        $title_stmt->execute();
        $title_result = $title_stmt->get_result()->fetch_assoc();
        $qp_title = $title_result['qp_title']; // ✅ now valid


        // Calculate percentage
        $percentage = ($score / $total_marks) * 100;

        // Decide status
        $status = ($percentage >= 50) ? "Pass" : "Fail";

        // Insert result into table
        $save_sql = "INSERT INTO quiz_results (id, qp_id, obtained_marks, qp_title, total_marks, mpercentage, mstatus)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
        $save_stmt = $conn->prepare($save_sql);
        $save_stmt->bind_param(
            "iiisids",
            $user_id,
            $qp_id,
            $score,
            $qp_title,
            $total_marks,
            $percentage,
            $status
        );
        $save_stmt->execute();

        // Reset session or destroy
        unset($_SESSION['score']);
        unset($_SESSION['qp_id']);
        unset($_SESSION['q_index']);
        unset($_SESSION['test_start_time']);
        unset($_SESSION[$timer_key]);

        // Redirect to result view page
        header("Location: ../result/UserResult.php");
        exit();
    }

        else{
            header("Location: AttemptTest.php");
            exit();
        }

    }
?>