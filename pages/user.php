<?php
// include
// include_once
// require_once
// require
// require_once "config/koneksi.php";

// urutkan berdasarkan urutan  terbesar
$query = mysqli_query($config, "SELECT l.name as level_name, u.* FROM users AS u LEFT JOIN levels l ON l.id = u.level_id ORDER BY u.id DESC");
// hasil dari query
$users = mysqli_fetch_all($query, MYSQLI_ASSOC);

// disini parameter delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = mysqli_query($config, "DELETE FROM users WHERE id='$id'");
    // redirect
    header("location:?page=user&hapus=berhasil");
}
?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Data User
                </h3>
            </div>
            <div class="card-body mt-3">
                <div class="mb-3" align="right">
                    <a href="?page=tambah-user" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Add User
                    </a>
                    <a href="?page=user-restore" class="btn btn-primary btn-sm">
                        <i class="bi bi-arrow-clockwise"></i> User Restore
                    </a>
                </div>
                <table class="table table-bordered table-striped datatable">
                    <thead>
                        <tr align="center">
                            <!-- listing -->
                            <th>No</th>
                            <th>Level</th>
                            <th>Name</th>
                            <th>Email</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $key => $value) { ?>
                            <tr>
                                <td><?php echo $key += 1 ?></td>
                                <td><?php echo $value['level_name'] ?></td>
                                <td><?php echo $value['name'] ?></td>
                                <td><?php echo $value['email'] ?></td>
                                <td align="center">
                                    <a class="btn btn-success btn-sm" href="?page=tambah-user&edit=<?php echo $value['id'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin akan menghapus data ini?')"
                                        href="?page=user&delete=<?php echo $value['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>