<?php
session_start(); // Start the session to access session variables

// Include your database connection file
include('security.php');
include('includes/header.php');
include('includes/navbar.php');

// Fetch user data from the database
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session after login
$query = "SELECT email FROM users WHERE id = '$user_id'";
$result = mysqli_query($connection, $query);
$user = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = mysqli_real_escape_string($connection, $_POST['email']);
    $new_password = mysqli_real_escape_string($connection, $_POST['password']);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password

    $update_query = "UPDATE users SET email = '$new_email', password = '$hashed_password' WHERE id = '$user_id'";
    if (mysqli_query($connection, $update_query)) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="email"], input[type="password"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 16px;
        }
        button {
            padding: 10px;
            background-color: #007BFF;
            border: none;
            border-radius: 3px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter new password" required>
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
