<?php
session_start();
include 'db.php'; // Assuming db.php is your database connection file

// Fetch land and house properties filtered by location if provided
$search_location = isset($_GET['search_location']) ? trim($_GET['search_location']) : '';

// SQL query to fetch land and house properties sorted by 'created_at' in descending order
$sql_land = "SELECT * FROM land_properties WHERE status = 'approved' ORDER BY created_at DESC, is_featured DESC";
$sql_house = "SELECT * FROM houseproperties WHERE status = 'approved' ORDER BY created_at DESC, is_featured DESC";

if (!empty($search_location)) {
    $sql_land = "SELECT * FROM land_properties WHERE status = 'approved' AND location LIKE :location ORDER BY created_at DESC, is_featured DESC";
    $sql_house = "SELECT * FROM houseproperties WHERE status = 'approved' AND location LIKE :location ORDER BY created_at DESC, is_featured DESC";

    $stmt_land = $conn->prepare($sql_land);
    $stmt_house = $conn->prepare($sql_house);
    $search_param = "%{$search_location}%";
    $stmt_land->bindParam(':location', $search_param);
    $stmt_house->bindParam(':location', $search_param);
} else {
    $stmt_land = $conn->prepare($sql_land);
    $stmt_house = $conn->prepare($sql_house);
}

$stmt_land->execute();
$stmt_house->execute();

$land_properties = $stmt_land->fetchAll(PDO::FETCH_ASSOC);
$house_properties = $stmt_house->fetchAll(PDO::FETCH_ASSOC);

// Update approval date when an admin approves a property
if (isset($_POST['approve_property'])) {
    $property_id = $_POST['property_id'];
    $property_type = $_POST['property_type'];

    // Get current date
    $approval_date = date('Y-m-d H:i:s');
    
    if ($property_type === 'land') {
        // Update land property
        $sql_approve_land = "UPDATE land_properties SET status = 'approved', approval_date = :approval_date WHERE id = :property_id";
        $stmt_approve_land = $conn->prepare($sql_approve_land);
        $stmt_approve_land->bindParam(':approval_date', $approval_date);
        $stmt_approve_land->bindParam(':property_id', $property_id);
        $stmt_approve_land->execute();
    } elseif ($property_type === 'house') {
        // Update house property
        $sql_approve_house = "UPDATE houseproperties SET status = 'approved', approval_date = :approval_date WHERE id = :property_id";
        $stmt_approve_house = $conn->prepare($sql_approve_house);
        $stmt_approve_house->bindParam(':approval_date', $approval_date);
        $stmt_approve_house->bindParam(':property_id', $property_id);
        $stmt_approve_house->execute();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Properties</title>
    
    <style>
    /* General Styles */
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8f9fa;
        color: #333;
    }

    .container {
        width: 90%;
        margin: auto;
        padding: 20px;
        max-width: 1200px;
    }

    h1 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    h5 {
        font-size: 20px; 
        color: red;
        margin-bottom: 20px;
    }

    form {
        margin-bottom: 20px;
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    form input[type="text"] {
        padding: 12px;
        font-size: 1rem;
        width: 60%;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    form button {
        padding: 12px 20px;
        font-size: 1rem;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    form button:hover {
        background-color: #0056b3;
    }

    /* Tab Buttons Styles */
    .view-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .viewland, .viewhouse {
    padding: 15px 30px;
    font-size: 1.1rem;
    color: white;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    font-weight: bold;
    text-transform: uppercase;
    margin: 0 10px; /* Add spacing between buttons */
}

.viewland {
    background-color: #3498db; /* Blue for View Land */
}

.viewhouse {
    background-color: #e67e22; /* Orange for View House */
}

.viewland:hover {
    background-color: #2980b9; /* Darker blue on hover */
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
    transform: translateY(-3px);
}

.viewhouse:hover {
    background-color: #d35400; /* Darker orange on hover */
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
    transform: translateY(-3px);
}

.viewland:active, .viewhouse:active {
    transform: translateY(0);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.viewland.active, .viewhouse.active {
    background-color: #2ecc71; /* Green for active state */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    color: #fff;
}

    .tab-content {
        display: none;
    }

    .active-tab {
        display: block;
    }

    /* Property Card Styles */
    .property {
        background-color: white;
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        transition: transform 0.3s;
    }

    .property:hover {
        transform: translateY(-5px);
    }

    .property img {
        max-width: 600px;
        height: auto;
        border-radius: 5px;
        margin-top: 10px;
    }

    .property h3 {
        color: #34495e;
        margin-bottom: 10px;
    }

    .property p {
        margin: 5px 0;
        color: #555;
    }

    .property h4 {
        font-size: 1.1rem;
        color: #2c3e50;
        margin-top: 15px;
    }

    .property ul {
        list-style-type: none;
        padding: 0;
    }

    .property ul li {
        margin: 10px 0;
    }

    /* Footer Styles */
    footer {
        background-color: #333;
        color: white;
        padding: 10px 0;
        text-align: center;
        position: fixed;
        width: 100%;
        bottom: 0;
        left: 0;
    }

    footer p {
        margin: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        form input[type="text"] {
            width: 100%;
        }

        form button {
            width: 100%;
        }
    }
    
    /* Navbar Styling */
nav {
    background-color: #007BFF;
    padding:  20px;
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    display: flex;
    justify-content: center; /* Center all content horizontally */
    align-items: center;
}

/* Navigation Links */
nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
}

nav ul li {
    margin: 0 15px;
}

nav ul li a {
    text-decoration: none;
    color: black;
    font-size: 18px;
    padding: 10px 15px;
    transition: 0.3s;
}

nav ul li a:hover {
    background-color: #0056b3;
    border-radius: 5px;
}
    /* Hide Hamburger Icon on Large Screens */
.menu-toggle {
    font-size: 30px;
    color: black;
    cursor: pointer;
    display: none;
    position: absolute; /* To place it relative to the nav */
    left: 15px; /* Align it to the left side */
    top: 3px; /* Optional: Add space from the top */
}

/* Responsive Navigation (For Small Screens) */
@media (max-width: 768px) {
    .menu-toggle {
        display: block; /* Show hamburger menu icon */
    }

    nav ul {
        display: none; /* Hide menu by default */
        flex-direction: column;
        width: 100%;
        position: absolute;
        top: 60px;
        left: 0;
        background-color: lightsteelblue;
        text-align: left;
    }

    nav ul.active {
        display: flex; /* Show menu when active */
    }

    nav ul li {
        margin: 10px 0;
    }
}

    /* Add this CSS to hide elements initially */
    .hidden {
        display: none;
    }
    /* buy property butoon style */
    #buyPropertiesButton {
    padding: 15px 30px;
    font-size: 1.2rem;
    background-color: #2ecc71; /* Green color */
    color: white;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    font-weight: bold;
    text-transform: uppercase;
}

#buyPropertiesButton:hover {
    background-color: #27ae60; /* Darker green on hover */
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
    transform: translateY(-3px);
}

#buyPropertiesButton:active {
    background-color: #229954; /* Even darker green on click */
    transform: translateY(0);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
    </style>
</head>

<body>
<nav>
    <div class="menu-toggle" onclick="toggleMenu()">&#9776;</div>  <!-- Hamburger Icon -->
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="buy.php">Buy</a></li>
        <li><a href="sell.php">Sell</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact us</a></li>
        <li><a href="my_property.php">My Property</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>
<div class="container">
    <h1>Buy Properties</h1>
    
    <!-- Buy Properties Button -->
    <div style="text-align: center; margin-bottom: 20px;">
        <button id="buyPropertiesButton" onclick="showProperties()">Buy Properties</button>
    </div>

    <!-- Search Form -->
    <form method="GET" action="" style="text-align: center; margin-bottom: 20px;" class="hidden" id="searchForm">
        <input type="text" name="search_location" placeholder="Search by location" 
               value="<?= htmlspecialchars($search_location) ?>" 
               style="padding: 10px; font-size: 1em; width: 60%; border: 1px solid #ccc; border-radius: 5px;">
        <button type="submit" style="padding: 10px 20px; font-size: 1.1em;">Search</button>
    </form>

    <!-- Buttons for viewing Land and House properties -->
    <div style="text-align: center;" class="hidden" id="viewButtons">
        <button id="viewLand" class="viewland" onclick="toggleTab('land')">View Land</button>
        <button id="viewHouse" class="viewhouse" onclick="toggleTab('house')">View House</button>
    </div>

    <!-- Display Land Properties -->
    <div id="landProperties" class="tab-content hidden">
        <h2>Land Properties</h2>
        <?php if (!empty($land_properties)): ?>
            <?php foreach ($land_properties as $property): ?>
                <div class="property">
                    <h3>Location: <?= htmlspecialchars($property['location']) ?></h3>
                    <p>Area: <?= htmlspecialchars($property['area']) ?> </p>
                    <p>Price: NPR <?= htmlspecialchars($property['price']) ?></p>
                    <h4>Map:</h4>
                    <img src="<?= htmlspecialchars($property['map_image']) ?>" alt="Property Map">
                    <h4>Images:</h4>
                    <?php
                    $images = json_decode($property['property_images'], true);
                    if ($images) {
                        foreach ($images as $image) {
                            echo '<img src="' . htmlspecialchars($image) . '" alt="Property Image" style="max-width: 600px; margin-right: 10px;">';
                        }
                    }
                    ?>
                    <p>Posted on: <?= date('Y-m-d H:i:s', strtotime($property['created_at'])) ?></p>
                    <h5>If you want to buy this property. You have to contact in this no:9823167724</h5>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No land properties available.</p>
        <?php endif; ?>
    </div>

    <!-- Display House Properties -->
    <div id="houseProperties" class="tab-content hidden">
        <h2>House Properties</h2>
        <?php if (!empty($house_properties)): ?>
            <?php foreach ($house_properties as $property): ?>
                <div class="property">
                    <h3>Location: <?= htmlspecialchars($property['location']) ?></h3>
                    <p>Price: NPR <?= htmlspecialchars($property['price']) ?></p>
                    <p>area: <?= htmlspecialchars($property['area']) ?></p>
                    <h4>House Details:</h4>
                    <ul>
                        <li>Total Floors: <?= htmlspecialchars($property['floors']) ?></li>
                        <li>Bedrooms: <?= htmlspecialchars($property['bedrooms']) ?></li>
                        <li>Living Rooms: <?= htmlspecialchars($property['living_rooms']) ?></li>
                        <li>Kitchens: <?= htmlspecialchars($property['kitchens']) ?></li>
                        <li>Washrooms: <?= htmlspecialchars($property['washrooms']) ?></li>
                        <li>Attached Washrooms: <?= htmlspecialchars($property['attached_washrooms']) ?></li>
                    </ul>
                    <h4>Map:</h4>
                    <img src="<?= htmlspecialchars($property['map_image']) ?>" alt="Property Map">
                    <h4>Images:</h4>
                    <?php
                    $images = json_decode($property['property_images'], true);
                    if ($images) {
                        foreach ($images as $image) {
                            echo '<img src="' . htmlspecialchars($image) . '" alt="Property Image" style="max-width: 600px; margin-right: 10px;">';
                        }
                    }
                    ?>
                    <p>Posted on: <?= date('Y-m-d H:i:s', strtotime($property['created_at'])) ?></p>
                    <h5>If you want to buy this property. You have to contact in this no:9823167724</h5>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No house properties available.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>Company Details | Contact: 9823167724 | Email: contact@realestate.com  | Location: Thamel, Kathmandu</p>
</footer>

<script>
    // JavaScript to toggle between the tabs for Land and House properties
    function toggleTab(tab) {
        var landTab = document.getElementById('landProperties');
        var houseTab = document.getElementById('houseProperties');
        var landButton = document.getElementById('viewLand');
        var houseButton = document.getElementById('viewHouse');

        if (tab === 'land') {
            landTab.classList.add('active-tab');
            houseTab.classList.remove('active-tab');
            landButton.classList.add('active');
            houseButton.classList.remove('active');
        } else {
            houseTab.classList.add('active-tab');
            landTab.classList.remove('active-tab');
            houseButton.classList.add('active');
            landButton.classList.remove('active');
        }
    }

    // Function to show properties when "Buy Properties" button is clicked
    function showProperties() {
        document.getElementById('buyPropertiesButton').classList.add('hidden');
        document.getElementById('searchForm').classList.remove('hidden');
        document.getElementById('viewButtons').classList.remove('hidden');
        document.getElementById('landProperties').classList.remove('hidden');
        document.getElementById('houseProperties').classList.remove('hidden');
        toggleTab('land'); // Show land properties by default
    }

    function toggleMenu() {
        const navMenu = document.querySelector("nav ul");
        navMenu.classList.toggle("active");
    }

    // Initialize the first tab
    toggleTab('land');
</script>
</body>
</html>