<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Minerva</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar h1 {
            font-size: 1.8rem;
        }

        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar .logout-btn {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .navbar .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .welcome-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .welcome-section h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .welcome-section p {
            color: #666;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background-color: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background-color: #d0d0d0;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            color: #667eea;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }

        .card-content {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .card-stat {
            font-size: 2rem;
            font-weight: bold;
            color: #764ba2;
            margin-bottom: 0.5rem;
        }

        .card-label {
            color: #999;
            font-size: 0.9rem;
        }

        .class-list, .work-list, .submission-list {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .class-list h3, .work-list h3, .submission-list h3 {
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }

        .list-item {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item:hover {
            background-color: #f9f9f9;
        }

        .list-item-info h4 {
            color: #333;
            margin-bottom: 0.3rem;
        }

        .list-item-info p {
            color: #999;
            font-size: 0.9rem;
        }

        .list-item-action {
            display: flex;
            gap: 0.5rem;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-view {
            background-color: #667eea;
            color: white;
        }

        .btn-view:hover {
            background-color: #5568d3;
        }

        .btn-edit {
            background-color: #4caf50;
            color: white;
        }

        .btn-edit:hover {
            background-color: #45a049;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-header h3 {
            margin: 0;
            color: #333;
        }

        .close {
            font-size: 2rem;
            font-weight: bold;
            color: #999;
            cursor: pointer;
            border: none;
            background: none;
            padding: 0;
        }

        .close:hover {
            color: #333;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 600;
        }

        .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .empty-state {
            text-align: center;
            color: #999;
            padding: 2rem;
        }

        .empty-state p {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Minerva</h1>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Teacher'); ?></span>
            <a href="/logout" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Teacher'); ?>!</h2>
            <p>Manage your classes, assignments, and student submissions all in one place.</p>
            <div class="action-buttons">
                <a href="/teacher/class/create" class="btn btn-primary">+ Create New Class</a>
                <a href="/teacher/work/create" class="btn btn-primary">+ Create Assignment</a>
                <a href="/logout" class="btn btn-secondary">Logout</a>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="dashboard-grid">
            <div class="card">
                <div class="card-stat"><?php echo count($classes ?? []); ?></div>
                <div class="card-label">Active Classes</div>
            </div>
            <div class="card">
                <div class="card-stat"><?php echo count($recentWorks ?? []); ?></div>
                <div class="card-label">Recent Assignments</div>
            </div>
            <div class="card">
                <div class="card-stat"><?php echo count($submissions ?? []); ?></div>
                <div class="card-label">Pending Submissions</div>
            </div>
        </div>

        <!-- Classes Section -->
        <div class="class-list">
            <h3>Your Classes</h3>
            <?php if (!empty($classes)): ?>
                <?php foreach ($classes as $class): ?>
                    <div class="list-item">
                        <div class="list-item-info">
                            <h4><?php echo htmlspecialchars($class['name'] ?? 'Unnamed Class'); ?></h4>
                            <p>Class ID: <?php echo htmlspecialchars($class['id'] ?? ''); ?></p>
                        </div>
                        <div class="list-item-action">
                            <a href="/teacher/class/<?php echo $class['id']; ?>" class="btn-small btn-view">View</a>
                            <a href="/teacher/class/<?php echo $class['id']; ?>/attendance" class="btn-small btn-edit">Attendance</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>You haven't created any classes yet.</p>
                    <a href="/teacher/class/create" class="btn btn-primary">Create Your First Class</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Assignments Section -->
        <div class="work-list">
            <h3>Recent Assignments</h3>
            <?php if (!empty($recentWorks)): ?>
                <?php foreach ($recentWorks as $work): ?>
                    <div class="list-item">
                        <div class="list-item-info">
                            <h4><?php echo htmlspecialchars($work['title'] ?? 'Untitled'); ?></h4>
                            <p>Due: <?php echo htmlspecialchars($work['due_date'] ?? 'No due date'); ?></p>
                        </div>
                        <div class="list-item-action">
                            <a href="/teacher/work/<?php echo $work['id']; ?>/submissions" class="btn-small btn-view">View Submissions</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>You haven't created any assignments yet.</p>
                    <a href="/teacher/work/create" class="btn btn-primary">Create Your First Assignment</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pending Submissions Section -->
        <div class="submission-list">
            <h3>Pending Submissions</h3>
            <?php if (!empty($submissions)): ?>
                <?php foreach ($submissions as $submission): ?>
                    <div class="list-item">
                        <div class="list-item-info">
                            <h4><?php echo htmlspecialchars($submission['student_name'] ?? 'Unknown Student'); ?></h4>
                            <p>Assignment: <?php echo htmlspecialchars($submission['work_title'] ?? 'Unknown'); ?> | Submitted: <?php echo htmlspecialchars($submission['submitted_at'] ?? ''); ?></p>
                        </div>
                        <div class="list-item-action">
                            <a href="/teacher/submission/<?php echo $submission['id']; ?>/grade" class="btn-small btn-edit">Grade</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>No pending submissions at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
