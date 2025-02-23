<?php
include 'db.php';

if (isset($_GET['id'])) {
    $property_id = $_GET['id'];

    // Fetch property details based on the property ID
    $sql = "SELECT * FROM land_properties WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $property_id);
    $stmt->execute();
    $property = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$property) {
        echo "Property not found.";
        exit;
    }

    // Display the property details
    echo "<h2>Property Details</h2>";
    echo "<p><strong>Area:</strong> " . $property['area'] . " </p>";
    echo "<p><strong>Location:</strong> " . $property['location'] . "</p>";
    echo "<p><strong>Price:</strong> " . $property['price'] . " NPR</p>";

    // Display map image
    echo "<p><strong>Map:</strong><br><img src='" . $property['map_image'] . "' alt='Property Map' width='600'></p>"; 

    // Display property images (if any)
    $property_images = json_decode($property['property_images'], true);
    if ($property_images) {
        echo "<p><strong>Property Images:</strong><br>";
        foreach ($property_images as $image) {
            echo "<img src='$image' alt='Property Image' width='600' style='margin-right: 10px;'>"; 
        }
        echo "</p>";
    }
} else {
    echo "Property ID is missing.";
}
?>
