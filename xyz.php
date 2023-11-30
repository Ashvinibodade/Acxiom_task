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

// Fetch reminders for the first subject (you may adjust this based on your needs)
$remindersResult = false;

$selectedSubject = $selectedReminderId = '';
$selectedReminderDescription = $emailAddress = $contactNo = $smsNo = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedSubject = $_POST["subject"];

    // Fetch reminders for the selected subject
    $reminderQuery = "SELECT id, description, LEFT(description, 30) AS short_description, email, contact_no, sms_no FROM reminders WHERE user_name = '$user' AND subject = '$selectedSubject'";
    $remindersResult = $con->query($reminderQuery);

    if (isset($_POST["reminder_id"])) {
        $selectedReminderId = $_POST["reminder_id"];

        // Fetch details for the selected reminder
        $reminderDetailsQuery = "SELECT description, email, contact_no, sms_no FROM reminders WHERE user_name = '$user' AND id = $selectedReminderId";
        $reminderDetailsResult = $con->query($reminderDetailsQuery);

        if ($reminderDetailsResult->num_rows > 0) {
            $row = $reminderDetailsResult->fetch_assoc();
            $selectedReminderDescription = $row["description"];
            $emailAddress = $row["email"];
            $contactNo = $row["contact_no"];
            $smsNo = $row["sms_no"];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Reminder</title>
    <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="css/style3.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="span9">
                    <h2>Modify Reminder</h2>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="reminder_date">Select Date:</label>
                            <input type="date" class="form-control" name="reminder_date" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Select Subject:</label>
                            <select class="form-control" name="subject" onchange="this.form.submit()" required>
                                <?php
                                $subjectsResult->data_seek(0);
                                while ($row = $subjectsResult->fetch_assoc()) {
                                    $selected = ($row["subject"] == $selectedSubject) ? 'selected' : '';
                                    echo "<option value='" . $row["subject"] . "' $selected>" . $row["subject"] . " </option>";
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
                                        $selected = ($reminderRow["id"] == $selectedReminderId) ? 'selected' : '';
                                        echo "<option value='" . $reminderRow["id"] . "' $selected>" . $reminderRow["short_description"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Description:</label>
                                <input type="text" class="form-control" name="description" value="<?php echo $selectedReminderDescription; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="email_address">Email Address:</label>
                                <input type="text" class="form-control" name="email_address" value="<?php echo $emailAddress; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="contact_no">Contact No:</label>
                                <input type="text" class="form-control" name="contact_no" value="<?php echo $contactNo; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="sms_no">SMS No:</label>
                                <input type="text" class="form-control" name="sms_no" value="<?php echo $smsNo; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Recur for next:</label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="recur_7_days" value="1">
                                    <label class="form-check-label">7 Days</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="recur_5_days" value="1">
                                    <label class="form-check-label">5 Days</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="recur_3_days" value="1">
                                    <label class="form-check-label">3 Days</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="recur_2_days" value="1">
                                    <label class="form-check-label">2 Days</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Modify Reminder</button>
                        <?php } else { ?>
                            <p>No reminders found for the selected subject and date.</p>
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
