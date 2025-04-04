<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure PHPMailer is included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['demo-name'];
    $email = $_POST['demo-email'];
    $category = $_POST['demo-category'];
    $message = $_POST['demo-message'];
    $sendCopy = isset($_POST['demo-copy']) ? true : false;  // Check if "Send me a copy" is checked
    $services = isset($_POST['services']) ? $_POST['services'] : [];

    // Map category value to category name
    $categoryNames = [
        "1" => "Undergrad",
        "2" => "Graduate School",
        "3" => "Career",
    ];

    $categoryName = isset($categoryNames[$category]) ? $categoryNames[$category] : 'N/A'; // Get category name from the array

    // Handle file upload
    $file = $_FILES['file-upload'];

    // Create PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'stzuraski@gmail.com';  // Your Gmail address
        $mail->Password = 'qztu qoae qolm gdgw';  // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('stzuraski@gmail.com', 'Steven');
        $mail->addAddress('stzuraski@gmail.com'); // Replace with the recipient's email address

        // Add CC if the checkbox is checked
        if ($sendCopy) {
            $mail->addCC($email);  // Add the user as CC
        }

        // Attach the file if uploaded
        if ($file['error'] === UPLOAD_ERR_OK) {
            $mail->addAttachment($file['tmp_name'], $file['name']);
        }

        $selectedServices = !empty($services) ? implode(", ", $services) : 'None';

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Form Submission:';
        $mail->Body = "
            <h2>Form Submission Details</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Category:</strong> $categoryName</p>
            <p><strong>Services:</strong> $selectedServices</p>
            <p><strong>Message:</strong> $message</p>
        ";

        // Send email
        $mail->send();
        echo 'Message has been sent'; // This response will be sent back to AJAX
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
