<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if property ID is provided
if (!isset($_GET['id'])) {
    header('Location: my_properties.php');
    exit();
}

$property_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch property details
$stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ? AND user_id = ?");
$stmt->execute([$property_id, $user_id]);
$property = $stmt->fetch();

if (!$property) {
    header('Location: my_properties.php');
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $location = trim($_POST['location']);
    $type = trim($_POST['type']);
    $status = trim($_POST['status']);
    $bedrooms = intval($_POST['bedrooms']);
    $bathrooms = intval($_POST['bathrooms']);
    $area = floatval($_POST['area']);
    $image_url = $property['image_url'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $newname = uniqid() . '.' . $filetype;
            $upload_path = 'uploads/properties/' . $newname;
            
            if (!file_exists('uploads/properties')) {
                mkdir('uploads/properties', 0777, true);
            }
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Delete old image if it's not the default
                if ($property['image_url'] !== 'images/default-property.jpg') {
                    @unlink($property['image_url']);
                }
                $image_url = $upload_path;
            }
        }
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE properties 
            SET title = ?, description = ?, price = ?, location = ?, type = ?, 
                status = ?, bedrooms = ?, bathrooms = ?, area = ?, image_url = ?
            WHERE id = ? AND user_id = ?
        ");
        
        $stmt->execute([
            $title, $description, $price, $location, $type, $status,
            $bedrooms, $bathrooms, $area, $image_url, $property_id, $user_id
        ]);
        
        $success = "Property updated successfully!";
        // Refresh property data
        $stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ? AND user_id = ?");
        $stmt->execute([$property_id, $user_id]);
        $property = $stmt->fetch();
    } catch(PDOException $e) {
        $error = "Error updating property: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property - Real Estate System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Property</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">Property Title</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="<?php echo htmlspecialchars($property['title']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" 
                                          rows="4" required><?php echo htmlspecialchars($property['description']); ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="price" name="price" 
                                               value="<?php echo $property['price']; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="location" name="location" 
                                           value="<?php echo htmlspecialchars($property['location']); ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="type" class="form-label">Property Type</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="house" <?php echo $property['type'] === 'house' ? 'selected' : ''; ?>>House</option>
                                        <option value="apartment" <?php echo $property['type'] === 'apartment' ? 'selected' : ''; ?>>Apartment</option>
                                        <option value="condo" <?php echo $property['type'] === 'condo' ? 'selected' : ''; ?>>Condo</option>
                                        <option value="land" <?php echo $property['type'] === 'land' ? 'selected' : ''; ?>>Land</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="available" <?php echo $property['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                                        <option value="sold" <?php echo $property['status'] === 'sold' ? 'selected' : ''; ?>>Sold</option>
                                        <option value="rented" <?php echo $property['status'] === 'rented' ? 'selected' : ''; ?>>Rented</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="image" class="form-label">Property Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <?php if ($property['image_url']): ?>
                                        <div class="mt-2">
                                            <img src="<?php echo htmlspecialchars($property['image_url']); ?>" 
                                                 alt="Current property image" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="bedrooms" class="form-label">Bedrooms</label>
                                    <input type="number" class="form-control" id="bedrooms" name="bedrooms" 
                                           value="<?php echo $property['bedrooms']; ?>" min="0">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="bathrooms" class="form-label">Bathrooms</label>
                                    <input type="number" class="form-control" id="bathrooms" name="bathrooms" 
                                           value="<?php echo $property['bathrooms']; ?>" min="0">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="area" class="form-label">Area (sq ft)</label>
                                    <input type="number" class="form-control" id="area" name="area" 
                                           value="<?php echo $property['area']; ?>" min="0">
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Save Changes
                                </button>
                                <a href="my_properties.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to My Properties
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 