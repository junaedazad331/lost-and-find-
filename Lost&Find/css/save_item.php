
<?php
require_once "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: report.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$item_name = trim($_POST['item_name'] ?? '');
$category = trim($_POST['category'] ?? '');
$status = $_POST['status'] ?? '';
$color = trim($_POST['color'] ?? '');
$location = trim($_POST['location'] ?? '');
$report_date = $_POST['report_date'] ?? '';
$description = trim($_POST['description'] ?? '');

$allowed_status = ['Lost', 'Found'];

if (
    $item_name === '' ||
    $category === '' ||
    $location === '' ||
    !in_array($status, $allowed_status, true)
) {
    exit("Invalid item information.");
}

$date = DateTime::createFromFormat('!Y-m-d', $report_date);

if (
    !$date ||
    $date->format('Y-m-d') !== $report_date ||
    $date > new DateTime('today')
) {
    exit("Invalid report date.");
}

// Image upload
$image_name = null;

if (isset($_FILES['image']) &&
    $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK ||
        $_FILES['image']['size'] > 2 * 1024 * 1024) {
        exit("Image upload failed. Maximum size is 2MB.");
    }

    $tmp = $_FILES['image']['tmp_name'];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    if (!isset($allowed[$mime])) {
        exit("Only JPG, PNG and WEBP images are allowed.");
    }

    $folder = __DIR__ . "/uploads/";

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $image_name = bin2hex(random_bytes(16))
                . "." . $allowed[$mime];

    if (!move_uploaded_file($tmp, $folder . $image_name)) {
        exit("Could not upload image.");
    }
}

$stmt = $conn->prepare(
    "INSERT INTO items
    (user_id, item_name, category, status, color,
     location, report_date, description, image)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

$stmt->execute([
    $user_id,
    $item_name,
    $category,
    $status,
    $color,
    $location,
    $report_date,
    $description,
    $image_name
]);

$new_item_id = $conn->lastInsertId();

// Automatic matching
require_once "matching.php";

findMatches($conn, $new_item_id);

header("Location: dashboard.php?report=success");
exit;
?>
