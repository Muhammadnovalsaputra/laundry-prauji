<?php

$query = mysqli_query($config, "SELECT * FROM taxs ORDER BY id DESC ");
$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];


    $delete = mysqli_query($config, "DELETE FROM taxs WHERE id=$id");
    if ($delete) {
        header("location:?page=tax");
    }
}

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <h3 class="card-title">
                    Data Product
                </h3>
                <div align="right">
                    <a href="?page=tambah-tax" class="btn btn-primary btn-sm mb-3 mt-3">
                        <i class="bi bi-plus-circle"></i> Add taxs
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tr align="center">
                        <th>No</th>
                        <th>Percent</th>
                        <th>Is Active</th>
                        <th>Actions</th>

                    </tr>
                    <?php
                    foreach ($rows as $key => $value) {
                    ?>
                        <tr>
                            <td><?php echo $key + 1 ?></td>
                            <td><?php echo $value['percent'] ?></td>
                            <td><?php echo $value['is_active'] ?></td>
                            <td><?php echo $value['is_active'] == 1 ? 'active' : 'draft' ?></td>
                            <td>
                                <a class="btn btn-success btn-sm" href="?page=tambah-tax&edit=<?php echo $value['id'] ?>">
                                    <i class="bi bi-pencil"></i>Edit
                                </a>
                                <a class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin akan menghapus data ini?')"
                                    href="?page=tax&delete=<?php echo $value['id'] ?>">
                                    <i class="bi bi-trash"></i>Delete
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