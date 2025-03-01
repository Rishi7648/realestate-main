<?php
session_start();
include 'db.php'; // Include the database connection file
require 'vendor/autoload.php'; // Include PHPMailer which handles email sending.

// PHPMailer Classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "You must log in as an admin to perform this action.";
    exit;
}

// Get the raw POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate and sanitize user input
if (isset($data['property_id'], $data['property_type'], $data['action'])) {
    $property_id = intval($data['property_id']); // Ensure it's an integer
    $property_type = $data['property_type'];
    $action = intval($data['action']); // Ensure it's an integer (1 for approve, 0 for reject, 2 for delete)

    // Validate property type (should be either 'land' or 'house')
    if ($property_type !== 'land' && $property_type !== 'house') {
        echo "Invalid property type.";
        exit;
    }

    // Validate action (should be either 1, 0, or 2)
    if ($action !== 1 && $action !== 0 && $action !== 2) {
        echo "Invalid action. Use 1 for approve, 0 for reject, and 2 for delete.";
        exit;
    }

    try {
        // Fetch property and user details
        if ($property_type == 'land') {
            $sql = "SELECT lp.*, u.email, u.first_name, u.last_name FROM land_properties lp JOIN users u ON lp.user_id = u.id WHERE lp.id = :property_id";
        } elseif ($property_type == 'house') {
            $sql = "SELECT hp.*, u.email, u.first_name, u.last_name FROM houseproperties hp JOIN users u ON hp.user_id = u.id WHERE hp.id = :property_id";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':property_id', $property_id);
        $stmt->execute();
        $property = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$property) {
            echo "Property not found.";
            exit;
        }

        $email = $property['email'];
        $first_name = $property['first_name'];
        $last_name = $property['last_name'];

        if ($action == 1) {
            // Update the property status to 'approved' if action is approve
            if ($property_type == 'land') {
                $sql = "UPDATE land_properties SET status = 'approved' WHERE id = :property_id";
            } elseif ($property_type == 'house') {
                $sql = "UPDATE houseproperties SET status = 'approved' WHERE id = :property_id";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':property_id', $property_id);

            // Debugging: Check if the query was executed successfully
            if (!$stmt->execute()) {
                print_r($stmt->errorInfo()); // Output errors if any
                echo "Failed to update property status.";
            } else {
                echo "Property status updated to approved.";

                // Send approval email
                $mail = new PHPMailer(true);
                try {
                    //Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                    $mail->SMTPAuth = true;
                    $mail->Username = 'rishisilwal19@gmail.com'; // SMTP username
                    $mail->Password = 'xwxe okcz iggp fxxr'; // SMTP password (use App Password if 2FA is enabled)
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    //Recipients
                    $mail->setFrom('rishisilwal19@gmail.com', 'Real Estate Admin');
                    $mail->addAddress($email, "$first_name $last_name");

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Property Approved';
                    $mail->Body = "Dear $first_name $last_name,<br><br>Your property has been approved.<br><br>Best regards,<br>Real Estate Team";

                    $mail->send();
                    echo 'Approval email has been sent.';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        } elseif ($action == 0) {
            // If rejected, update the property status to 'rejected'
            if ($property_type == 'land') {
                $sql = "UPDATE land_properties SET status = 'rejected' WHERE id = :property_id";
            } elseif ($property_type == 'house') {
                $sql = "UPDATE houseproperties SET status = 'rejected' WHERE id = :property_id";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':property_id', $property_id);

            // Debugging: Check if the query was executed successfully
            if (!$stmt->execute()) {
                print_r($stmt->errorInfo()); // Output errors if any
                echo "Failed to update property status.";
            } else {
                echo "Property status updated to rejected.";

                // Prompt for rejection reason
                $rejection_reason = $data['rejection_reason'] ?? 'No reason provided';

                // Send rejection email
                $mail = new PHPMailer(true);
                try {
                    //Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                    $mail->SMTPAuth = true;
                    $mail->Username = 'rishisilwal19@gmail.com'; // SMTP username
                    $mail->Password = 'xwxe okcz iggp fxxr'; // SMTP password (use App Password if 2FA is enabled)
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    //Recipients
                    $mail->setFrom('rishisilwal19@gmail.com', 'Real Estate Nepal');
                    $mail->addAddress($email, "$first_name $last_name");

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Property Rejected';
                    $mail->Body = "Dear $first_name $last_name,<br><br>Your property has been rejected.<br>Reason: $rejection_reason<br><br>Best regards,<br>Real Estate Team";

                    $mail->send();
                    echo 'Rejection email has been sent.';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        } elseif ($action == 2) {
            // If delete, permanently remove the property from the database
            if ($property_type == 'land') {
                $sql = "DELETE FROM land_properties WHERE id = :property_id";
            } elseif ($property_type == 'house') {
                $sql = "DELETE FROM houseproperties WHERE id = :property_id";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':property_id', $property_id);

            // Debugging: Check if the query was executed successfully
            if (!$stmt->execute()) {
                print_r($stmt->errorInfo()); // Output errors if any
                echo "Failed to delete property.";
            } else {
                echo "Property deleted successfully.";
                // No email is sent for deletion
            }
        }
    } catch (PDOException $e) {
        // Handle any errors during the database operation
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Required data not provided.";
}
?>