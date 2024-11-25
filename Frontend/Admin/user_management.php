<?php
session_start();
include('../../Backend/db/db_connect.php');

if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../../Frontend/login.php");
    exit();
}

$user_id = $_SESSION['id'];
$query = "SELECT first_name, last_name, profile_picture, user_type FROM tbl_users WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $first_name, $last_name, $profile_picture, $user_type);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Default profile picture
$default_profile_picture = '../../Assets/default-profile.png';
$profile_image = !empty($profile_picture) && file_exists('../../uploads/profile_pictures/' . $profile_picture) ? '../../uploads/profile_pictures/' . $profile_picture : $default_profile_picture;

// Query to get all users for the User Management table
$user_query = "SELECT first_name, last_name, profile_picture, email, phone, user_type FROM tbl_users";
$user_result = mysqli_query($con, $user_query);

if (!$user_result) {
    die("Error executing query: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX - Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body>

    <?php
    $page = 'user_management.php';
    include('../../sidebar/sidebar-admin.php');
    ?>

    <!-- User Management Section -->
    <div class="dashboard-section">
        <h2>User Management</h2>
        <div class="table-container">
            <table class="user-management-table">
                <thead>
                    <tr>
                        <th>Profile Picture</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>User Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($user_result)) {
                        $user_profile_picture = !empty($user['profile_picture']) && file_exists('../../uploads/profile_pictures/' . $user['profile_picture'])
                            ? '../../uploads/profile_pictures/' . $user['profile_picture']
                            : $default_profile_picture;
                    ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($user_profile_picture); ?>" alt="Profile Picture" class="profile-img"></td>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone']); ?></td>
                            <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                            <td>
                                <button class="action-btn view-btn">View</button>
                                <!-- <button class="action-btn edit-btn">Edit</button>
                                            <button class="action-btn delete-btn">Delete</button> -->
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../js/logout-confirmation.js"></script>

</html>