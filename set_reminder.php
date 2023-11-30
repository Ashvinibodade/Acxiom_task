<?php
session_start();
require("db_conn.php");
require_once("vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION["login_info"])) {
    header("location:login.php");
}

function sendEmail($recipient, $subject, $body) {
    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";

    $mail->Username = "ashvinibodade234@gmail.com";
    $mail->Password = "kqnh gdyw pfrr ylzd";

    $mail->setFrom("ashvinibodade234@gmail.com");
    $mail->addReplyTo("no-reply@your_domain.com");

    $mail->addAddress($recipient);
    $mail->isHTML();
    $mail->Subject = $subject;
    $mail->Body = $body;

    if ($mail->send()) {
        echo "Email sent successfully!";
    } else {
        echo "Error sending email: " . $mail->ErrorInfo;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST["reminder_date"];
    $subject = $_POST["subject"];
    $description = $_POST["description"];
    $email = $_POST["email"];
    $contactNo = $_POST["contact_no"];
    $smsNo = $_POST["sms_no"];

    // Perform validation if needed

    // Insert reminder into the database
    $user = $_SESSION["login_info"]["User_Name"];
    $sql = "INSERT INTO reminders (reminder_date, subject, description, email, contact_no, sms_no, user_name) 
            VALUES ('$date', '$subject', '$description', '$email', '$contactNo', '$smsNo', '$user')";

    if ($con->query($sql) === TRUE) {
        echo "Reminder set successfully!";

        // Check if today is the specified reminder date
        if (date("Y-m-d") == $date) {
            // Send email only if it's the specified date
            sendEmail($email, $subject, $description);

            // Check for recurrence checkboxes
            $recurDays = [];
            if (!empty($_POST["recur_7_days"])) $recurDays[] = 7;
            if (!empty($_POST["recur_5_days"])) $recurDays[] = 5;
            if (!empty($_POST["recur_3_days"])) $recurDays[] = 3;
            if (!empty($_POST["recur_2_days"])) $recurDays[] = 2;

            // Calculate and insert next recurrence dates
            foreach ($recurDays as $days) {
                $nextDate = date('Y-m-d', strtotime($date . " + $days days"));
                $sql = "INSERT INTO reminders (reminder_date, subject, description, email, contact_no, sms_no, user_name) 
                        VALUES ('$nextDate', '$subject', '$description', '$email', '$contactNo', '$smsNo', '$user')";
                $con->query($sql);
            }
        } else {
            echo "Reminder email will be sent on the specified date: $date";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Reminder</title>
    <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="bootstrap/css/bootstrap-datepicker.css" rel="stylesheet">
    <link type="text/css" href="css/style1.css" rel="stylesheet";

    <!-- Add jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="span9">
                <h2>Set Reminder</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="reminder_date">Select a Date:</label>
                        <input type="date" class="form-control" name="reminder_date" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject:</label>
                        <!-- You can populate the dropdown options dynamically from your database -->
                        <select class="form-control" name="subject" required>
                            <option value="TOC">TOC</option>
                            <option value="DBMS">DBMS</option>
                            <option value="IWT">IWT</option>
                            <option value="COMPUTER NETWORKS">COMPUTER NETWORKS</option>
                            <option value="PYTHON">PYTHON</option>
                            <option value="PHP">PHP</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Add Description:</label>
                        <textarea class="form-control" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="text" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_no">Contact No:</label>
                        <input type="text" class="form-control" name="contact_no">
                    </div>
                    <div class="form-group">
                        <label for="sms_no">SMS No:</label>
                        <input type="text" class="form-control" name="sms_no">
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
                    <button type="button" class="btn btn-primary"><a href="home.php">Back</a></button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </form>
                <br>
                <a href="logout.php"><i class="menu-icon icon-signout"></i>Logout </a>
            </div>
        </div>
    </div>
</div>

<!-- Add necessary scripts, such as Bootstrap, Bootstrap datepicker, etc. -->

</body>
</html>
