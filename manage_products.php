<?php 
include ('logic/manage_products_logic.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Lokimart</title>
    <!-- Local Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css">

</head>

<body>

    <?php include ('includes/header.php') ?>

<div class="container mt-5 mb-5">

    <div class="position-relative d-flex justify-content-between align-items-center mb-4">

        <div class="d-flex align-items-center gap-2">
            <!-- Back Button -->
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i>
            </a>
            <div id="searchWrapper" class="d-flex align-items-center ms-2">
                <!-- Search icon visible initially -->
                <button id="searchToggle" class="btn btn-outline-secondary" type="button" aria-label="Toggle search">
                    <i class="bi bi-search"></i>
                </button>

                <!-- Hidden search bar with input + icon inside -->
                <div id="searchBarContainer" class="search-container input-group ms-2">
                    <input type="text" id="productSearch" class="form-control" placeholder="Search products...">
                    <button id="searchClear" class="btn btn-outline-secondary" type="button" aria-label="Clear search">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Title -->
        <h2 class="custom-black-css position-absolute top-50 start-50 translate-middle text-center mb-0">Product Management</h2>
        
        <div class="d-flex gap-2">
            <!-- Add Product Button -->
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle"></i>
            </button>

            <!-- Download Button -->
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#downloadModal">
                <i class="bi bi-download"></i>
            </button>
        </div>
        
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($product = $products->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td>ZK<?= number_format($product['price'], 2) ?></td>
                    <td><?= isset($product['quantity']) ? (int)$product['quantity'] : 0 ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td>
                        <img src="<?= $product['image_url'] ?: 'images/default.png' ?>" 
                        width="50" 
                        class="rounded product-thumbnail"
                        alt="Product Image">
                    </td>
                    <td>
                        <?php if ($_SESSION['role'] === 'Vendor'): ?>
                        <!-- Active buttons for Vendor -->
                        <button class="btn btn-warning edit-btn"
                            data-id="<?= $product['id'] ?>"
                            data-name="<?= htmlspecialchars($product['name']) ?>"
                            data-price="<?= $product['price'] ?>"
                            data-quantity="<?= isset($product['quantity']) ? $product['quantity'] : 0 ?>"
                            data-description="<?= htmlspecialchars($product['description']) ?>"
                            data-image="<?= $product['image_url'] ?>"
                            data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-danger delete-btn"
                            data-id="<?= $product['id'] ?>"
                            data-name="<?= htmlspecialchars($product['name']) ?>"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i>
                        </button>
                        <?php else: ?>
                            <!-- Grayed out / disabled buttons for non-Vendors -->
                            <button class="btn btn-secondary" disabled title="Only vendors can edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-secondary" disabled title="Only vendors can delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <p id="noProductsMessage" class="custom-black-css display-none text-center mt-3 text-dark">No matching products found.</p>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input class="form-control mb-2" name="name" placeholder="Name" required>
                <input class="form-control mb-2" name="price" type="number" step="0.01" placeholder="Price" required>
                <input class="form-control mb-2" name="quantity" type="number" placeholder="Quantity" required>
                <textarea class="form-control mb-2" name="description" placeholder="Description" required></textarea>
                <input class="form-control" type="file" name="image" accept="image/*">
            </div>
            <div class="modal-footer">
                <button type="submit" name="add_product" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div></div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" id="editProductId">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input class="form-control mb-2" name="name" id="editName" required>
                <input class="form-control mb-2" name="price" id="editPrice" type="number" step="0.01" required>
                <input class="form-control mb-2" name="quantity" id="editQuantity" type="number" placeholder="Quantity" required>
                <textarea class="form-control mb-2" name="description" id="editDescription" required></textarea>
                <img id="editImagePreview" src="" class="rounded mb-2" width="80">
                <input class="form-control" type="file" name="image" accept="image/*">
            </div>
            <div class="modal-footer">
                <button type="submit" name="update_product" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div></div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST">
            <input type="hidden" name="product_id" id="deleteProductId">
            <div class="modal-header">
                <h5 class="modal-title">Delete Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong id="deleteProductName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="submit" name="delete_product" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div></div>
</div>
<!-- Image Modal -->
<div id="imageModal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-transparent border-0 text-center">
      <div class="img-modal modal-body p-0 d-flex justify-content-center align-items-center">
        <img id="fullImage" src="" class="full-image img-fluid rounded shadow" alt="Full view">
      </div>
    </div>
  </div>
</div>

<!-- Download Options Modal -->
<div class="modal fade" id="downloadModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Download Product List</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p class="custom-black-css">Select a format to download:</p>
        <button class="btn btn-outline-primary m-2" onclick="downloadProductsAsCSV()">CSV</button>
        <button class="btn btn-outline-success m-2" onclick="downloadProductsAsXLS()">XLS</button>
        <button class="btn btn-outline-dark m-2" onclick="downloadProductsAsXML()">XML</button>
      </div>
    </div>
  </div>
</div>

<!-- Local Bootstrap JS -->
<script src="js/xlsx.full.min.js"></script>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="scripts/script.js"></script>
<?php include ('includes/footer.php'); ?>
</body>
</html>
