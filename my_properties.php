<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle property deletion
if (isset($_POST['delete_property'])) {
    $property_id = $_POST['property_id'];
    $user_id = $_SESSION['user_id'];
    
    // Verify ownership before deletion
    $stmt = $pdo->prepare("DELETE FROM properties WHERE id = ? AND user_id = ?");
    $stmt->execute([$property_id, $user_id]);
}

// Fetch user's properties
$stmt = $pdo->prepare("
    SELECT p.*, 
           COUNT(DISTINCT v.id) as view_count,
           COUNT(DISTINCT m.id) as message_count
    FROM properties p
    LEFT JOIN property_views v ON p.id = v.property_id
    LEFT JOIN messages m ON p.id = m.property_id
    WHERE p.user_id = ?
    GROUP BY p.id
    ORDER BY p.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$properties = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Properties - Real Estate System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">My Properties</h1>
            <a href="add_property.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Property
            </a>
        </div>

        <?php if (empty($properties)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> You haven't listed any properties yet.
                <a href="add_property.php" class="alert-link">Add your first property</a> to get started!
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($properties as $property): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <img src="<?php echo htmlspecialchars($property['image_url'] ?? 'images/default-property.jpg'); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($property['title']); ?>"
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($property['title']); ?></h5>
                                <p class="card-text text-primary fw-bold">$<?php echo number_format($property['price']); ?></p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($property['location']); ?>
                                    </small>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-<?php echo $property['status'] === 'available' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($property['status']); ?>
                                        </span>
                                    </div>
                                    <div>
                                        <small class="text-muted">
                                            <i class="bi bi-eye"></i> <?php echo $property['view_count']; ?> views
                                        </small>
                                        <small class="text-muted ms-2">
                                            <i class="bi bi-envelope"></i> <?php echo $property['message_count']; ?> messages
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="btn-group w-100">
                                    <a href="property_details.php?id=<?php echo $property['id']; ?>" 
                                       class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="edit_property.php?id=<?php echo $property['id']; ?>" 
                                       class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this property?');">
                                        <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
                                        <button type="submit" name="delete_property" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 