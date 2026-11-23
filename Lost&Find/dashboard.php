

<?php
require_once "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['fullname'];


$stmt = $conn->prepare(
    "SELECT COUNT(*) FROM items
     WHERE user_id = ? AND status = 'Lost'"
);
$stmt->execute([$user_id]);
$total_lost = $stmt->fetchColumn();


$stmt = $conn->prepare(
    "SELECT COUNT(*) FROM items
     WHERE user_id = ? AND status = 'Found'"
);
$stmt->execute([$user_id]);
$total_found = $stmt->fetchColumn();


$stmt = $conn->prepare(
    "SELECT COUNT(*) FROM matches m
     JOIN items l ON m.lost_item_id = l.id
     JOIN items f ON m.found_item_id = f.id
     WHERE (l.user_id = ? OR f.user_id = ?)
     AND m.match_status != 'Rejected'"
);
$stmt->execute([$user_id, $user_id]);
$total_matches = $stmt->fetchColumn();

// Recent Reports
$stmt = $conn->prepare(
    "SELECT * FROM items
     WHERE user_id = ?
     ORDER BY created_at DESC
     LIMIT 5"
);
$stmt->execute([$user_id]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Lost & Find</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="layout">

   
    <aside class="sidebar">

        <h2 class="logo">Lost<span>&</span>Find</h2>

        <p class="menu-title">MENU</p>

        <nav>
           <a href="dashboard.php" class="active">Dashboard</a>

           <a href="report.php">Report Item</a>

           <a href="all_items.php">All Items</a>

           <a href="my_reports.php">My Reports</a>

           <a href="logout.php">Logout</a>
        </nav>

        <div class="sidebar-bottom">
            <a href="logout.php">Logout</a>
        </div>

    </aside>

 
    <main class="main-content">

        <header class="topbar">
            <div>
                <h2>Dashboard</h2>
                <p>Manage your lost and found reports</p>
            </div>

            <div class="profile">
                <div class="avatar">U</div>
                <span>User Account</span>
            </div>
        </header>

        <section class="welcome">
            <div>
                <h1>Welcome Back, User!</h1>
                <p>Let's help people find their lost belongings.</p>
            </div>
        </section>

       
        <section class="stats">

            <div class="stat-card">
                <div class="stat-icon blue">L</div>
                <h3>Total Lost Items</h3>
                <h1>12</h1>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">F</div>
                <h3>Total Found Items</h3>
                <h1>08</h1>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">M</div>
                <h3>Possible Matches</h3>
                <h1>05</h1>
            </div>

        </section>

       
        <section class="actions">

            <h2>Quick Actions</h2>

            <div class="action-grid">

                <a href="report.html?type=Lost" class="action-card">
                    <div class="action-icon lost">+</div>
                    <h3>Report Lost Item</h3>
                    <p>Report something you have lost.</p>
                    <span>Report Now →</span>
                </a>

                <a href="report.html?type=Found" class="action-card">
                    <div class="action-icon found">+</div>
                    <h3>Report Found Item</h3>
                    <p>Report something you have found.</p>
                    <span>Report Now →</span>
                </a>

            </div>

        </section>

      
        <section class="recent">

            <h2>Recent Reports</h2>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Black Wallet</td>
                            <td>Wallet</td>
                            <td>Lost</td>
                            <td><span class="badge pending">Pending</span></td>
                        </tr>

                        <tr>
                            <td>Samsung Phone</td>
                            <td>Electronics</td>
                            <td>Found</td>
                            <td><span class="badge matched">Matched</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </section>

    </main>

</div>

</body>
</html>
