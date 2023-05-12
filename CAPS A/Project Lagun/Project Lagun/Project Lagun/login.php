<?php
if (isset($_POST['submit'])) {
    // Get user input
    $emailAddress = $_POST['emailAddress'];
    $password = $_POST['password'];

    // Database connection
    require "scripts/dbconnect.php";

    // Check if email address exists in database
    $stmt = $db->prepare("SELECT * FROM tbl_user WHERE emailAddress = ?");
    $stmt->bind_param("s", $emailAddress);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Email address found, check password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password correct, log user in
            session_start();
            $_SESSION['userID'] = $user['userID'];
            header("Location: index.php");
            exit;
        } else {
            // Password incorrect
            echo "Invalid email or password";
        }
    } else {
        // Email address not found
        echo "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login Form</title>
</head>
<body>
    <h2>User Login Form</h2>
    <form action="" method="post">
        <label for="emailAddress">Email Address:</label><br />
        <input type="email" id="emailAddress" name="emailAddress" required /><br /><br />
        <label for="password">Password:</label><br />
        <input type="password" id="password" name="password" required /><br /><br />
        <input type="submit" name="submit" value="Submit" />
    </form>
</body>
</html>