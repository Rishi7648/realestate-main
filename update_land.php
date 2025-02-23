<?php
include 'db.php';

// Validate and fetch property details
if (isset($_GET['id'])) {
    $property_id = $_GET['id'];

    // Fetch property details based on the property ID
    $sql = "SELECT * FROM land_properties WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $property_id, PDO::PARAM_INT);
    $stmt->execute();
    $property = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$property) {
        die("Property not found.");
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $id = $_POST['id'];
        $area = htmlspecialchars($_POST['area']);
        $location = htmlspecialchars($_POST['location']);
        $price = htmlspecialchars($_POST['price']);

        // Handle map image upload
        $map_image_path = $_POST['existing_map_image'];
        if (isset($_FILES['map_image']) && $_FILES['map_image']['error'] === UPLOAD_ERR_OK) {
            $map_image = $_FILES['map_image']['name'];
            $map_image_tmp = $_FILES['map_image']['tmp_name'];
            $map_image_path = "uploads/" . basename($map_image);
            move_uploaded_file($map_image_tmp, $map_image_path);
        }

        // Handle property images upload
        $property_images_paths = [];
        if (isset($_FILES['property_images']['name'][0]) && $_FILES['property_images']['name'][0] !== '') {
            foreach ($_FILES['property_images']['tmp_name'] as $index => $tmp_name) {
                if ($_FILES['property_images']['error'][$index] === UPLOAD_ERR_OK) {
                    $file_name = basename($_FILES['property_images']['name'][$index]);
                    $file_path = "uploads/" . $file_name;
                    move_uploaded_file($tmp_name, $file_path);
                    $property_images_paths[] = $file_path;
                }
            }
        }

        $property_images_json = json_encode($property_images_paths);

        // Update property in the database
        $sql = "UPDATE land_properties 
                SET area = :area, location = :location, price = :price, 
                    map_image = :map_image, property_images = :property_images 
                WHERE id = :id";
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':area', $area);
        $stmt->bindValue(':location', $location);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':map_image', $map_image_path);
        $stmt->bindValue(':property_images', $property_images_json);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Property updated successfully!";
            // Redirect to the "View Property" page
            header("Location: index.php");
            exit;
        } else {
            echo "Error: " . implode(", ", $stmt->errorInfo());
        }
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Property</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            font-size: 28px;
            color: #555;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="file"],
        button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="file"] {
            padding: 5px;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Update Property</h1>
    <!-- Update Property Form -->
<form action="update_land.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $property['id']; ?>">
    
    <label for="area">Area:</label>
    <input type="text" id="area" name="area" value="<?php echo htmlspecialchars($property['area']); ?>" required>
    
    <label for="location">Location:</label>
    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($property['location']); ?>" required>
    
    <label for="price">Price:</label>
    <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($property['price']); ?>" required>
    
    <label for="map_image">Upload Map Image:</label>
    <input type="file" id="map_image" name="map_image" accept="image/*">
    <input type="hidden" name="existing_map_image" value="<?php echo htmlspecialchars($property['map_image']); ?>">
    
    <label for="property_images">Upload Property Images:</label>
    <input type="file" id="property_images" name="property_images[]" multiple accept="image/*">
    
    <button type="submit">Update Property</button>
</form>

</body>
</html>
