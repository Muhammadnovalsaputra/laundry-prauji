<?php
session_start();
include '../config/koneksi.php';

$query = mysqli_query($koneksi, "SELECT * FROM orders ORDER BY id DESC");
$row = mysqli_fetch_assoc($query);

$order_id = $row['id'];
$queryDetails = mysqli_query($koneksi, "SELECT p.product_name, od.* FROM order_details od
LEFT JOIN products p ON p.id = od.product_id WHERE order_id = '$order_id'");
$rowDetails = mysqli_fetch_all($queryDetails, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Struk Pembayaran</title>

  <style>
    body {
      width: 80mm;
      font-family: "Courier New", Courier, monospace;
      margin: 0 auto;
      padding: 10px;
      background-color: white;
    }

    .struck-page {
      width: 100%;
      font-size: 12px;
    }

    .header {
      text-align: center;
      margin-bottom: 15px;
    }

    .header h2 {
      font-size: 20px;
      margin: 0 0 10px 0;
      font-weight: bold;
    }

    .header p {
      margin: 3px 0;
      font-size: 11px;
    }

    .info {
      margin: 15px 0;
      font-size: 11px;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      margin: 3px 0;
    }

    .separator {
      border-bottom: 1px dashed black;
      margin: 10px 0;
    }

    .items {
      margin: 10px 0;
    }

    .item {
      display: flex;
      justify-content: space-between;
      margin: 8px 0;
      font-size: 12px;
    }

    .item-name {
      flex: 1;
    }

    .item-qty {
      margin: 0 10px;
    }

    .item-price {
      text-align: right;
      min-width: 80px;
    }

    .totals {
      margin-top: 15px;
    }

    .total-row {
      display: flex;
      justify-content: space-between;
      margin: 5px 0;
      font-size: 12px;
    }

    .total-row.grand {
      font-weight: bold;
      font-size: 16px;
      margin: 10px 0;
    }

    .payment {
      margin-top: 10px;
    }

    @media print {

      /* body {
          margin: 0;
          padding: 0;
        } */
      @page {
        margin: 0;
        size: 80mm auto;
      }
    }
  </style>
</head>

<body onload="window.print()">
  <div class="struck-page">
    <div class="header">
      <h2>Struk Pembayaran</h2>
      <p>Jl. Karet Benhil</p>
      <p>0874125369</p>
    </div>
    <div class="info">
      <div class="info-row">
        <?php
        //strtotime
        $date = date("d-m-y", strtotime($row['order_date']));
        $time = date("H:i:s", strtotime($row['order_date']));
        ?>
        <span><?php echo $date ?></span>
        <span><?php echo $time ?></span>
      </div>
      <div class="info-row">
        <span>Transaction Id</span>
        <Span>#<?php echo $row['order_code'] ?? '' ?></Span>
      </div>
      <div class="info-row">
        <span>Cashier Name</span>
        <span><?php echo $_SESSION['NAME'] ?? '' ?></span>
      </div>
    </div>
    <div class="separator"></div>
    <div class="items">
      <?php foreach ($rowDetails as $item): ?>
        <div class="item">
          <span class="item-name"><?php echo $item['product_name'] ?></span>
          <span class="item-qty">x<?php echo $item['qty'] ?></span>
          <span class="item-price">Rp. <?php echo number_format($item['order_price']) ?></span>
        </div>
      <?php endforeach ?>
      <div class="separator"></div>
    </div>
    <div class="totals">
      <div class="total-row">
        <span>Sub Total</span>
        <span></span>
      </div>
      <div class="total-row">
        <span>Ppn (include)</span>
        <span></span>
      </div>
    </div>
    <div class="separator"></div>
    <div class="total-row grand ">
      <span>Total</span>
      <span>Rp. <?php echo $row['order_amount'] ?> </span>
    </div>
    <!-- <div class="payment">
            <div class="total-row">
                <span>Cash</span>
                <span>Rp. </span>
            </div>
            <div class="total-row">
                <span>Change</span>
                <span>Rp.</span>
            </div>
        </div> -->
  </div>
  <hr>
</body>

</html>