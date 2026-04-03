<?php
session_start();

// Include class definition (matching index.php)
class Product {
    public $id; public $code; public $name; public $category; public $description;
    public $price; public $quantity; public $image; public $status; public $createdAt;

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Basic auto-increment ID logic
    $nextId = isset($_SESSION['products']) ? count($_SESSION['products']) + 1 : 1;
    
    $imagePath = "https://via.placeholder.com/400x400/ffffff/0c3be4?text=No+Image"; 
    if(!empty($_POST['img_url'])) {
        $imagePath = $_POST['img_url'];
    }

    $newProduct = new Product(
        $nextId,
        $_POST['code'],
        $_POST['name'],
        $_POST['category'],
        $_POST['description'],
        (float)$_POST['price'],
        (int)$_POST['quantity'],
        $imagePath,
        $_POST['status']
    );

    $_SESSION['products'][] = $newProduct;
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Inventory</title>
    <style>
        :root { 
            --primary: #0c3be4; 
            --white: #ffffff; 
        }
        
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            background: var(--white); 
            color: #000;
            margin: 0; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .form-container { 
            background: var(--white); 
            padding: 40px; 
            width: 100%; 
            max-width: 700px; 
            border: 2px solid var(--primary);
        }

        h2 { 
            color: var(--primary); 
            margin-top: 0; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: -1px;
            font-size: 1.8rem;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .form-group { margin-bottom: 25px; }
        
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 800; 
            color: var(--primary); 
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        input, select, textarea { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid var(--primary); 
            box-sizing: border-box; 
            font-size: 1rem;
            color: #000;
            background: transparent;
            outline: none;
        }

        input:focus, select:focus, textarea:focus {
            background-color: #f0f3ff;
        }

        textarea { height: 100px; resize: vertical; }

        .row { display: flex; gap: 20px; }
        .col { flex: 1; }

        .btn-row { 
            display: flex; 
            gap: 15px; 
            margin-top: 40px; 
            border-top: 1px solid var(--primary);
            padding-top: 30px;
        }

        .btn { 
            flex: 1; 
            padding: 15px; 
            border: 2px solid var(--primary); 
            cursor: pointer; 
            text-decoration: none; 
            text-align: center; 
            font-size: 0.9rem; 
            font-weight: 700;
            text-transform: uppercase;
            transition: 0.2s;
        }

        .btn-save { background: var(--primary); color: var(--white); }
        .btn-back { background: var(--white); color: var(--primary); }
        .btn:hover { opacity: 0.8; }

        @media (max-width: 600px) {
            .row { flex-direction: column; gap: 0; }
        }
    </style>
</head>
<body>

<div class="form-container">
    <img style="width: 100px; margin: 0 auto; display: block;" src="https://numer.digital/public/template/university/images/logo/num.png" alt="">
    <h2 style="text-align: center;">Add New Product</h2>
    <form method="POST">
        <div class="form-group">
            <label>Product Full Name</label>
            <input type="text" name="name" required placeholder="ENTER PRODUCT NAME">
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>SKU / Product Code</label>
                    <input type="text" name="code" required placeholder="SKU-XXXX">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label>Product Category</label>
                    <select name="category" required>
                        <option value="Electronics">Electronics</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Books">Books</option>
                        <option value="Home">Home</option>
                        <option value="Drink">Drink</option>
                        <option value="Water">Water</option>
                        <option value="Milk">Milk</option>
                        <option value="Fruits">Fruits</option>
                        <option value="Vegetables">Vegetables</option>
                        <option value="Snacks">Snacks</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>Price (USD)</label>
                    <input type="number" step="0.01" name="price" required placeholder="0.00">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label>Initial Quantity</label>
                    <input type="number" name="quantity" required placeholder="0">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="img_url" placeholder="https://image-link.com/photo.jpg">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Detailed Description</label>
            <textarea name="description" placeholder="Provide technical specifications or details..."></textarea>
        </div>
        
        <div class="btn-row">
            <a href="index.php" class="btn btn-back">Return to Inventory</a>
            <button type="submit" class="btn btn-save">Create Product Record</button>
        </div>
    </form>
</div>

</body>
</html>