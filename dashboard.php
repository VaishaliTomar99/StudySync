<?php

session_start();

include 'includes/db.php';
include 'includes/ai-helper.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
}

$user_id = $_SESSION['user_id'];

$totalTasks = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tasks WHERE user_id='$user_id'"));

$completedTasks = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tasks WHERE user_id='$user_id' AND status='Completed'"));

$pendingTasks = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tasks WHERE user_id='$user_id' AND status='Pending'"));

$inProgressTasks = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tasks WHERE user_id='$user_id' AND status='In Progress'"));

// Calculate productivity percentage
$productivity = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

// Get AI suggestion
$aiSuggestion = getAISuggestion($pendingTasks, $completedTasks, $productivity);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<title>Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="stylesheet" href="assets/css/style.css">

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 0;
        font-family: 'Poppins', sans-serif;
        display: flex;
    }

    /* Sidebar Navigation */
    .sidebar {
        width: 250px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px 20px;
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        color: white;
        margin-bottom: 40px;
        font-weight: 700;
        font-size: 18px;
    }

    .sidebar-brand i {
        font-size: 28px;
    }

    .sidebar-nav a {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 15px;
        margin-bottom: 10px;
        border-radius: 10px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .sidebar-nav a:hover,
    .sidebar-nav a.active {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        transform: translateX(5px);
    }

    .sidebar-nav a i {
        font-size: 20px;
        width: 24px;
    }

    .sidebar-logout {
        position: absolute;
        bottom: 30px;
        left: 20px;
        right: 20px;
    }

    .sidebar-logout a {
        width: 100%;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        display: flex;
    }

    .sidebar-logout a:hover {
        background: white;
        color: #667eea !important;
    }
    /* Main Content */
    .main-content {
        margin-left: 250px;
        flex: 1;
        padding: 30px;
        width: calc(100% - 250px);
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        color: white;
    }

    .dashboard-header h1 {
        font-weight: 700;
        font-size: 32px;
        margin: 0;
    }

    .dashboard-header p {
        color: rgba(255, 255, 255, 0.8);
        margin: 5px 0 0 0;
    }

    .header-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .btn-dark-toggle {
        background: white;
        color: #667eea;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-dark-toggle:hover {
        background: #f3f4f6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .stats-icon {
        font-size: 40px;
        margin-bottom: 12px;
        display: inline-block;
    }

    .stats-icon.total {
        color: #667eea;
    }

    .stats-icon.completed {
        color: #10b981;
    }

    .stats-icon.pending {
        color: #f59e0b;
    }

    .stats-icon.productivity {
        color: #3b82f6;
    }

    .stats-value {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        margin: 8px 0;
    }

    .stats-label {
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
    }

    .stats-period {
        color: #9ca3af;
        font-size: 12px;
        margin-top: 6px;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .btn-custom {
        background: white;
        color: #667eea;
        border: none;
        border-radius: 10px;
        padding: 12px 28px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-custom:hover {
        background: #f3f4f6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .tasks-section {
        background: white;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-top: 30px;
    }

    .tasks-section h3 {
        color: #1f2937;
        font-weight: 700;
        margin-bottom: 20px;
        font-size: 20px;
    }

    .tasks-section h4 {
        color: #1f2937;
        font-weight: 700;
        margin-bottom: 15px;
        font-size: 16px;
    }

    .form-select {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        padding: 10px 15px;
        font-weight: 500;
        color: #374151;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23667eea' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    }

    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .task-table {
        color: #1f2937;
        margin-bottom: 0;
    }

    .task-table thead th {
        border: none;
        border-bottom: 2px solid #e5e7eb;
        font-weight: 700;
        color: #6b7280;
        padding: 16px 12px;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }

    .task-table tbody td {
        border: none;
        border-bottom: 1px solid #f3f4f6;
        padding: 16px 12px;
        vertical-align: middle;
    }

    .task-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .task-table tbody tr:hover {
        background: #fafbfc;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-pending {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-completed {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-in-progress {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-priority {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-priority-high {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-priority-medium {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-priority-low {
        background: #d1fae5;
        color: #065f46;
    }

    .action-btn {
        padding: 6px 10px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .action-btn-edit {
        background: #fbbf24;
        color: white;
    }

    .action-btn-edit:hover {
        background: #f59e0b;
    }

    .action-btn-delete {
        background: #ef4444;
        color: white;
    }

    .action-btn-delete:hover {
        background: #dc2626;
    }

    .ai-suggestion-box {
        background: linear-gradient(135deg, #f3f0ff 0%, #ede9fe 100%);
        border-radius: 12px;
        padding: 20px;
        margin-top: 15px;
    }

    .ai-suggestion-box p {
        color: #333;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .upcoming-deadline {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .upcoming-deadline:last-child {
        border-bottom: none;
    }

    .deadline-date {
        color: #ef4444;
        font-weight: 700;
        font-size: 12px;
    }

    .deadline-title {
        color: #1f2937;
        font-weight: 600;
        font-size: 14px;
        margin-top: 4px;
    }

    /* Dark Mode Styles */
    body.dark-mode {
        background: #0f172a;
    }

    .dark-mode .sidebar {
        background: linear-gradient(135deg, #1e1e2e 0%, #2c2c3e 100%);
    }

    .dark-mode .main-content {
        background: #0f172a;
    }

    .dark-mode .dashboard-header h1,
    .dark-mode .dashboard-header p {
        color: white;
    }

    .dark-mode .stats-card {
        background: #1e1e2e;
        color: white;
    }

    .dark-mode .stats-value {
        color: #e0e0e0;
    }

    .dark-mode .stats-label,
    .dark-mode .stats-period {
        color: #b0b0b0;
    }

    .dark-mode .btn-dark-toggle {
        background: white;
        color: #667eea;
        font-weight: 600;
    }

    .dark-mode .btn-custom {
        background: white;
        color: #667eea;
        font-weight: 600;
    }

    .dark-mode .tasks-section {
        background: #1e1e2e;
        color: #e0e0e0;
    }

    .dark-mode .tasks-section h3,
    .dark-mode .tasks-section h4 {
        color: #ffffff;
    }

    .dark-mode .task-table thead th {
        color: #b0b0b0;
        border-bottom-color: #3a3a4e;
    }

    .dark-mode .task-table tbody td {
        color: #e0e0e0;
        border-bottom-color: #3a3a4e;
    }

    .dark-mode .task-table tbody tr:hover {
        background: #2c2c3e;
    }

    .dark-mode .form-select {
        background: #2c2c3e;
        color: #e0e0e0;
        border-color: #3a3a4e;
    }

    .dark-mode .form-select option {
        background: #1e1e2e;
        color: #e0e0e0;
    }

    .dark-mode .ai-suggestion-box {
        background: #2c2c3e;
    }

    .dark-mode .ai-suggestion-box p {
        color: #e0e0e0;
    }

    .dark-mode .btn-primary {
        background: #667eea;
        border-color: #667eea;
    }

    .dark-mode .btn-primary:hover {
        background: #764ba2;
    }

    .dark-mode .upcoming-deadline {
        border-bottom-color: #3a3a4e;
    }

    .dark-mode .deadline-title {
        color: #e0e0e0;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 0;
            overflow: hidden;
            transition: width 0.3s ease;
        }

        .sidebar.active {
            width: 250px;
            z-index: 1000;
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }

        .stats-card {
            margin-bottom: 15px;
        }

        .tasks-section {
            margin-top: 20px;
        }
    }

</style>

</head>

<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-book"></i>
            <span>StudySync AI</span>
        </div>

        <nav class="sidebar-nav">
            <a href="dashboard.php" class="active">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="add-task.php">
                <i class="fas fa-plus-circle"></i> Add Task
            </a>
            <a href="analytics.php">
                <i class="fas fa-chart-line"></i> Analytics
            </a>
            <a href="pomodoro.php">
                <i class="fas fa-clock"></i> Pomodoro Timer
            </a>
            <a href="upload-notes.php">
                <i class="fas fa-sticky-note"></i> Notes
            </a>
            <a href="profile.php">
                <i class="fas fa-user"></i>
                Profile
            </a>

            <a href="chatbot.php">
                <i class="fas fa-robot"></i>
                AI Chatbot
            </a>

            <a href="calendar.php">
                <i class="fas fa-calendar"></i>
                Calendar
            </a>
        </nav>

        <div class="sidebar-logout">
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <div class="dashboard-header">
            <div>
                <h1>Welcome back, <?php echo $_SESSION['user_name']; ?>! 👋</h1>
                <p>Let's make today productive</p>
            </div>

            <div class="header-actions">

            <!-- PROFILE CARD -->

                <!-- PROFILE CARD -->

                <div style="
                    display:flex;
                    align-items:center;
                    gap:15px;
                    background:white;
                    padding:10px 18px;
                    border-radius:60px;
                    box-shadow:0 4px 15px rgba(0,0,0,0.12);
                ">

                <?php
                $userQuery = mysqli_query($conn,
                "SELECT avatar FROM users WHERE id='$user_id'");
                $userData = mysqli_fetch_assoc($userQuery);
                if(!empty($userData['avatar'])){
                    $avatar = $userData['avatar'];
                }else{
                    $avatar = "uploads/avatars/default.png";
                }
                ?>

                <img src="<?php echo $avatar; ?>"
                    alt="Profile"
                    style="
                        width:60px;
                        height:60px;
                        border-radius:50%;
                        object-fit:cover;
                        border:3px solid #667eea;
                        display:block;
                    ">

                <div style="
                    font-weight:700;
                    color:#1f2937;
                    font-size:16px;
                    white-space:nowrap;
                ">
                    <?php echo $_SESSION['user_name']; ?>
                </div>

            </div>

    <!-- DARK MODE BUTTON -->

                <button id="darkToggle" class="btn-dark-toggle">
                    🌙 Dark Mode
                </button>

            </div>
        </div>

        <div class="row g-4">

            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <i class="fas fa-tasks stats-icon total"></i>
                    <div class="stats-value"><?php echo $totalTasks; ?></div>
                    <div class="stats-label">Total Tasks</div>
                    <div class="stats-period">This Month</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <i class="fas fa-check-circle stats-icon completed"></i>
                    <div class="stats-value"><?php echo $completedTasks; ?></div>
                    <div class="stats-label">Completed</div>
                    <div class="stats-period">This Month</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <i class="fas fa-clock stats-icon pending"></i>
                    <div class="stats-value"><?php echo $pendingTasks; ?></div>
                    <div class="stats-label">Pending</div>
                    <div class="stats-period">This Month</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <i class="fas fa-chart-pie stats-icon productivity"></i>
                    <div class="stats-value"><?php echo $productivity; ?>%</div>
                    <div class="stats-label">Productivity</div>
                    <div class="stats-period">This Week</div>
                </div>
            </div>

        </div>

        <div class="action-buttons">

            <a href="add-task.php" class="btn btn-custom">
                <i class="fas fa-plus"></i>
                Add New Task
            </a>

            <a href="analytics.php" class="btn btn-custom">
                <i class="fas fa-chart-line"></i>
                View Analytics
            </a>

            <a href="export-tasks.php"
               class="btn btn-custom">


                <i class="fas fa-file-csv"></i>
                Export CSV

            </a>

            <a href="generate-report.php"
                class="btn btn-custom">

                <i class="fas fa-file-pdf"></i>
                Download PDF

            </a>

        </div>

        <div class="row mt-4">
            <div class="col-lg-9">
                <div class="tasks-section">

                    <h3><i class="fas fa-list"></i> Your Tasks</h3>

                    <select id="statusFilter" class="form-select mb-3">
                        <option value="all">All Tasks</option>
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                        <option value="In Progress">In Progress</option>
                    </select>

                    <table class="table task-table">

                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Task</th>
                                <th>Deadline</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody id="taskTableBody">

                        <?php

                        $tasks = mysqli_query($conn,
                        "SELECT * FROM tasks WHERE user_id='$user_id' ORDER BY deadline ASC");

                        while($row = mysqli_fetch_assoc($tasks)){
                            $statusClass = 'badge-' . strtolower(str_replace(' ', '-', $row['status']));
                            $priorityClass = 'badge-priority-' . strtolower($row['priority']);
                        ?>

                            <tr class="task-row" data-status="<?php echo $row['status']; ?>">
                                <td><span class="badge bg-light text-dark"><?php echo $row['subject']; ?></span></td>
                                <td><?php echo $row['task_title']; ?></td>
                                <td><?php echo date('d M Y', strtotime($row['deadline'])); ?></td>
                                <td><span class="badge-priority <?php echo $priorityClass; ?>"><?php echo ucfirst($row['priority']); ?></span></td>
                                <td><span class="badge-status <?php echo $statusClass; ?>"><?php echo $row['status']; ?></span></td>
                                <td>
                                    <a href="edit-task.php?id=<?php echo $row['id']; ?>" class="action-btn action-btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete-task.php?id=<?php echo $row['id']; ?>" class="action-btn action-btn-delete" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>
            </div>

            <div class="col-lg-3">

                <!-- AI Suggestion Card -->
                <div class="tasks-section mb-4">
                    <h4>🧠 AI Suggestion</h4>
                    <div class="ai-suggestion-box">
                        <p><?php echo $aiSuggestion; ?></p>
                        <button class="btn btn-primary w-100" style="border-radius: 8px; padding: 10px 20px; font-weight: 600;">
                            View Suggestions
                        </button>
                    </div>
                </div>

                <!-- Upcoming Deadlines -->
                <div class="tasks-section">
                    <h4>📅 Upcoming Deadlines</h4>

                    <?php

                    $upcoming = mysqli_query($conn,
                    "SELECT * FROM tasks WHERE user_id='$user_id' ORDER BY deadline ASC LIMIT 3");

                    $hasUpcoming = false;
                    while($task = mysqli_fetch_assoc($upcoming)){
                        $hasUpcoming = true;
                    ?>

                    <div class="upcoming-deadline">
                        <div>
                            <div class="deadline-date">
                                <?php echo date('d M', strtotime($task['deadline'])); ?>
                            </div>
                            <div class="deadline-title">
                                <?php echo $task['task_title']; ?>
                            </div>
                        </div>
                        <span class="badge badge-priority-<?php echo strtolower($task['priority']); ?>">
                            <?php echo ucfirst($task['priority']); ?>
                        </span>
                    </div>

                    <?php } ?>

                    <?php if(!$hasUpcoming) { ?>
                    <p style="color: #9ca3af; text-align: center; padding: 20px;">
                        No upcoming deadlines
                    </p>
                    <?php } ?>

                </div>
            </div>
        </div>

    </div>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert">
            <div class="toast-header">
                <strong class="me-auto">StudySync AI</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Welcome back! Stay productive today 🚀
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Dark Mode Toggle
        const darkToggle = document.getElementById('darkToggle');
        const body = document.body;

        if(localStorage.getItem('darkMode') === 'enabled'){
            body.classList.add('dark-mode');
            darkToggle.textContent = '☀️ Light Mode';
        }

        darkToggle.addEventListener('click', function(){
            body.classList.toggle('dark-mode');
            
            if(body.classList.contains('dark-mode')){
                localStorage.setItem('darkMode', 'enabled');
                darkToggle.textContent = '☀️ Light Mode';
            } else {
                localStorage.setItem('darkMode', 'disabled');
                darkToggle.textContent = '🌙 Dark Mode';
            }
        });

        // Toast Notification
        const toastLiveExample = document.getElementById('liveToast');
        const toast = new bootstrap.Toast(toastLiveExample);
        toast.show();

        // Task Filter
        const filter = document.getElementById("statusFilter");

        if(filter){
            filter.addEventListener("change", function(){
                let value = this.value;
                let rows = document.querySelectorAll(".task-row");

                rows.forEach(row => {
                    if(value === "all"){
                        row.style.display = "";
                    }else{
                        let status = row.getAttribute("data-status");
                        row.style.display = status === value ? "" : "none";
                    }
                });
            });
        }
    </script>

</body>
</html>