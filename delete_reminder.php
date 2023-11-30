<?php
session_start();
require("db_conn.php");

if (!isset($_SESSION["login_info"])) {
    header("location: login.php");
}

$user = $_SESSION["login_info"]["User_Name"];

// Fetch subjects for the logged-in user
$subjectQuery = "SELECT DISTINCT subject FROM reminders WHERE user_name = '$user'";
$subjectsResult = $con->query($subjectQuery);

// Fetch reminders for the selected subject and date
$remindersResult = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["subject"]) && isset($_POST["reminder_date"])) {
    $selectedSubject = $_POST["subject"];
    $selectedDate = $_POST["reminder_date"];

    // Output the selected subject and date for debugging
    echo "Selected Subject: $selectedSubject<br>";
    echo "Selected Date: $selectedDate<br>";

    $reminderQuery = "SELECT id, LEFT(description, 30) AS short_description FROM reminders WHERE user_name = '$user' AND subject = '$selectedSubject' AND reminder_date = '$selectedDate'";
    echo "Query: $reminderQuery<br>";
    

    $remindersResult = $con->query($reminderQuery);
    // Output the query result for debugging
    echo "Query Result: " . ($remindersResult ? "Success" : "Failure") . "<br>";
}

$selectedReminderDescription = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reminder_id"])) {
    $selectedReminderId = $_POST["reminder_id"];

    // Fetch the selected reminder's full description
    $descriptionQuery = "SELECT description FROM reminders WHERE user_name = '$user' AND id = $selectedReminderId";
    $descriptionResult = $con->query($descriptionQuery);

    if ($descriptionResult->num_rows > 0) {
        $row = $descriptionResult->fetch_assoc();
        if ($row && isset($row["description"])) {
            $selectedReminderDescription = $row["description"];
        }
    }

    // Delete the selected reminder from the database
    $deleteQuery = "DELETE FROM reminders WHERE user_name = '$user' AND id = $selectedReminderId";

    if ($con->query($deleteQuery) === TRUE) {
        echo "Reminder deleted successfully!";
    } else {
        echo "Error: " . $deleteQuery . "<br>" . $con->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Reminder</title>
    <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="css/style2.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="span9">
                    <h2>Delete Reminder</h2>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="subject">Select Subject:</label>
                            <select class="form-control" name="subject">
                                <?php
                                $subjectsResult->data_seek(0);
                                while ($row = $subjectsResult->fetch_assoc()) {
                                    echo "<option value='" . $row["subject"] . "'>" . $row["subject"] . " </option>";
                                }
                                ?>
                            </select>
                        </div>
                        <?php if ($remindersResult && $remindersResult->num_rows > 0) { ?>
                            <div class="form-group">
                                <label for="reminder_id">Select Reminder:</label>
                                <select class="form-control" name="reminder_id" required>
                                    <?php
                                    $remindersResult->data_seek(0);
                                    while ($reminderRow = $remindersResult->fetch_assoc()) {
                                        echo "<option value='" . $reminderRow["id"] . "'>" . $reminderRow["short_description"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" readonly><?php echo $selectedReminderDescription; ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Delete Reminder</button>
                        <?php } else { ?>
                            <p>No reminders found for the selected subject.</p>
                        <?php } ?>
                    </form>
                    <br>
                    <a href="logout.php"><i class="menu-icon icon-signout"></i>Logout </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Add necessary scripts, such as Bootstrap, etc. -->
</body>
</html>
