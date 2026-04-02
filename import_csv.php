<?php
/*
 * import_csv.php
 * Admin page: upload a CSV file to bulk-add or update products in MySQL.
 * Place this file in your project root (same level as products.php).
 *
 * HOW IT WORKS:
 *   1. Admin uploads a CSV file via this page.
 *   2. PHP reads each row and runs INSERT ... ON DUPLICATE KEY UPDATE.
 *      - If product_id already exists  → update name, price, image, category, brand, description.
 *      - If product_id is new          → insert as a new product.
 *   3. A summary shows how many rows were inserted vs updated.
 *
 * CSV FORMAT (first row must be the header):
 *   product_id, name, price, image, category, brand, description
 *
 * SECURITY: Only logged-in admins should be able to access this page.
 */

session_start();
require_once 'php/db_connect.php';

// ── Admin guard ───────────────────────────────────────────────────────────────
// Adjust this check to match how your project identifies admins.
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$message    = '';
$msgType    = '';   // 'success' or 'danger'
$previewRows = [];  // rows parsed from CSV for preview

// ── Handle form submission ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {

    $file = $_FILES['csv_file'];

    // Basic validation
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = 'Upload failed. Please try again.';
        $msgType = 'danger';
    } elseif ($ext !== 'csv') {
        $message = 'Invalid file type. Please upload a .csv file.';
        $msgType = 'danger';
    } else {

        $handle = fopen($file['tmp_name'], 'r');
        if ($handle === false) {
            $message = 'Could not open the uploaded file.';
            $msgType = 'danger';
        } else {
            $inserted = 0;
            $updated  = 0;
            $skipped  = 0;
            $rowNum   = 0;

            // Prepare the upsert statement
            $sql = "INSERT INTO maison_reluxe_products
                        (product_id, name, price, image, category, brand, description)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                        name        = VALUES(name),
                        price       = VALUES(price),
                        image       = VALUES(image),
                        category    = VALUES(category),
                        brand       = VALUES(brand),
                        description = VALUES(description)";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                $message = 'Database error: ' . htmlspecialchars($conn->error);
                $msgType = 'danger';
            } else {

                while (($row = fgetcsv($handle, 0, ',')) !== false) {
                    $rowNum++;

                    // Skip the header row
                    if ($rowNum === 1) {
                        continue;
                    }

                    // Expect exactly 7 columns
                    if (count($row) < 7) {
                        $skipped++;
                        continue;
                    }

                    $productId   = (int) trim($row[0]);
                    $name        = trim($row[1]);
                    $price       = (float) trim($row[2]);
                    $image       = trim($row[3]);
                    $category    = trim($row[4]);
                    $brand       = trim($row[5]);
                    $description = trim($row[6]);

                    // Skip rows with missing required fields
                    if ($productId <= 0 || $name === '' || $price <= 0) {
                        $skipped++;
                        continue;
                    }

                    $stmt->bind_param(
                        'isdssss',
                        $productId,
                        $name,
                        $price,
                        $image,
                        $category,
                        $brand,
                        $description
                    );
                    $stmt->execute();

                    // affected_rows: 1 = insert, 2 = update, 0 = no change
                    if ($stmt->affected_rows === 1) {
                        $inserted++;
                    } elseif ($stmt->affected_rows === 2) {
                        $updated++;
                    }
                }

                $stmt->close();
                fclose($handle);

                $message = "Import complete — {$inserted} product(s) added, {$updated} updated, {$skipped} skipped.";
                $msgType = 'success';
            }
        }
    }
}

// ── Load current products for preview table ───────────────────────────────────
$result = $conn->query("SELECT product_id, name, price, category, brand,deleted FROM maison_reluxe_products ORDER BY product_id");
$currentProducts = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $currentProducts[] = $row;
    }
}
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<main class="container my-5">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h1 class="mb-1" style="font-family:'Georgia',serif; font-weight:400;">Product CSV Import</h1>
            <p class="text-muted mb-4">Upload a CSV file to add new products or update existing ones in the database.</p>

            <?php if ($message !== ''): ?>
                <div class="alert alert-<?= htmlspecialchars($msgType) ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Upload form -->
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Upload CSV File</h5>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="csv_file" class="form-label">Select CSV file</label>
                            <input
                                type="file"
                                class="form-control"
                                id="csv_file"
                                name="csv_file"
                                accept=".csv"
                                required>
                            <div class="form-text">
                                File must be <code>.csv</code> with columns in this exact order:<br>
                                <code>product_id, name, price, image, category, brand, description</code>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark">Import Products</button>
                        <a href="products_sample.csv" download class="btn btn-outline-secondary ms-2">
                            Download Sample CSV
                        </a>
                    </form>
                </div>
            </div>

            <!-- CSV format guide -->
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">CSV Format Guide</h5>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Column</th>
                                <th>Type</th>
                                <th>Example</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>product_id</td>
                                <td>Integer</td>
                                <td>40</td>
                                <td>Must be unique. Existing ID = update; new ID = insert.</td>
                            </tr>
                            <tr>
                                <td>name</td>
                                <td>Text</td>
                                <td>Rolex Submariner</td>
                                <td>Required</td>
                            </tr>
                            <tr>
                                <td>price</td>
                                <td>Decimal</td>
                                <td>20500.00</td>
                                <td>Required. No currency symbols.</td>
                            </tr>
                            <tr>
                                <td>image</td>
                                <td>Text</td>
                                <td>watches/rolex1.jpeg</td>
                                <td>Path relative to <code>images/</code> folder.</td>
                            </tr>
                            <tr>
                                <td>category</td>
                                <td>Text</td>
                                <td>Watches</td>
                                <td>Must match existing categories exactly.</td>
                            </tr>
                            <tr>
                                <td>brand</td>
                                <td>Text</td>
                                <td>Rolex</td>
                                <td>Required</td>
                            </tr>
                            <tr>
                                <td>description</td>
                                <td>Text</td>
                                <td>"A legendary..."</td>
                                <td>Wrap in quotes if it contains commas.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Current products table -->
    <h2 class="mb-3" style="font-family:'Georgia',serif; font-weight:400; font-size:1.4rem;">
        Current Products in Database (<?= count($currentProducts) ?>)
    </h2>
    <div class="table-responsive shadow-sm rounded mb-5">
        <table class="table table-hover table-bordered mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price (SGD)</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Availablity</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($currentProducts)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No products in database yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($currentProducts as $p): ?>
                        <tr>
                            <td><?= (int)$p['product_id'] ?></td>
                            <td>
                                <a href="<?= htmlspecialchars($rootPath) ?>/product_detail.php?id=<?= (int)$p['product_id'] ?>" class="product-link">
                                    <?= htmlspecialchars($p['name']) ?>
                                </a>
                            </td>
                            <td>$<?= number_format((float)$p['price'], 2) ?></td>
                            <td><?= htmlspecialchars($p['category']) ?></td>
                            <td><?= htmlspecialchars($p['brand']) ?></td>
                            <td><span class="availability-badge <?= $p['deleted'] == 0 ? 'available' : 'unavailable' ?>">
                                <?= $p['deleted'] == 0 ? 'Available' : 'Unavailable' ?>
                            </span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>
<style>
    .product-link {
        color: inherit;
        /* Use text color of surrounding element */
        text-decoration: none;
        /* Remove underline */
    }

    .product-link:hover {
        text-decoration: underline;
        /* Optional: underline on hover for usability */
    }

    .availability-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 25px;
        color: white;
        text-align: center;
    }

    .availability-badge.available {
        background-color: green;
    }

    .availability-badge.unavailable {
        background-color: red;
    }
</style>
<?php include 'includes/footer.php'; ?>