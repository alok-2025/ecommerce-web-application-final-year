<?php 
include ('logic/manage_users_logic.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - LokiMart</title>
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
                    <input type="text" id="userSearch" class="form-control" placeholder="Search users...">
                    <button id="searchClear" class="btn btn-outline-secondary" type="button" aria-label="Clear search">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>


        <!-- Title -->
        <h2 class="custom-black-css position-absolute top-50 start-50 translate-middle text-center mb-0">User Management</h2>

        <div class="d-flex gap-2">
            <!-- Add User Button -->
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-person-plus"></i>
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
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Profile</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <img src="uploads/<?= htmlspecialchars($user['profile_pic']) ?>"
                        alt="Profile Picture"
                        onerror="this.src='uploads/default_img.png';" 
                        width="50" 
                        class="rounded profile-thumbnail"
                        onerror="this.src='uploads/default_img.png';">
                    </td>
                    <td>
                        <button class="btn btn-warning edit-btn"
                            data-id="<?= $user['id'] ?>"
                            data-username="<?= htmlspecialchars($user['username']) ?>"
                            data-email="<?= htmlspecialchars($user['email']) ?>"
                            data-role="<?= $user['role'] ?>"
                            data-pic="<?= $user['profile_pic'] ?>"
                            data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <?php if ($user['id'] == $_SESSION['user_id'] || $user['role'] === 'Administrator'): ?>
                            <button class="btn btn-secondary" disabled 
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="Administrator cannot be deleted">
                                <i class="bi bi-trash"></i>
                            </button>
                        <?php else: ?>
                            <button class="btn btn-danger delete-btn"
                                data-id="<?= $user['id'] ?>"
                                data-username="<?= htmlspecialchars($user['username']) ?>"
                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="bi bi-trash"></i>
                            </button>
                        <?php endif; ?>
                    </td>

                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <p id="noUsersMessage" class="custom-black-css display-none text-center mt-3 text-dark">No matching users found.</p>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input class="form-control mb-2" name="username" placeholder="Username" required>
                <input class="form-control mb-2" name="email" type="email" placeholder="Email" required>

                <div class="position-relative mb-2">
                    <input class="form-control password-input" name="password" id="register_password" type="password" placeholder="Password" required>
                    <i class="bi bi-eye-fill toggle-password position-absolute top-50 end-0 translate-middle-y me-3" id="register_togglePassword" style="cursor:pointer;"></i>
                </div>
                <div class="position-relative mb-2">
                    <input class="form-control password-input" name="confirm_password" id="register_confirm_password" type="password" placeholder="Re-enter Password" required>
                    <i class="bi bi-eye-fill toggle-password position-absolute top-50 end-0 translate-middle-y me-3" id="register_toggleConfirmPassword" style="cursor:pointer;"></i>
                </div>

                <select name="role" class="form-control mb-2">
                    <option value="Visitor">Visitor</option>
                    <option value="Customer">Customer</option>
                    <option value="Vendor">Vendor</option>
                    <option value="Administrator">Administrator</option>
                </select>
                <input class="form-control" type="file" name="profile_pic" accept="image/*">
            </div>
            <div class="modal-footer">
                <button type="submit" name="add_user" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div></div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="user_id" id="editUserId">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input class="form-control mb-2" name="username" id="editUsername" required>
                <input class="form-control mb-2" name="email" id="editEmail" type="email" required>

                <div class="position-relative mb-2">
                    <input class="form-control password-input" name="password" id="login_password" type="password" placeholder="New Password">
                    <i class="bi bi-eye-fill toggle-password position-absolute top-50 end-0 translate-middle-y me-3" id="login_togglePassword" style="cursor:pointer;"></i>
                </div>
                <select name="role" class="form-control mb-2" id="editRole">
                    <option value="Visitor">Visitor</option>
                    <option value="Customer">Customer</option>
                    <option value="Vendor">Vendor</option>
                    <option value="Administrator">Administrator</option>
                </select>
                <img id="editPicPreview" src="" 
                alt="Profile picture" 
                onerror="this.src='uploads/default_img.png';" 
                class="rounded mb-2" width="80">
                <input class="form-control" type="file" name="profile_pic" accept="image/*">
            </div>
            <div class="modal-footer">
                <button type="submit" name="update_user" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div></div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST">
            <input type="hidden" name="user_id" id="deleteUserId">
            <div class="modal-header">
                <h5 class="modal-title">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                Are you sure you want to delete <strong id="deleteUsername"></strong>?
            </div>

            <div class="modal-footer">
                <button type="submit" name="delete_user" class="btn btn-danger">Delete</button>
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
        <h5 class="modal-title">Download Users List</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p class="custom-black-css">Select a format to download:</p>
        <button class="btn btn-outline-primary m-2" onclick="downloadUsersAsCSV()">CSV</button>
        <button class="btn btn-outline-success m-2" onclick="downloadUsersAsXLS()">XLS</button>
        <button class="btn btn-outline-dark m-2" onclick="downloadUsersAsXML()">XML</button>
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
