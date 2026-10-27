<?php
    require_once '../connect.php';
    $search_value = isset($_POST["search"]) ? $conn->real_escape_string($_POST["search"]) : "";
    $output = "";
    $sql = "SELECT * FROM candidate WHERE cnic LIKE ? OR fname LIKE ? OR email LIKE ? OR phonnum LIKE ?";
    $stmt = $conn->prepare($sql);
    $like = "%$search_value%";
    $stmt->bind_param("ssss", $like, $like, $like, $like);
    $stmt->execute();
    $result_data = $stmt->get_result();

    $number_of_rows=$result_data->num_rows;
    if($number_of_rows > 0){
        while ($row = $result_data->fetch_assoc()) {
            $profile_image = !empty($row['profile_image']) ? '../../UserDashboard/uploads/' . $row['profile_image'] : '';
           $output.= '<tr>
                      <th>
                        <img src="' . $profile_image . '" class="object-fit-cover rounded-circle" style="width: 90px; height: 90px;">
                      </th>
                      <td>' . htmlspecialchars($row['fname']) . '</td>
                      <td>' . htmlspecialchars($row['cnic']) . '</td>
                      <td>' . htmlspecialchars($row['email']) . '</td>
                      <td>' . htmlspecialchars($row['id']) . '</td>
                      <td><a href="?view_profile=' . $row['id'] . '" class="btn btn-primary">View Profile</a></td>
                      <td>
                        <form action="UserManagement.php" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this record?\');">
                                <input type="hidden" name="delete_id" value="' . $row['id'] . '">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                      </td>
                    </tr>';
        }
    }else {
    $output = '<tr><td colspan="7" class="text-center">No records found</td></tr>';
}
    // The output is given by 
    echo $output;


?>