<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must log in to delete a property.";
    exit;
}

// Check if the property ID is provided
if (!isset($_GET['id'])) {
    echo "No property ID provided.";
    exit;
}

// Get the property ID from the URL
$property_id = $_GET['id'];

// Fetch the type of property (land or house) from the database
$sql = "SELECT 'land' AS type FROM land_properties WHERE id = :id AND user_id = :user_id 
        UNION 
        SELECT 'house' AS type FROM houseproperties WHERE id = :id AND user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $property_id, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();

$property = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$property) {
    echo "Property not found or access denied.";
    exit;
}

// Determine the table to delete from
$table = $property['type'] === 'land' ? 'land_properties' : 'houseproperties';

// Delete the property from the database
$sql = "DELETE FROM $table WHERE id = :id AND user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $property_id, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

if ($stmt->execute()) {
    echo "Property deleted successfully.";
} else {
    echo "Failed to delete the property.";
}

header("Location: my_property.php");
exit;
?>
