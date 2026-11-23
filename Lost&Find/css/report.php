
<?php
require_once "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Item | Lost & Find</title>
    <link rel="stylesheet" href="css/report.css">
</head>
<body>

<div class="report-container">

    <header class="report-header">
        <a href="dashboard.html" class="back">← Dashboard</a>
        <h1>Report an Item</h1>
        <p>Help us connect lost belongings with their owners.</p>
    </header>

    <main class="report-card">

        <h2>Item Information</h2>
        <p class="subtitle">Please provide accurate information.</p>

        <form action="save_item.php" method="POST"
              enctype="multipart/form-data">

            <label>What do you want to report?</label>

            <select name="status" required>
                <option value="">Select Report Type</option>
                <option value="Lost">I Lost an Item</option>
                <option value="Found">I Found an Item</option>
            </select>

            <label>Item Name</label>
            <input type="text" name="item_name"
                   placeholder="Example: Black Wallet" required>

            <label>Category</label>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="Electronics">Electronics</option>
                <option value="Mobile">Mobile Phone</option>
                <option value="Wallet">Wallet</option>
                <option value="Bag">Bag</option>
                <option value="Documents">Documents</option>
                <option value="Jewelry">Jewelry</option>
                <option value="Clothing">Clothing</option>
                <option value="Keys">Keys</option>
                <option value="Other">Other</option>
            </select>

            <div class="form-row">

                <div>
                    <label>Item Color</label>
                    <input type="text" name="color"
                           placeholder="Example: Black">
                </div>

                <div>
                    <label>Date</label>
                    <input type="date" name="report_date" required>
                </div>

            </div>

            <label>Location</label>
            <input type="text" name="location"
                   placeholder="Where was it lost or found?" required>

            <label>Item Description</label>
            <textarea name="description"
                      placeholder="Describe the item in detail..."
                      rows="5"></textarea>

            <label>Upload Item Image</label>
            <input type="file" name="image"
                   accept="image/jpeg,image/png,image/webp">

            <div class="notice">
                Please provide correct details.
                Our system will automatically search for
                possible matching reports.
            </div>

            <button type="submit">Submit Report</button>

        </form>

    </main>

</div>

</body>
</html>
