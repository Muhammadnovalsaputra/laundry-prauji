<?php
// require_once "config/koneksi.php";

$id = isset($_GET['edit']) ? $_GET['edit'] : "";
$selectLevel = mysqli_query($config, "SELECT name FROM levels WHERE id='$id'");
$level = mysqli_fetch_assoc($selectLevel);

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $update = mysqli_query($config, "UPDATE levels SET name='$name' WHERE id='$id' ");
    header("location:?page=level&ubah=berhasil");
}

if (isset($_POST['simpan'])) {
    $name =  $_POST['name'];
    $insert = mysqli_query($config, "INSERT INTO levels (name)  VALUES ('$name')");

    header("location:?page=level&tambah=berhasil");
}

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h3><?php echo isset($_GET['edit']) ? 'Update' : 'Add' ?> Level</h3>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="" class="form-label">Tambah Level</label>
                            <input class="form-control" type="text" name="name" value="<?php echo $level['name'] ?? "" ?>" required>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary btn-sm" type="submit"
                                name="<?php echo isset($_GET['edit']) ? 'update' : 'simpan' ?>">
                                <?php echo isset($_GET['edit']) ? 'Edit' : 'Create' ?>
                            </button>
                            <a href="?page=level" class="btn btn-primary btn-sm">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>