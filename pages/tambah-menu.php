<?php

$id = isset($_GET['edit']) ? $_GET['edit'] : "";
$queryEdit = mysqli_query($config, "SELECT * FROM menus WHERE id = '$id'");
$rowEdit = mysqli_fetch_assoc($queryEdit);

if (isset($_POST['update'])) {
    // $_POST
    $name = $_POST['name'];
    $icon = $_POST['icon'];
    $link = $_POST['link'];
    $orders = $_POST['orders'];


    // SET kolom apa yang ini di ubah
    if ($link) {
        $query = mysqli_query($config, "UPDATE menus SET name='$name', icon='$icon', link='$link', orders='$orders' WHERE id='$id'");
    } else {
        $query = mysqli_query($config, "UPDATE menus SET name='$name', icon='$icon', link='$link',orders='$orders' WHERE id='$id'");
    }

    if ($query) {
        header("location:?page=menu&ubah=berhasil");
    }
}

if (isset($_POST['simpan'])) {
    $name = $_POST['name'];
    $icon = $_POST['icon'];
    $link = $_POST['link'];
    $orders = $_POST['orders'];


    // masukkan ke dalam sebuah tabel user jika di laravel $fillable
    $query = mysqli_query($config, "INSERT INTO menus (name ,icon, link,orders) values ('$name','$icon', '$link','$orders')");

    if ($query) {
        header("location:?page=menu&tambah=berhasil");
    }
}

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">
                    <?php echo isset($_GET['edit']) ? 'Edit' : 'Add' ?> Service
                </h3>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="" class="form-label">name* </label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required value="<?php echo $rowEdit['name'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">icon* </label>
                        <input type="text" name="icon" class="form-control" placeholder="Enter your name" required value="<?php echo $rowEdit['icon'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">link* </label>
                        <input type="text" name="link" class="form-control" placeholder="Enter your price" required value="<?php echo $rowEdit['link'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Order* </label>
                        <input type="text" name="orders" class="form-control" placeholder="Enter your price" required value="<?php echo $rowEdit['orders'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary btn-sm" type="submit" name="<?php echo ($id) ? 'update' : 'simpan' ?>">
                            <?php echo ($id) ? 'Simpan Perubahan' : 'Simpan' ?>
                        </button>
                        <a href="?page=user" class="btn btn-primary btn-sm">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>