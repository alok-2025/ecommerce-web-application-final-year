<?php 
include ('logic/view_support_logic.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Messages - LokiMart</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    
<?php 
include('includes/header.php'); 
$pageTitle = 'Support Messages';
?>

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
                    <input type="text" id="supportSearch" class="form-control" placeholder="Search users...">
                    <button id="searchClear" class="btn btn-outline-secondary" type="button" aria-label="Clear search">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <h2 class="custom-black-css position-absolute top-50 start-50 translate-middle text-center mb-0">Support Management</h2>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#downloadModal">
            <i class="bi bi-download"></i>
    </div>

    <?php if ($supports && $supports->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Submitted At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 1; while ($row = $supports->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['fullname']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= date("d M Y H:i", strtotime($row['created_at'])) ?></td>
                        <td>
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#supportModal<?= $row['id'] ?>">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Support Message Modal -->
                    <div class="modal fade" id="supportModal<?= $row['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Message from <?= htmlspecialchars($row['fullname']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
                                    <p><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></p>
                                    <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
                                    <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($row['message'])) ?></p>
                                    <p><strong>Submitted By:</strong> <?= htmlspecialchars($row['created_by']) ?></p>
                                    <p><strong>Submitted At:</strong> <?= date("d M Y H:i", strtotime($row['created_at'])) ?></p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
                </tbody>
            </table>
            <p id="noSupportMessage" class="custom-black-css display-none text-center mt-3 text-dark">No matching messages found.</p>
        </div>
    <?php else: ?>
        <p class="text-muted">No support messages found.</p>
    <?php endif; ?>
    <!-- Download Options Modal -->
        <div class="modal fade" id="downloadModal" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Download Messages Table</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body text-center">
                <p class="custom-black-css">Select a format to download:</p>
                <button class="btn btn-outline-primary m-2" onclick="downloadAsCSV()">CSV</button>
                <button class="btn btn-outline-success m-2" onclick="downloadAsXLS()">XLS</button>
                <button class="btn btn-outline-dark m-2" onclick="downloadAsXML()">XML</button>
              </div>
            </div>
          </div>
        </div>
</div>

<!-- Local Bootstrap JS -->
<script src="js/xlsx.full.min.js"></script>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="scripts/script.js"></script>
<?php include('includes/footer.php'); ?>
</body>
</html>
