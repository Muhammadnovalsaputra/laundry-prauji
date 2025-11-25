<?php

$query = mysqli_query($koneksi, "SELECT * FROM orders ORDER BY id DESC");
$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $s_photo = mysqli_query($koneksi, "SELECT product_photo FROM products WHERE id=$id");
    $row = mysqli_fetch_assoc($s_photo);
    $filePath = $row['product_photo'];

    if (file_exists($filePath)) {
        unlink($filePath);
    }
    $delete = mysqli_query($koneksi, "DELETE FROM products WHERE id=$id");
    if ($delete) {
        header("location:?page=product");
    }
}

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Data Product
                </h3>
            </div>
            <div class="card-body">
                <div align="right">
                    <a href="pos/add-pos.php" class="btn btn-primary btn-sm mb-3 mt-3">
                        <i class="bi bi-plus-circle"></i> Add POSS
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tr align="center">
                        <th>No</th>
                        <th>Order Code</th>
                        <th>Order Date</th>
                        <th>Order Amount</th>
                        <th>Order Change</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    foreach ($rows as $key => $value) {
                    ?>
                        <tr>
                            <td><?php echo $key + 1 ?></td>
                            <td><?php echo $value['order_code'] ?></td>
                            <td><?php echo $value['order_date'] ?></td>
                            <td><?php echo $value['order_amount'] ?></td>
                            <td><?php echo $value['order_change'] ?></td>
                            <td><?php echo $value['order_status'] ?></td>
                            <td>
                                <a class="btn btn-success btn-sm" href="?page=tambah-product&edit=<?php echo $value['id'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin akan menghapus data ini?')"
                                    href="?page=product&delete=<?php echo $value['id'] ?>">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>