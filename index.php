<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch properties
try {
    $stmt = $pdo->query("SELECT p.*, u.username as seller_name 
                         FROM properties p 
                         JOIN users u ON p.user_id = u.id 
                         ORDER BY p.created_at DESC");
    $properties = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching properties: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Estate System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-house-door-fill"></i> Real Estate
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_property.php">Add Property</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_properties.php">My Properties</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>Find Your Dream Home</h1>
                <p>Discover the perfect property from our extensive collection of listings</p>
                <a href="#properties" class="btn btn-primary btn-lg">View Properties</a>
            </div>
        </div>
    </section>

    <!-- Properties Section -->
    <section id="properties" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Available Properties</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="row">
                <?php foreach ($properties as $property): ?>
                    <div class="col-md-4">
                        <div class="property-card">
                            <img src="<?php echo htmlspecialchars($property['image_url'] ?? 'images/default-property.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($property['title']); ?>" 
                                 class="property-image">
                            <div class="property-info">
                                <div class="property-price">$<?php echo number_format($property['price'], 2); ?></div>
                                <h3 class="property-title"><?php echo htmlspecialchars($property['title']); ?></h3>
                                <div class="property-details">
                                    <p><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($property['location']); ?></p>
                                    <p><i class="bi bi-house"></i> <?php echo htmlspecialchars($property['property_type']); ?></p>
                                    <p><i class="bi bi-tag"></i> <?php echo htmlspecialchars($property['status']); ?></p>
                                </div>
                                <a href="property_details.php?id=<?php echo $property['id']; ?>" class="btn btn-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h4>About Us</h4>
                    <p>Your trusted partner in finding the perfect property. We connect buyers with sellers and help make real estate dreams come true.</p>
                </div>
                <div class="col-md-4">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="add_property.php">Add Property</a></li>
                        <li><a href="my_properties.php">My Properties</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h4>Contact Us</h4>
                    <ul class="footer-links">
                        <li><i class="bi bi-envelope"></i> info@realestate.com</li>
                        <li><i class="bi bi-telephone"></i> (123) 456-7890</li>
                        <li><i class="bi bi-geo-alt"></i> 123 Real Estate St, City</li>
                    </ul>
                </div>
            </div>
            <div class="text-center mt-4">
                <p>&copy; 2024 Real Estate System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 