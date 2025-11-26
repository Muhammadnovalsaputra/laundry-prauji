<?php
include '../config/config.php';

$queryCat = mysqli_query($config, "SELECT * FROM services");
$fetchCats = mysqli_fetch_all($queryCat, MYSQLI_ASSOC);
// query product
// $queryProducts = mysqli_query($config, "SELECT c.category_name,p.* FROM products p LEFT JOIN categories c ON c.id = p.category_id");
// $fetchProducts = mysqli_fetch_all($queryProducts, MYSQLI_ASSOC);


if (isset($_GET['payment'])) {

    //transaction
    $data = json_decode(file_get_contents("php://input"), true);
    $cart = $data["cart"];
    $subtotal = array_reduce($cart, function ($sum, $item) {
        return $sum + ($item['product_price'] * $item['quantity']);
    }, 0);
    $tax = $data['tax'];
    $orderAmounth = $data['grandTotal'];
    $orderCode = $data['order_code'];
    $orderDate = date("Y-m-d H:i:s");
    $orderChange = 0;
    $orderStatus = 1;
    $orderSubtotal = $data['subtotal'];

    try {
        $insertOrder = mysqli_query($config, "INSERT INTO trans_orders(order_code, order_date, order_amount, order_subtotal, order_status) VALUES ('$orderCode', '$orderDate','$orderAmounth','$orderSubtotal','$orderStatus')");
        $idOrder = mysqli_insert_id($config);
        if (!$insertOrder) {
            throw new Exception("Insert failed to table orders", mysqli_error($config));
            exit;
        }

        foreach ($cart as $v) {
            $product_id = $v['id'];
            $qty = $v['quantity'];
            $order_price = $v['product_price'];
            $subtotal = $qty * $order_price; //1*13

            $insertOrderDetails = mysqli_query($config, "INSERT INTO trans_orders(order_id, product_id, qty, order_price, order_subtotal) VALUES ($idOrder, $product_id, $qty, $order_price, $subtotal)");

            if (!$insertOrderDetails) {
                throw new Exception("Insert failed to table orders", mysqli_error($config));
                exit;
            }
        }
        $response = [
            'status' => 'success',
            'message' => 'Transcation success',
            'order_id' => $idOrder,
            'order_code' => $orderCode,
        ];
        mysqli_commit($config);
        echo json_encode($response);
        die;
    } catch (\Throwable $th) {
        mysqli_rollback($config);
        $response = ['status' => 'Error', 'message' => $th->getMessage()];
        echo json_encode($response);
        die;
    }
}

// $orderNumbers = mysqli_query($config, "SELECT id FROM orders ORDER BY id DESC LIMIT 1");
// $row = mysqli_fetch_assoc($orderNumbers);
// $nextId = $row ? $row['id'] + 1 : 1;
// STR_PAD fungtion dari php untuk membuat sebuah nomor urut atau running number
// $order_code =  "ORD-" . date('dmy') . str_pad($nextId, 4, "0", STR_PAD_RIGHT);
// if(row) {
//     $nextId= $row['id'] +1;
// } else {
//     $nextId= 1;
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point Of Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/css/salsa.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
</head>

<body>




    <!-- container-fluid -->
    <div class="container-fluid container-pos">
        <div id="card">
            <!-- <h3>Nama Product</h3>
            <p>Description product</p> -->
        </div>
        <div class="row h-100 d-flex justify-content-between">
            <div class="col-md-6 product-section">
                <div class="mb-4">
                    <h4 class="mb-3" id="product-title">
                        <i class="fas fas-store"></i>product
                    </h4>
                    <input type="text" id="searchProduct" class="form-control search-box" placeholder="find the product..">
                </div>
                <div class="mb-4">
                    <button class="btn btn-warning category-btn active" onclick="filterCategory('all', this)">All Menu</button>
                    <?php foreach ($fetchCats as $cat): ?>
                        <!-- <button class="btn btn-outline-warning category-btn " onclick="filterCategory('<?php echo $cat['category_name'] ?>', this)"><?php echo $cat['category_name'] ?></button> -->
                    <?php endforeach ?>
                    <!-- <button class="btn btn-outline-warning category-btn ">Drink</button>
                        <button class="btn btn-outline-warning category-btn ">Snack</button> -->
                </div>
                <div class="row" id="productGrid"></div>

            </div>
            <div class="col-md-5 cart-section">
                <div class="cart-header">
                    <h4>cart</h4>
                    <!-- ORD-date-001 -->
                    <small>Order # <span class="orderNumber"></span></small>
                </div>
                <div class="cart-items" id="cartItems">
                    <div class="text-center text-muted mt-5">
                        <i class="bi bi-cart mb-3"></i>
                        <p>cart the empty </p>
                    </div>
                </div>
                <div class="cart-footer">
                    <div class="total-section">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal :</span>
                            <span id="Subtotal">Rp. 0.0</span>
                            <input type="hidden" id="subtotal_value">
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pajak (10%) :</span>
                            <span id="tax">Rp. 0.0</span>
                            <input type="hidden" id="tax_value">
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total :</span>
                            <span id="total">Rp. 0.0</span>
                            <input type="hidden" id="total_value">
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <button class="btn btn-outline-danger w-100" id="clearCart">
                                <i class="bi bi-trash"></i> Clear Cart
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-checkout btn-primary w-100" onclick="processPayment()">
                                <i class="bi bi-cash"></i> Process Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous">
        </script>
        <script>
            const products = <?php echo json_encode($fetchProducts); ?>
        </script>

        <script src="../assets/js/salsa.js"></script>
</body>

</html>