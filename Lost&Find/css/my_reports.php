
<?php
require_once "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT * FROM items
     WHERE user_id = ?
     ORDER BY created_at DESC"
);

$stmt->execute([$user_id]);

$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Reports</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>

<div class="main-content">

    <h1>My Reports</h1>

    <p>All items reported by you.</p>

    <br>

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($reports as $item): ?>

                <tr>

                    <td>
                        <?= htmlspecialchars($item['item_name']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($item['category']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($item['status']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($item['location']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($item['report_date']) ?>
                    </td>

                </tr>

            <?php endforeach; ?>

            <?php if (empty($reports)): ?>
                <tr>
                    <td colspan="5">You have no reports yet.</td>
                </tr>
            <?php endif; ?>

            </tbody>

        </table>

    </div>

    <br>

    <a href="dashboard.php">Back to Dashboard</a>

</div>

</body>
</html>
