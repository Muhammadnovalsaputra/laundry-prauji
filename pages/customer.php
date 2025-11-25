<?php
// require_once "config/koneksi.php";
$q_customers = mysqli_query($config, "SELECT * FROM customers");
$customers = mysqli_fetch_all($q_customers, MYSQLI_ASSOC);


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $q_delete = mysqli_query($config, "DELETE FROM customers WHERE id = '$id'");
    header("location:?page=customer&hapus=berhasil");
}

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Data Customer
                </h3>
            </div>
            <div class="card-body">
                <div align="right">
                    <a href="?page=tambah-customer" class="btn btn-primary btn-sm mb-3 mt-3">
                        <i class="bi bi-plus-circle"></i> Add Customer
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tr align="center">
                        <th>Id</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>address</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    foreach ($customers as $key => $customer) {
                    ?>
                        <tr>
                            <td><?php echo $key + 1 ?></td>
                            <td><?php echo $customer['name'] ?></td>
                            <td><?php echo $customer['phone'] ?></td>
                            <td><?php echo $customer['address'] ?></td>
                            <td>
                                <a class="btn btn-success btn-sm" href="?page=tambah-service&edit=<?php echo $customer['id'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin akan menghapus data ini?')"
                                    href="?page=service&delete=<?php echo $customer['id'] ?>">
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