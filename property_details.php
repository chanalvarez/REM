<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get property ID from URL
$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    $stmt = $pdo->prepare("SELECT p.*, u.username as seller_name, u.email as seller_email, u.phone as seller_phone 
                          FROM properties p 
                          JOIN users u ON p.user_id = u.id 
                          WHERE p.id = ?");
    $stmt->execute([$property_id]);
    $property = $stmt->fetch();

    if (!$property) {
        header("Location: index.php");
        exit();
    }
} catch(PDOException $e) {
    $error = "Error fetching property details: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($property['title']); ?> - Real Estate System</title>
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

    <div class="container py-5">
        <div class="property-details">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="property-gallery mb-4">
                        <img src="<?php echo htmlspecialchars($property['image_url'] ?? 'images/default-property.jpg'); ?>" 
                             alt="<?php echo htmlspecialchars($property['title']); ?>" 
                             class="img-fluid rounded">
                    </div>

                    <h2 class="mb-4"><?php echo htmlspecialchars($property['title']); ?></h2>
                    
                    <div class="property-features">
                        <div class="feature-item">
                            <i class="bi bi-currency-dollar"></i>
                            <h4>Price</h4>
                            <p>$<?php echo number_format($property['price'], 2); ?></p>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-geo-alt"></i>
                            <h4>Location</h4>
                            <p><?php echo htmlspecialchars($property['location']); ?></p>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-house"></i>
                            <h4>Type</h4>
                            <p><?php echo htmlspecialchars($property['property_type']); ?></p>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-tag"></i>
                            <h4>Status</h4>
                            <p><?php echo htmlspecialchars($property['status']); ?></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3>Description</h3>
                        <p class="lead"><?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="bi bi-door-open"></i>
                                <h4>Bedrooms</h4>
                                <p><?php echo $property['bedrooms'] ?: 'N/A'; ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="bi bi-droplet"></i>
                                <h4>Bathrooms</h4>
                                <p><?php echo $property['bathrooms'] ?: 'N/A'; ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="bi bi-arrows-angle-expand"></i>
                                <h4>Square Feet</h4>
                                <p><?php echo $property['square_feet'] ?: 'N/A'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="contact-form">
                        <h3 class="mb-4">Contact Seller</h3>
                        <div class="mb-4">
                            <h5><?php echo htmlspecialchars($property['seller_name']); ?></h5>
                            <p><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($property['seller_email']); ?></p>
                            <?php if ($property['seller_phone']): ?>
                                <p><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($property['seller_phone']); ?></p>
                            <?php endif; ?>
                        </div>
                        <form action="send_message.php" method="POST">
                            <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">
                            <input type="hidden" name="seller_id" value="<?php echo $property['user_id']; ?>">
                            <div class="mb-3">
                                <label for="message" class="form-label">Your Message</label>
                                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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