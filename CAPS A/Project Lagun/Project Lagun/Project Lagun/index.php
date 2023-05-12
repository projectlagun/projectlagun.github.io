

<?php
if(isset($_POST['submit'])) {
    // Sanitize user input to prevent HTML injection
    $firstName = filter_var($_POST['firstName'], FILTER_SANITIZE_STRING);
    $lastName = filter_var($_POST['lastName'], FILTER_SANITIZE_STRING);
    $emailAddress = filter_var($_POST['emailAddress'], FILTER_SANITIZE_EMAIL);
    $contactNumber = filter_var($_POST['contactNumber'], FILTER_SANITIZE_STRING);
    $userType = filter_var($_POST['userType'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $userID = 'user_' . rand(10000, 99999);

    // Validate email address
    if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    // Validate phone number (Philippines)
    if (!preg_match('/^(09|\+639)\d{9}$/', $contactNumber)) {
        echo "Invalid phone number format";
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    require "scripts/dbconnect.php";

    // Insert data into database
    $stmt = $db->prepare("INSERT INTO tbl_user (userID, userType, lastName, firstName, emailAddress, contactNumber, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $userID, $userType, $lastName, $firstName, $emailAddress, $contactNumber, $hashed_password);
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration Form</title>
    <script>
        function validateForm() {
            // Get form data
            var firstName = document.forms["registrationForm"]["firstName"].value;
            var lastName = document.forms["registrationForm"]["lastName"].value;
            var emailAddress = document.forms["registrationForm"]["emailAddress"].value;
            var contactNumber = document.forms["registrationForm"]["contactNumber"].value;
            var password = document.forms["registrationForm"]["password"].value;

            // Validate first name
            if (firstName == "") {
                document.getElementById("firstNameError").innerHTML = "First name is required";
                return false;
            } else {
                document.getElementById("firstNameError").innerHTML = "";
            }

            // Validate last name
            if (lastName == "") {
                document.getElementById("lastNameError").innerHTML = "Last name is required";
                return false;
            } else {
                document.getElementById("lastNameError").innerHTML = "";
            }

            // Validate email address
            if (emailAddress == "") {
                document.getElementById("emailAddressError").innerHTML = "Email address is required";
                return false;
            } else if (!validateEmail(emailAddress)) {
                document.getElementById("emailAddressError").innerHTML = "Invalid email format";
                return false;
            } else {
                document.getElementById("emailAddressError").innerHTML = "";
            }

            // Validate contact number
            if (contactNumber == "") {
                document.getElementById("contactNumberError").innerHTML = "Contact number is required";
                return false;
            } else if (!validatePhoneNumber(contactNumber)) {
                document.getElementById("contactNumberError").innerHTML = "Invalid phone number format";
                return false;
            } else {
                document.getElementById("contactNumberError").innerHTML = "";
            }

            // Validate password
            if (password == "") {
                document.getElementById("passwordError").innerHTML = "Password is required";
                return false;
            } else {
                document.getElementById("passwordError").innerHTML = "";
            }

            return true;
        }

        function validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }

        function validatePhoneNumber(phoneNumber) {
            var re = /^(09|\+639)\d{9}$/;
            return re.test(phoneNumber);
        }
    </script>
</head>
<body>
    <h2>User Registration Form</h2>
    <form name="registrationForm" action="" method="post" onsubmit="return validateForm()">
        <label for="firstName">First Name:</label><br>
        <input type="text" id="firstName" name="firstName" onkeyup="validateForm()" required><br>
        <span id="firstNameError" style="color: red;"></span><br><br>
        <label for="lastName">Last Name:</label><br>
        <input type="text" id="lastName" name="lastName" onkeyup="validateForm()" required><br>
        <span id="lastNameError" style="color: red;"></span><br><br>
        <label for="emailAddress">Email Address:</label><br>
        <input type="email" id="emailAddress" name="emailAddress" onkeyup="validateForm()" required><br>
        <span id="emailAddressError" style="color: red;"></span><br><br>
        <label for="contactNumber">Contact Number:</label><br>
        <input type="tel" id="contactNumber" name="contactNumber" onkeyup="validateForm()" required><br>
        <span id="contactNumberError" style="color: red;"></span><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" onkeyup="validateForm()" required><br>
        <span id="passwordError" style="color: red;"></span><br><br>
        <label for="userType">User Type:</label><br>
        <select id="userType" name="userType">
            <option value="Admin">Admin</option>
            <option value="Desk">Desk</option>
            <option value="Owner">Owner</option>
        </select><br><br>
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>
