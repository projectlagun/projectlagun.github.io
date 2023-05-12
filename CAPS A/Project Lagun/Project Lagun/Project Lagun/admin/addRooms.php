
<?php
if(isset($_POST['submit'])) {
    // Validate and sanitize user input
    $roomDescription = filter_input(INPUT_POST, 'roomDescription', FILTER_SANITIZE_STRING);
    $roomCategory = filter_input(INPUT_POST, 'roomCategory', FILTER_SANITIZE_STRING);
    $roomPrice = filter_input(INPUT_POST, 'roomPrice', FILTER_VALIDATE_FLOAT);

    // Check if user input is valid
    if ($roomDescription === false || $roomCategory === false || $roomPrice === false) {
        echo "Invalid input";
    } else {
        // Generate unique roomID value
        $randomNumber = sprintf("%05d", rand(0, 99999));
        $roomID = "room_" . $randomNumber;

        // Database connection
        require "scripts/dbconnect.php";

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO tbl_room (roomID, roomDescription, roomCategory, roomPrice) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $roomID, $roomDescription, $roomCategory, $roomPrice);

        // Execute
        if ($stmt->execute() === TRUE) {
            echo "New record created successfully";
            echo "Room ID: " . $roomID;
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>

<form method="post" onsubmit="return validateForm()">
    <label for="roomCategory">Room Category:</label>
    <input type="text" name="roomCategory" id="roomCategory" required /><br />
    <label for="roomDescription">Room Description:</label><br />
    <textarea name="roomDescription" id="roomDescription" rows="4" cols="50" required></textarea><br />
    <label for="roomPrice">Room Price:</label>
    <input type="number" name="roomPrice" id="roomPrice" min="0" required /><br />
    <input type="submit" name="submit" value="Submit" />
</form>

<script>
function validateForm() {
    var roomCategory = document.getElementById("roomCategory").value;
    var roomDescription = document.getElementById("roomDescription").value;
    var roomPrice = document.getElementById("roomPrice").value;

    if (roomCategory == "") {
        alert("Room Category must be filled out");
        return false;
    }

    if (roomDescription == "") {
        alert("Room Description must be filled out");
        return false;
    }

    if (isNaN(roomPrice) || roomPrice == "") {
        alert("Room Price must be a valid number");
        return false;
    }

    return true;
}
</script>

<style>
    body {
        font-family: Arial, sans-serif;
    }

    form {
        background-color: #f4f4f4;
        padding: 20px;
        border-radius: 5px;
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    input[type="text"],
    input[type="number"],
    textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        margin-bottom: 16px;
    }

    input[type="submit"] {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }
</style>