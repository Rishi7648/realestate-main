<?php
session_start();
include 'db.php'; // Include your database connection file
// A REQUEST-METHOD  in PHP refers to the HTTP method used to send data to or request data from a server
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("User not logged in.");
    }

    // Retrieve form data
    $floors = $_POST['floors'];
    $bedrooms = $_POST['bedrooms'];
    $living_rooms = $_POST['living_rooms'];
    $kitchens = $_POST['kitchens'];
    $washrooms = $_POST['washrooms'];
    $attached_washrooms = $_POST['attached_washrooms'];
    $location = $_POST['location'];
    $area = $_POST['area'];
    $price = $_POST['price'];
    $map_image = $_FILES['map_image']['name'];
    $property_images = $_FILES['property_images'];
    $user_id = $_SESSION['user_id']; // Get the user ID from the session

    // Handle file uploads
    $target_dir = "uploads/";
    $map_image_target = $target_dir . basename($map_image);
    if (!move_uploaded_file($_FILES['map_image']['tmp_name'], $map_image_target)) {
        die("Failed to upload the map image.");
    }

    $property_images_paths = [];
    foreach ($property_images['name'] as $key => $name) {
        $target_file = $target_dir . basename($name);
        if (!move_uploaded_file($property_images['tmp_name'][$key], $target_file)) {
            die("Failed to upload property images.");
        }
        $property_images_paths[] = $target_file;
    }
    $property_images_json = json_encode($property_images_paths);

    // Store the map image path as a plain string
    $map_image_path = $map_image_target;

    try {
        // Insert data into the database using PDO
        $query = "INSERT INTO houseproperties 
            (user_id, floors, bedrooms, living_rooms, area, kitchens, washrooms, attached_washrooms, location, price, map_image, property_images) 
            VALUES 
            (:user_id, :floors, :bedrooms, :living_rooms, :area, :kitchens, :washrooms, :attached_washrooms, :location, :price, :map_image, :property_images)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':floors', $floors, PDO::PARAM_INT);
        $stmt->bindParam(':bedrooms', $bedrooms, PDO::PARAM_INT);
        $stmt->bindParam(':living_rooms', $living_rooms, PDO::PARAM_INT);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->bindParam(':kitchens', $kitchens, PDO::PARAM_INT);
        $stmt->bindParam(':washrooms', $washrooms, PDO::PARAM_INT);
        $stmt->bindParam(':attached_washrooms', $attached_washrooms, PDO::PARAM_INT);
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':map_image', $map_image_path, PDO::PARAM_STR);
        $stmt->bindParam(':property_images', $property_images_json, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "Property listed successfully.";
        } else {
            echo "Failed to list property: " . implode(", ", $stmt->errorInfo());
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
