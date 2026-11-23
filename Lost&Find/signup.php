
<?php
require_once "config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: signup.html");
    exit;
}

$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (
    $fullname === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($password) < 8
) {
    exit("Please enter valid information.");
}

$check = $conn->prepare(
    "SELECT id FROM users WHERE email = ?"
);

$check->execute([$email]);

if ($check->fetch()) {
    exit("Email already exists. Please login.");
}

$hashed_password = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$stmt = $conn->prepare(
    "INSERT INTO users (fullname,email,password)
     VALUES (?,?,?)"
);

$stmt->execute([
    $fullname,
    $email,
    $hashed_password
]);

header("Location: login.html?registered=1");
exit;
?>
