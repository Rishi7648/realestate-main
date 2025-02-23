<?php
include 'db.php';

// Fetch property details for editing
if (isset($_GET['id'])) {
    $property_id = $_GET['id'];

    // Retrieve property details from the database
    $sql = "SELECT * FROM houseproperties WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $property_id, PDO::PARAM_INT);
    $stmt->execute();
    $property = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$property) {
        die("Property not found.");
    }
}

// Update property details upon form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $id = $_POST['id'];
        $location = htmlspecialchars($_POST['location']);
        $price = htmlspecialchars($_POST['price']);
        $floors = htmlspecialchars($_POST['floors']);
        $bedrooms = htmlspecialchars($_POST['bedrooms']);
        $area = htmlspecialchars($_POST['area']);
        $living_rooms = htmlspecialchars($_POST['living_rooms']);
        $kitchens = htmlspecialchars($_POST['kitchens']);
        $washrooms = htmlspecialchars($_POST['washrooms']);
        $attached_washrooms = htmlspecialchars($_POST['attached_washrooms']);

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

        // Update the database
        $sql = "UPDATE houseproperties 
                SET location = :location, price = :price, floors = :floors, bedrooms = :bedrooms, area = :area,
                    living_rooms = :living_rooms, kitchens = :kitchens, washrooms = :washrooms, 
                    attached_washrooms = :attached_washrooms, map_image = :map_image, property_images = :property_images 
                WHERE id = :id";
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':location', $location);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':floors', $floors);
        $stmt->bindValue(':bedrooms', $bedrooms);
        $stmt->bindValue(':area', $area);
        $stmt->bindValue(':living_rooms', $living_rooms);
        $stmt->bindValue(':kitchens', $kitchens);
        $stmt->bindValue(':washrooms', $washrooms);
        $stmt->bindValue(':attached_washrooms', $attached_washrooms);
        $stmt->bindValue(':map_image', $map_image_path);
        $stmt->bindValue(':property_images', $property_images_json);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Property updated successfully!";
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
    <title>Update House Property</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
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
        }

        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Update House Property</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $property['id']; ?>">

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($property['location']); ?>" required>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($property['price']); ?>" required>

        <label for="area">area:</label>
        <input type="text" id="area" name="area" value="<?php echo htmlspecialchars($property['area']); ?>" required>

        <label for="floors">Total Floors:</label>
        <input type="text" id="floors" name="floors" value="<?php echo htmlspecialchars($property['floors']); ?>" required>

        <label for="bedrooms">Bedrooms:</label>
        <input type="text" id="bedrooms" name="bedrooms" value="<?php echo htmlspecialchars($property['bedrooms']); ?>" required>

        <label for="living_rooms">Living Rooms:</label>
        <input type="text" id="living_rooms" name="living_rooms" value="<?php echo htmlspecialchars($property['living_rooms']); ?>" required>

        <label for="kitchens">Kitchens:</label>
        <input type="text" id="kitchens" name="kitchens" value="<?php echo htmlspecialchars($property['kitchens']); ?>" required>

        <label for="washrooms">Washrooms:</label>
        <input type="text" id="washrooms" name="washrooms" value="<?php echo htmlspecialchars($property['washrooms']); ?>" required>

        <label for="attached_washrooms">Attached Washrooms:</label>
        <input type="text" id="attached_washrooms" name="attached_washrooms" value="<?php echo htmlspecialchars($property['attached_washrooms']); ?>" required>

        <label for="map_image">Upload Map Image:</label>
        <input type="file" id="map_image" name="map_image" accept="image/*">
        <input type="hidden" name="existing_map_image" value="<?php echo htmlspecialchars($property['map_image']); ?>">

        <label for="property_images">Upload Property Images:</label>
        <input type="file" id="property_images" name="property_images[]" multiple accept="image/*">

        <button type="submit">Update Property</button>
    </form>
</body>
</html>
