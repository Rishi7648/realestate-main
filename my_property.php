<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must log in to view your properties.";
    exit;
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch user details (such as name)
$sql = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch land properties for the logged-in user
$sql = "SELECT * FROM land_properties WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$land_properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch house properties for the logged-in user
$sql = "SELECT * FROM houseproperties WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$house_properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Properties</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #343a40;
        }
        .property-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .property-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .property-details p {
            margin: 5px 0;
            color: #495057;
        }
        .property-actions {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }
        .action-btn {
            padding: 10px 15px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border-radius: 5px;
            text-align: center;
            flex: 1;
            transition: background-color 0.3s ease;
        }
        .action-btn:hover {
            background-color: #0056b3;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        img {
            width: 100%;
            max-width: 300px;
            margin-top: 10px;
            border-radius: 5px;
        }
        .profile {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }
        .profile h3 {
            color: #007bff;
        }
    </style>
</head>
<body>
    <!-- Profile Section -->
    <div class="profile">
        <h3>Welcome, <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>!</h3>
        
    </div>

    <h1>My Properties</h1>
    
    <!-- Land Properties Section -->
    <h2>Land Properties</h2>
    <div class="property-container">
        <?php if (!empty($land_properties)): ?>
            <?php foreach ($land_properties as $property): ?>
                <div class="property-card">
                    <div class="property-details">
                        <p><strong>ID:</strong> <?php echo htmlspecialchars($property['id']); ?></p>
                        <p><strong>Area:</strong> <?php echo htmlspecialchars($property['area']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($property['location']); ?></p>
                        <p><strong>Price:</strong> <?php echo htmlspecialchars($property['price']); ?> NPR</p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($property['status']); ?></p>
                    </div>
                    <div class="property-actions">
                        <a href="view_landproperty.php?id=<?php echo htmlspecialchars($property['id']); ?>" class="action-btn">View</a>
                        <a href="update_land.php?id=<?php echo htmlspecialchars($property['id']); ?>" class="action-btn">Update</a>
                        <a href="delete_property.php?id=<?php echo htmlspecialchars($property['id']); ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this property?');">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No land properties found.</p>
        <?php endif; ?>
    </div>

    <!-- House Properties Section -->
    <h2>House Properties</h2>
    <div class="property-container">
        <?php if (!empty($house_properties)): ?>
            <?php foreach ($house_properties as $property): ?>
                <div class="property-card">
                    <div class="property-details">
                        <p><strong>ID:</strong> <?php echo htmlspecialchars($property['id']); ?></p>
                        <p><strong>Floors:</strong> <?php echo htmlspecialchars($property['floors']); ?></p>
                        <p><strong>Bedrooms:</strong> <?php echo htmlspecialchars($property['bedrooms']); ?></p>
                        <p><strong>Area:</strong> <?php echo htmlspecialchars($property['area']); ?></p>
                        <p><strong>Living Rooms:</strong> <?php echo htmlspecialchars($property['living_rooms']); ?></p>
                        <p><strong>Kitchens:</strong> <?php echo htmlspecialchars($property['kitchens']); ?></p>
                        <p><strong>Washrooms:</strong> <?php echo htmlspecialchars($property['washrooms']); ?></p>
                        <p><strong>Attached Washrooms:</strong> <?php echo htmlspecialchars($property['attached_washrooms']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($property['location']); ?></p>
                        <p><strong>Price:</strong> <?php echo htmlspecialchars($property['price']); ?> NPR</p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($property['status']); ?></p>
                    </div>
                    <div class="property-actions">
                        <a href="view_house.php?id=<?php echo htmlspecialchars($property['id']); ?>" class="action-btn">View</a>
                        <a href="update_house.php?id=<?php echo htmlspecialchars($property['id']); ?>" class="action-btn">Update</a>
                        <a href="delete_property.php?id=<?php echo htmlspecialchars($property['id']); ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this property?');">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No house properties found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
