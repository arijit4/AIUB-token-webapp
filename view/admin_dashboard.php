<?php
session_start();
require_once "../model/users.php";
require_once "../model/rooms.php";
require_once "../model/Tokens.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$usersModel = new Users();
$roomsModel = new Rooms();
$tokensModel = new Tokens();

$allUsers = $usersModel->get_all_users();
$allRooms = $roomsModel->get_all_rooms_with_supervisor();
$unassignedSupervisors = $usersModel->get_unassigned_supervisors();
$allSupervisors = $usersModel->get_all_supervisors();

// System status
$systemStatus = file_exists("../system_status.txt") ? trim(file_get_contents("../system_status.txt")) : "CLOSED";

$activeTab = $_GET['tab'] ?? 'process';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Token Management System</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <style>
        .dashboard-container { max-width: 1100px; margin: 20px auto; padding: 20px; }
        .tabs { display: flex; gap: 10px; margin-bottom: 20px; }
        .tab-btn {
            padding: 10px 20px; background: #eee; border: none; cursor: pointer;
            border-radius: 5px; text-decoration: none; color: black;
        }
        .tab-btn.active { background: #4CAF50; color: white; }
        .card { background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #4CAF50; color: white; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; color: white; }
        .btn-edit { background: #2196F3; }
        .btn-delete { background: #f44336; }
        .btn-success { background: #4CAF50; }
        .btn-danger { background: #f44336; }
        .status-open { color: green; font-weight: bold; }
        .status-closed { color: red; font-weight: bold; }
        .form-row { margin-bottom: 12px; }
        .form-row label { display: inline-block; width: 140px; }
        .success { color: green; background: #e8f5e9; padding: 10px; border-radius: 5px; }
        .error { color: red; background: #ffebee; padding: 10px; border-radius: 5px; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 10% auto; padding: 20px; width: 400px; border-radius: 8px; }
    </style>
</head>
<body>
<div class="dashboard-container">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong> 
       | <a href="logout.php">Logout</a></p>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
    <?php endif; ?>

    <!-- TABS -->
    <div class="tabs">
        <a href="?tab=process" class="tab-btn <?php echo $activeTab=='process'?'active':''; ?>">Registration Process</a>
        <a href="?tab=rooms" class="tab-btn <?php echo $activeTab=='rooms'?'active':''; ?>">Allocate Rooms</a>
        <a href="?tab=users" class="tab-btn <?php echo $activeTab=='users'?'active':''; ?>">Manage Users</a>
        <a href="?tab=assign" class="tab-btn <?php echo $activeTab=='assign'?'active':''; ?>">Assign Supervisor</a>
    </div>

    <!-- ==================== TAB 1: REGISTRATION PROCESS ==================== -->
    <?php if ($activeTab === 'process'): ?>
    <div class="card">
        <h2>Registration Process Control</h2>
        <p>Current Status: 
            <span class="<?php echo $systemStatus === 'OPEN' ? 'status-open' : 'status-closed'; ?>">
                <?php echo $systemStatus === 'OPEN' ? 'OPEN (Students can take tokens)' : 'CLOSED'; ?>
            </span>
        </p>

        <form method="post" action="../controller/admin-handler.php" style="display:inline;">
            <input type="hidden" name="action" value="start_registration">
            <button type="submit" class="btn btn-success" <?php echo $systemStatus==='OPEN'?'disabled':''; ?>>
                Start Registration Process
            </button>
        </form>

        <form method="post" action="../controller/admin-handler.php" style="display:inline; margin-left:10px;">
            <input type="hidden" name="action" value="stop_registration">
            <button type="submit" class="btn btn-danger" <?php echo $systemStatus==='CLOSED'?'disabled':''; ?>>
                Stop Registration Process
            </button>
        </form>
    </div>
    <?php endif; ?>

    <!-- ==================== TAB 2: ALLOCATE ROOMS ==================== -->
    <?php if ($activeTab === 'rooms'): ?>
    <div class="card">
        <h2>Allocate Rooms</h2>

        <h3>Create New Room</h3>
        <form method="post" action="../controller/admin-handler.php">
            <input type="hidden" name="action" value="create_room">
            <div class="form-row">
                <label>Room Name:</label>
                <input type="text" name="name" required placeholder="e.g. DN0903">
            </div>
            <div class="form-row">
                <label>Capacity:</label>
                <input type="number" name="capacity" required min="1" value="300">
            </div>
            <div class="form-row">
                <label>Supervisor:</label>
                <select name="supervisor_id" required>
                    <option value="">-- Select Supervisor --</option>
                    <?php foreach ($unassignedSupervisors as $sup): ?>
                        <option value="<?php echo $sup['id']; ?>">
                            <?php echo htmlspecialchars($sup['fullname'] . " (" . $sup['uni_id'] . ")"); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Create Room</button>
        </form>

        <h3 style="margin-top:30px;">Existing Rooms</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Capacity</th>
                <th>Current Load</th>
                <th>Supervisor</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($allRooms as $room): ?>
            <tr>
                <td><?php echo $room['id']; ?></td>
                <td><?php echo htmlspecialchars($room['name']); ?></td>
                <td><?php echo $room['capacity']; ?></td>
                <td><?php echo $room['current_load']; ?></td>
                <td><?php echo htmlspecialchars($room['supervisor_name'] ?? 'None'); ?></td>
                <td>
                    <button class="btn btn-edit" onclick="openEditRoom(
                        <?php echo $room['id']; ?>,
                        '<?php echo htmlspecialchars($room['name']); ?>',
                        <?php echo $room['capacity']; ?>,
                        <?php echo $room['supervisor_id']; ?>
                    )">Edit</button>

                    <form method="post" action="../controller/admin-handler.php" style="display:inline;" 
                          onsubmit="return confirm('Are you sure you want to delete this room?');">
                        <input type="hidden" name="action" value="delete_room">
                        <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                        <button type="submit" class="btn btn-delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>

    <!-- ==================== TAB 3: MANAGE USERS ==================== -->
    <?php if ($activeTab === 'users'): ?>
    <div class="card">
        <h2>Manage User List</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>University ID</th>
                <th>Full Name</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($allUsers as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['uni_id']); ?></td>
                <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td><?php echo $user['created_at']; ?></td>
                <td>
                    <button class="btn btn-edit" onclick="openEditUser(
                        <?php echo $user['id']; ?>,
                        '<?php echo htmlspecialchars($user['uni_id']); ?>',
                        '<?php echo htmlspecialchars($user['fullname']); ?>',
                        '<?php echo $user['role']; ?>'
                    )">Edit</button>

                    <?php if ($user['role'] !== 'admin'): ?>
                    <form method="post" action="../controller/admin-handler.php" style="display:inline;"
                          onsubmit="return confirm('Delete this user permanently?');">
                        <input type="hidden" name="action" value="delete_user">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" class="btn btn-delete">Delete</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>

    <!-- ==================== TAB 4: ASSIGN SUPERVISOR ==================== -->
    <?php if ($activeTab === 'assign'): ?>
    <div class="card">
        <h2>Assign Supervisor to Rooms</h2>
        <p>You can change the supervisor of an existing room from the <strong>Allocate Rooms</strong> tab (Edit button).</p>

        <h3>Current Room → Supervisor Mapping</h3>
        <table>
            <tr>
                <th>Room</th>
                <th>Supervisor Name</th>
                <th>Supervisor ID</th>
            </tr>
            <?php foreach ($allRooms as $room): ?>
            <tr>
                <td><?php echo htmlspecialchars($room['name']); ?></td>
                <td><?php echo htmlspecialchars($room['supervisor_name'] ?? 'Not Assigned'); ?></td>
                <td><?php echo htmlspecialchars($room['supervisor_uni_id'] ?? '-'); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- ==================== EDIT ROOM MODAL ==================== -->
<div id="editRoomModal" class="modal">
    <div class="modal-content">
        <h3>Edit Room</h3>
        <form method="post" action="../controller/admin-handler.php">
            <input type="hidden" name="action" value="update_room">
            <input type="hidden" name="room_id" id="edit_room_id">
            <div class="form-row">
                <label>Room Name:</label>
                <input type="text" name="name" id="edit_room_name" required>
            </div>
            <div class="form-row">
                <label>Capacity:</label>
                <input type="number" name="capacity" id="edit_room_capacity" required min="1">
            </div>
            <div class="form-row">
                <label>Supervisor:</label>
                <select name="supervisor_id" id="edit_room_supervisor" required>
                    <?php foreach ($allSupervisors as $sup): ?>
                        <option value="<?php echo $sup['id']; ?>">
                            <?php echo htmlspecialchars($sup['fullname'] . " (" . $sup['uni_id'] . ")"); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update Room</button>
            <button type="button" class="btn" onclick="closeModal('editRoomModal')">Cancel</button>
        </form>
    </div>
</div>

<!-- ==================== EDIT USER MODAL ==================== -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <h3>Edit User</h3>
        <form method="post" action="../controller/admin-handler.php">
            <input type="hidden" name="action" value="update_user">
            <input type="hidden" name="user_id" id="edit_user_id">
            <div class="form-row">
                <label>University ID:</label>
                <input type="text" name="uni_id" id="edit_user_uni_id" required>
            </div>
            <div class="form-row">
                <label>Full Name:</label>
                <input type="text" name="fullname" id="edit_user_fullname" required>
            </div>
            <div class="form-row">
                <label>Role:</label>
                <select name="role" id="edit_user_role" required>
                    <option value="admin">Admin</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                </select>
            </div>
            <div class="form-row">
                <label>New Password:</label>
                <input type="password" name="password" placeholder="Leave blank to keep current">
            </div>
            <button type="submit" class="btn btn-success">Update User</button>
            <button type="button" class="btn" onclick="closeModal('editUserModal')">Cancel</button>
        </form>
    </div>
</div>

<script>
function openEditRoom(id, name, capacity, supervisorId) {
    document.getElementById('edit_room_id').value = id;
    document.getElementById('edit_room_name').value = name;
    document.getElementById('edit_room_capacity').value = capacity;
    document.getElementById('edit_room_supervisor').value = supervisorId;
    document.getElementById('editRoomModal').style.display = 'block';
}

function openEditUser(id, uniId, fullname, role) {
    document.getElementById('edit_user_id').value = id;
    document.getElementById('edit_user_uni_id').value = uniId;
    document.getElementById('edit_user_fullname').value = fullname;
    document.getElementById('edit_user_role').value = role;
    document.getElementById('editUserModal').style.display = 'block';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>
</body>
</html>
