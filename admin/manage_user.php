<?php
require_once '../config/db.php';
require_once '../includes/auth.php';

requireRole('admin');

$page_title = 'Manage Users - Admin';
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';
        
        if (empty($username) || empty($password) || empty($role)) {
            $error = 'All fields are required.';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                $stmt->execute([$username, $password, $role]); // In production, hash the password
                $message = 'User added successfully.';
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = 'Username already exists.';
                } else {
                    $error = 'Failed to add user.';
                }
            }
        }
    } elseif ($action === 'delete') {
        $user_id = $_POST['user_id'] ?? '';
        if ($user_id && $user_id != $_SESSION['user_id']) {
            try {
                $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
                $stmt->execute([$user_id]);
                $message = 'User deleted successfully.';
            } catch (PDOException $e) {
                $error = 'Failed to delete user.';
            }
        } else {
            $error = 'Cannot delete your own account.';
        }
    } elseif ($action === 'update') {
        $user_id = $_POST['user_id'] ?? '';
        $username = trim($_POST['username'] ?? '');
        $role = $_POST['role'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($role)) {
            $error = 'Username and role are required.';
        } else {
            try {
                if (!empty($password)) {
                    $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE user_id = ?");
                    $stmt->execute([$username, $password, $role, $user_id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ? WHERE user_id = ?");
                    $stmt->execute([$username, $role, $user_id]);
                }
                $message = 'User updated successfully.';
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = 'Username already exists.';
                } else {
                    $error = 'Failed to update user.';
                }
            }
        }
    }
}

// Get all users
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $users = [];
    $error = 'Failed to load users.';
}

include '../includes/header.php';
?>

<div class="col-12">
    <div class="main-content p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-people"></i> Manage Users</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-person-plus"></i> Add New User
            </button>
        </div>

        <!-- Messages -->
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Users (<?php echo count($users); ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['user_id']; ?></td>
                                <td>
                                    <i class="bi bi-person-circle"></i> 
                                    <?php echo htmlspecialchars($user['username']); ?>
                                    <?php if ($user['user_id'] == $_SESSION['user_id']): ?>
                                        <span class="badge bg-primary ms-1">You</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $user['role'] === 'admin' ? 'danger' : 
                                            ($user['role'] === 'supplier' ? 'warning' : 'info'); 
                                    ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y g:i A', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-primary" 
                                                onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)"
                                                data-bs-toggle="modal" data-bs-target="#editUserModal">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                                        <form method="POST" style="display: inline;" 
                                              onsubmit="return confirmDelete('Are you sure you want to delete this user?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus"></i> Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="addUserForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="mb-3">
                        <label for="add_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="add_username" name="username" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="add_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="add_password" name="password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="add_role" class="form-label">Role</label>
                        <select class="form-select" id="add_role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="supplier">Supplier</option>
                            <option value="customer">Customer</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil"></i> Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editUserForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    
                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit_username" name="username" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="supplier">Supplier</option>
                            <option value="customer">Customer</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editUser(user) {
    document.getElementById('edit_user_id').value = user.user_id;
    document.getElementById('edit_username').value = user.username;
    document.getElementById('edit_role').value = user.role;
    document.getElementById('edit_password').value = '';
}

// Form validation
validateForm('addUserForm');
validateForm('editUserForm');
</script>

<?php include '../includes/footer.php'; ?>