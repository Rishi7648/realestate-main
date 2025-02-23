<?php
session_start();
include 'db.php'; // Assuming db.php is your database connection file

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "You must log in as an admin to view this page.";
    exit;
}

// Fetch search keyword if provided
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch all land properties with user details, filtered by search keyword and excluding rejected properties
$sql_land = "SELECT lp.*, u.first_name, u.last_name, u.phone, u.email, lp.map_image, lp.status 
             FROM land_properties lp
             JOIN users u ON lp.user_id = u.id
             WHERE lp.location LIKE :keyword AND lp.status != 'rejected'
             ORDER BY lp.created_at DESC"; // Sorting by creation date (latest first)
$stmt_land = $conn->prepare($sql_land);
$stmt_land->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
$stmt_land->execute();
$land_properties = $stmt_land->fetchAll(PDO::FETCH_ASSOC);

// Fetch all house properties with user details, filtered by search keyword and excluding rejected properties
$sql_house = "SELECT hp.*, u.first_name, u.last_name, u.phone, u.email, hp.map_image, hp.status
              FROM houseproperties hp
              JOIN users u ON hp.user_id = u.id
              WHERE hp.location LIKE :keyword AND hp.status != 'rejected'
              ORDER BY hp.created_at DESC"; // Sorting by creation date (latest first)
$stmt_house = $conn->prepare($sql_house);
$stmt_house->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
$stmt_house->execute();
$house_properties = $stmt_house->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            font-size: 2em;
        }
        .tab-btns {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .tab-btn {
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .tab-btn.active {
            background-color: #0056b3;
        }
        .property-list {
            display: none;
        }
        .property-list.active {
            display: block;
        }
        .property {
            border-bottom: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 10px;
        }
        .property h3 {
            font-size: 1.5em;
        }
        .property img {
            max-width: 600px;
            height: auto;
            border-radius: 5px;
        }
        .btn {
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
        }
        .approve {
            background-color: #28a745;
            color: white;
        }
        .reject {
            background-color: #dc3545;
            color: white;
        }
        .search-bar input {
            padding: 10px;
            width: 80%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .search-bar button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .tab-btns {
                flex-direction: column;
            }
        }
        .fixed-buttons {
            position: fixed;
            top: 10px;
            right: 20px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 5px;
        }
        .view-property-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .hidden {
            display: none;
        }
        
    </style>
</head>
<body>
    
    <button class="logout-btn" onclick="window.location.href='admin_logout.php'">Logout</button>

    <div class="container">
        <h1>Admin Panel</h1>

        <!-- View Property Button -->
        <button class="view-property-btn" id="viewPropertyBtn" onclick="showPropertyOptions()">View Property</button>

        <!-- Search Bar -->
        <div class="search-bar hidden" id="searchBar" style="margin-bottom: 20px;">
            <form method="GET" action="">
                <input type="text" name="search" value="<?= htmlspecialchars($search_keyword) ?>" placeholder="Search by location">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Tab buttons for toggling between house and land properties -->
        <div class="tab-btns hidden" id="tabBtns">
            <button class="tab-btn active" id="viewLandBtn" onclick="toggleTab('land')">View Land</button>
            <button class="tab-btn" id="viewHouseBtn" onclick="toggleTab('house')">View House</button>
        </div>

        <!-- Land Properties -->
        <div class="property-list land hidden">
            <h2>Land Properties</h2>
            <?php if (!empty($land_properties)): ?>
                <?php foreach ($land_properties as $property): ?>
                    <div class="property">
                        <p><strong>ID:</strong> <?php echo htmlspecialchars($property['id']); ?></p>
                        <h3>Location: <?= htmlspecialchars($property['location']) ?></h3>
                        <p>Area: <?= htmlspecialchars($property['area']) ?></p>
                        <p>Price: NPR <?= htmlspecialchars($property['price']) ?></p>
                        <p>Status: <?= htmlspecialchars($property['status']) ?></p>
                        <p>Uploaded By: <?= htmlspecialchars($property['first_name']) ?> <?= htmlspecialchars($property['last_name']) ?></p>
                        <p>Email: <?= htmlspecialchars($property['email']) ?></p>
                        <p>Phone Number: <?= htmlspecialchars($property['phone']) ?></p>
                        <h4>Map:</h4>
                        <img src="<?= htmlspecialchars($property['map_image']) ?>" alt="Property Map">
                        <h4>Images:</h4>
                        <?php
                        $images = json_decode($property['property_images'], true);
                        if ($images && is_array($images)) {
                            foreach ($images as $image) {
                                echo "<img src='$image' alt='Property Image'>";
                            }
                        }
                        ?>
                        <button class="btn approve" onclick="handleAction(<?= $property['id'] ?>, 'land', 1)">Approve</button>
                        <button class="btn reject" onclick="handleAction(<?= $property['id'] ?>, 'land', 0)">Reject</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No land properties available.</p>
            <?php endif; ?>
        </div>

        <!-- House Properties -->
        <div class="property-list house hidden">
            <h2>House Properties</h2>
            <?php if (!empty($house_properties)): ?>
                <?php foreach ($house_properties as $property): ?>
                    <div class="property">
                        <p><strong>ID:</strong> <?php echo htmlspecialchars($property['id']); ?></p>
                        <h3>Location: <?= htmlspecialchars($property['location']) ?></h3>
                        <p>Floors: <?= htmlspecialchars($property['floors']) ?></p>
                        <p>Bedrooms: <?= htmlspecialchars($property['bedrooms']) ?></p>
                        <p>Area: <?= htmlspecialchars($property['area']) ?></p>
                        <p>Living Rooms: <?= htmlspecialchars($property['living_rooms']) ?></p>
                        <p>Kitchens: <?= htmlspecialchars($property['kitchens']) ?></p>
                        <p>Washrooms: <?= htmlspecialchars($property['washrooms']) ?></p>
                        <p>Attached washrooms: <?= htmlspecialchars($property['attached_washrooms']) ?></p>
                        <p>Price: NPR <?= htmlspecialchars($property['price']) ?></p>
                        <p>Status: <?= htmlspecialchars($property['status']) ?></p>
                        <p>Uploaded By: <?= htmlspecialchars($property['first_name']) ?> <?= htmlspecialchars($property['last_name']) ?></p>
                        <p>Email: <?= htmlspecialchars($property['email']) ?></p>
                        <p>Phone Number: <?= htmlspecialchars($property['phone']) ?></p>
                        <h4>Map:</h4>
                        <img src="<?= htmlspecialchars($property['map_image']) ?>" alt="Property Map">
                        <h4>Images:</h4>
                        <?php
                        $images = json_decode($property['property_images'], true);
                        if ($images && is_array($images)) {
                            foreach ($images as $image) {
                                echo "<img src='$image' alt='Property Image'>";
                            }
                        }
                        ?>
                        <button class="btn approve" onclick="handleAction(<?= $property['id'] ?>, 'house', 1)">Approve</button>
                        <button class="btn reject" onclick="handleAction(<?= $property['id'] ?>, 'house', 0)">Reject</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No house properties available.</p>
            <?php endif; ?>
        </div>
    </div>

    
    <script>
    // Function to show property options (search bar, view land, view house buttons)
    function showPropertyOptions() {
        document.getElementById('viewPropertyBtn').classList.add('hidden');
        document.getElementById('searchBar').classList.remove('hidden');
        document.getElementById('tabBtns').classList.remove('hidden');
        document.querySelector('.property-list.land').classList.remove('hidden'); // Show land properties by default
    }

    // Toggle tab for land and house properties
    function toggleTab(tab) {
        const landTab = document.querySelector('.property-list.land');
        const houseTab = document.querySelector('.property-list.house');
        const landBtn = document.getElementById('viewLandBtn');
        const houseBtn = document.getElementById('viewHouseBtn');

        if (tab === 'land') {
            landTab.classList.add('active'); // Show land properties
            houseTab.classList.remove('active'); // Hide house properties
            landBtn.classList.add('active'); // Activate land button
            houseBtn.classList.remove('active'); // Deactivate house button
        } else if (tab === 'house') {
            landTab.classList.remove('active'); // Hide land properties
            houseTab.classList.add('active'); // Show house properties
            landBtn.classList.remove('active'); // Deactivate land button
            houseBtn.classList.add('active'); // Activate house button
        }
    }
    function handleAction(propertyId, propertyType, action) {
    let rejectionReason = '';
    if (action === 0) {
        rejectionReason = prompt('Please provide a reason for rejection:');
        if (!rejectionReason) {
            alert('Rejection reason is required.');
            return;
        }
    }

    // Prepare the data to send
    const data = {
        property_id: propertyId,
        property_type: propertyType,
        action: action,
        rejection_reason: rejectionReason
    };

    // Send the data to the server using fetch
    fetch('approve_reject_property.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.text()) // Parse the response as text
    .then(result => {
        alert(result); // Show the result to the admin
        location.reload(); // Reload the page to reflect the updated status
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status.');
    });
}
    </script>
</body>
</html>