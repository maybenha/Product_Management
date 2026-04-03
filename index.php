<?php
session_start();

// Product Class Definition
class Product {
    public $id;
    public $code;
    public $name;
    public $category;
    public $description;
    public $price;
    public $quantity;
    public $image;
    public $status;
    public $createdAt;

    public function __construct($id, $code, $name, $category, $description, $price, $quantity, $image, $status) {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->category = $category;
        $this->description = $description;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->image = $image;
        $this->status = $status;
        $this->createdAt = date('Y-m-d H:i:s');
    }
}

// Delete Logic
if (isset($_GET['delete'])) {
    $idToDelete = $_GET['delete'];
    foreach ($_SESSION['products'] as $key => $product) {
        if ($product->id == $idToDelete) {
            unset($_SESSION['products'][$key]);
        }
    }
    header("Location: index.php");
    exit();
}

$products = isset($_SESSION['products']) ? $_SESSION['products'] : [];
$viewId = $_GET['view'] ?? null;
$selectedProduct = null;
if ($viewId) {
    foreach ($products as $p) {
        if ($p->id == $viewId) $selectedProduct = $p;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <style>
        :root { 
            --primary: #0c3be4; 
            --white: #ffffff; 
            --sidebar-width: 450px;
        }
        
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            background: var(--white); 
            color: #000;
            margin: 0; 
            display: flex;
            overflow-x: hidden;
        }

        /* Layout */
        .main-content {
            flex: 1;
            padding: 40px;
            transition: margin-right 0.3s ease;
            margin-right: <?= $selectedProduct ? 'var(--sidebar-width)' : '0' ?>;
        }

        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 40px; 
            border-bottom: 2px solid var(--primary);
            padding-bottom: 20px;
        }

        h1 { color: var(--primary); margin: 0; font-weight: 800; text-transform: uppercase; letter-spacing: -1px; }
        
        /* Buttons */
        .btn { 
            padding: 12px 24px; 
            text-decoration: none; 
            font-weight: 600; 
            cursor: pointer; 
            display: inline-block; 
            border: 2px solid var(--primary);
            transition: 0.2s;
            font-size: 0.9rem;
        }
        .btn-primary { background: var(--primary); color: var(--white); }
        .btn-outline { background: var(--white); color: var(--primary); }
        .btn:hover { opacity: 0.8; }

        /* Product Grid */
        .product-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); 
            gap: 20px; 
        }
        
        .product-card { 
            background: var(--white); 
            border: 1px solid var(--primary);
            display: flex;
            flex-direction: column;
        }
        
        .img-container { 
            width: 100%; 
            border-bottom: 1px solid var(--primary);
            line-height: 0; 
        }
        .product-card img { width: 100%; height: auto; display: block; }

        .card-body { padding: 20px; }
        .status-badge { 
            font-size: 0.7rem; 
            padding: 2px 8px; 
            border: 1px solid var(--primary);
            color: var(--primary);
            text-transform: uppercase; 
            font-weight: bold; 
        }
        
        .price { color: var(--primary); font-size: 1.5rem; font-weight: 800; margin: 15px 0; }
        .product-name { font-size: 1.2rem; margin: 10px 0; font-weight: 700; color: #000; }

        .actions { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
        .btn-sm { padding: 8px; text-align: center; font-size: 0.8rem; }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            right: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--white);
            border-left: 4px solid var(--primary);
            transform: translateX(<?= $selectedProduct ? '0' : '100%' ?>);
            transition: transform 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px;
            border-bottom: 1px solid var(--primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--white);
        }

        .sidebar-content { padding: 30px; }
        .sidebar-content img { 
            width: 100%; 
            border: 1px solid var(--primary); 
            margin-bottom: 30px; 
        }
        
        .detail-row { margin-bottom: 20px; }
        .detail-label { font-size: 0.75rem; color: var(--primary); text-transform: uppercase; font-weight: 800; margin-bottom: 5px; }
        .detail-value { font-weight: 500; font-size: 1rem; color: #000; }

        .close-btn { font-size: 30px; text-decoration: none; color: var(--primary); font-weight: bold; }
        
        /* Utility */
        .text-blue { color: var(--primary); }
    </style>
</head>
<body>

<div class="main-content">
    <div class="header">
        <img style="width: 100px;" src="https://numer.digital/public/template/university/images/logo/num.png" alt="Logo Shop">
        <h1>NUM-Product-Managment</h1>
        <a href="add.php" class="btn btn-primary">ADD NEW PRODUCT</a>
    </div>

    <div class="product-grid">
        <?php if (empty($products)): ?>
            <p>No products currently in inventory.</p>
        <?php endif; ?>

        <?php foreach ($products as $p): ?>
            <div class="product-card">
                <div class="img-container">
                    <img src="<?= $p->image ? $p->image : 'https://via.placeholder.com/400x400/ffffff/0c3be4?text=No+Image' ?>" alt="Product">
                </div>
                <div class="card-body">
                    <span class="status-badge"><?= $p->status ?></span>
                    <div class="product-name"><?= strtoupper($p->name) ?></div>
                    <div class="price">$<?= number_format($p->price, 2) ?></div>
                    
                    <div class="actions">
                        <a href="index.php?view=<?= $p->id ?>" class="btn btn-sm btn-primary">VIEW DETAILS</a>
                        <a href="index.php?delete=<?= $p->id ?>" class="btn btn-sm btn-outline" onclick="return confirm('Confirm Deletion?')">DELETE</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<aside class="sidebar">
    <?php if ($selectedProduct): ?>
        <div class="sidebar-header">
            <h2 style="margin:0; font-size: 1.2rem; color: var(--primary);">PRODUCT SPECIFICATIONS</h2>
            <a href="index.php" class="close-btn">&times;</a>
        </div>
        <div class="sidebar-content">
            <img src="<?= $selectedProduct->image ? $selectedProduct->image : 'https://via.placeholder.com/400x400/ffffff/0c3be4?text=Product' ?>" alt="Detail">
            
            <div class="detail-row">
                <div class="detail-label">Item Name</div>
                <div class="detail-value" style="font-size: 1.5rem; font-weight: 800;"><?= $selectedProduct->name ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Current Market Price</div>
                <div class="detail-value" style="font-size: 1.8rem; color: var(--primary); font-weight: 900;">$<?= number_format($selectedProduct->price, 2) ?></div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="detail-row">
                    <div class="detail-label">Product Code</div>
                    <div class="detail-value"><?= $selectedProduct->code ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Category</div>
                    <div class="detail-value"><?= $selectedProduct->category ?></div>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Stock Level</div>
                <div class="detail-value"><?= $selectedProduct->quantity ?> Units Available</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Product Description</div>
                <div class="detail-value" style="line-height: 1.6;"><?= $selectedProduct->description ?></div>
            </div>

            <div class="detail-row" style="border-top: 1px solid var(--primary); padding-top: 20px; margin-top: 40px;">
                <div class="detail-label">Registration Date</div>
                <div class="detail-value"><?= $selectedProduct->createdAt ?></div>
            </div>

            <a href="index.php" class="btn btn-primary" style="width:100%; text-align:center; box-sizing: border-box; margin-top: 20px;">Close</a>
        </div>
    <?php endif; ?>
</aside>

</body>
</html>