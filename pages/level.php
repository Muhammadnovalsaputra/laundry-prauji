<?php
// require_once "config/koneksi.php";
$q_categories = mysqli_query($config, "SELECT * FROM levels");
$categories = mysqli_fetch_all($q_categories, MYSQLI_ASSOC);


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $q_delete = mysqli_query($config, "DELETE FROM levels WHERE id = '$id'");
    header("location:?page=level&hapus=berhasil");
}

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Data Level
                </h3>
            </div>
            <div class="card-body">
                <div align="right">
                    <a href="?page=tambah-level" class="btn btn-primary btn-sm mb-3 mt-3">
                        <i class="bi bi-plus-circle"></i> Add Categories
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tr align="center">
                        <th>No</th>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    foreach ($categories as $key => $category) {
                    ?>
                        <tr>
                            <td><?php echo $key + 1 ?></td>
                            <td><?php echo $category['name'] ?></td>
                            <td>
                                <a class="btn btn-danger btn-sm" href="?page=add-role-menu&edit=<?php echo $category['id'] ?>">
                                    <i class="bi bi-plus"></i>
                                </a>
                                <a class="btn btn-success btn-sm" href="?page=tambah-level&edit=<?php echo $category['id'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin akan menghapus data ini?')"
                                    href="?page=level&delete=<?php echo $category['id'] ?>">
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