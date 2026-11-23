
<?php
require_once "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$stmt = $conn->query(
    "SELECT items.*, users.fullname
     FROM items
     JOIN users ON items.user_id = users.id
     ORDER BY items.created_at DESC"
);

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Items - Lost & Find</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .items-grid {
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
            gap:20px;
        }

        .item-card {
            background:white;
            padding:20px;
            border-radius:12px;
        }

        .item-card img {
            width:100%;
            height:180px;
            object-fit:cover;
            border-radius:8px;
        }

        .item-card h3 { margin:12px 0; }
        .item-card p { margin:8px 0; color:#718096; }
    </style>
</head>

<body>

<div class="main-content">

    <h1>All Lost & Found Items</h1>

    <p>Explore all reported items.</p>

    <br>

    <div class="items-grid">

        <?php foreach ($items as $item): ?>

        <div class="item-card">

            <?php if (!empty($item['image'])): ?>
                <img src="uploads/<?= htmlspecialchars($item['image']) ?>"
                     alt="Item image">
            <?php endif; ?>

            <h3><?= htmlspecialchars($item['item_name']) ?></h3>

            <p>Category:
                <?= htmlspecialchars($item['category']) ?>
            </p>

            <p>Type:
                <?= htmlspecialchars($item['status']) ?>
            </p>

            <p>Color:
                <?= htmlspecialchars($item['color'] ?? '') ?>
            </p>

            <p>Location:
                <?= htmlspecialchars($item['location']) ?>
            </p>

            <p>Reported By:
                <?= htmlspecialchars($item['fullname']) ?>
            </p>

            <p>
                <?= htmlspecialchars($item['description'] ?? '') ?>
            </p>

        </div>

        <?php endforeach; ?>

        <?php if (empty($items)): ?>
            <p>No items reported yet.</p>
        <?php endif; ?>

    </div>

    <br>

    <a href="dashboard.php">Back to Dashboard</a>

</div>

</body>
</html>
