<?php
// include
// include_once
// require_once
// require
// require_once "config/koneksi.php";

$query = mysqli_query($koneksi, "SELECT r.name AS role_name, u.* FROM users AS u LEFT Join roles AS r ON r.id = u.role_id WHERE u.deleted_at IS NOT NULL ORDER BY u.id");

$users = mysqli_fetch_all($query, MYSQLI_ASSOC);

// disini parameter delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = mysqli_query($koneksi, "DELETE FROM users Where id='$id'");
    // redirect
    header("location:?page=user-restore&hapus=berhasil");
}

// disini parameter restore
if (isset($_GET['restore'])) {
    $id = $_GET['restore'];
    $restore = mysqli_query($koneksi, "UPDATE users SET deleted_at=null WHERE id='$id'");
    header("location:?page=user&restore=berhasil");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User</title>
</head>

<body>
    <h1>Restore Data User</h1>
    <div align="right">
        <a href="?page=user" class="btn btn-primary btn-sm mb-3">
            Back
        </a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $key => $value) { ?>
                <tr>
                    <td><?php echo $key += 1 ?></td>
                    <td><?php echo $value['name'] ?></td>
                    <td><?php echo $value['email'] ?></td>
                    <td><?php echo $value['role_name'] ?></td>
                    <td>
                        <a class="btn btn-warning btn-sm" onclick="return confirm('Apakah anda yakin akan merestore data ini?')"
                            href="?page=user-restore&restore=<?php echo $value['id'] ?>">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>

                        <a class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin akan menghapus permanent data ini?')"
                            href="?page=user-restore&delete=<?php echo $value['id'] ?>">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</body>

</html>