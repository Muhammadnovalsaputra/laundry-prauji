<?php
// require_once "config/koneksi.php";
$q_menu = mysqli_query($config, "SELECT * FROM menus ORDER BY orders ASC");
$menus = mysqli_fetch_all($q_menu, MYSQLI_ASSOC);


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $q_delete = mysqli_query($config, "DELETE FROM menus WHERE id = '$id'");
    header("location:?page=menu&hapus=berhasil");
}

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Data Menu
                </h3>
            </div>
            <div class="card-body">
                <div align="right">
                    <a href="?page=tambah-menu" class="btn btn-primary btn-sm mb-3 mt-3">
                        <i class="bi bi-plus-circle"></i> Add Menu
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tr align="center">
                        <th>No</th>
                        <th>name</th>
                        <th>Icon</th>
                        <th>Link</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    foreach ($menus as $key => $menu) {
                    ?>
                        <tr>
                            <td><?php echo $key + 1 ?></td>
                            <td><?php echo $menu['name'] ?></td>
                            <td><?php echo $menu['icon'] ?></td>
                            <td><?php echo $menu['link'] ?></td>
                            <td><?php echo $menu['orders'] ?></td>

                            <td>
                                <a class="btn btn-success btn-sm" href="?page=tambah-menu&edit=<?php echo $menu['id'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin akan menghapus data ini?')"
                                    href="?page=menu&delete=<?php echo $menu['id'] ?>">
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