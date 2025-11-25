<?php

$id = isset($_GET['edit']) ? $_GET['edit'] : "";
$queryEdit = mysqli_query($config, "SELECT * FROM services WHERE id = '$id'");
$rowEdit = mysqli_fetch_assoc($queryEdit);

if (isset($_POST['update'])) {
    // $_POST
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // SET kolom apa yang ini di ubah
    if ($description) {
        $query = mysqli_query($config, "UPDATE services SET name='$name', price='$price', description='$description' WHERE id='$id'");
    } else {
        $query = mysqli_query($config, "UPDATE services SET name='$name', price='$price', description='$description' WHERE id='$id'");
    }

    if ($query) {
        header("location:?page=service&ubah=berhasil");
    }
}

if (isset($_POST['simpan'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // masukkan ke dalam sebuah tabel user jika di laravel $fillable
    $query = mysqli_query($config, "INSERT INTO services (name, price, description) values ('$name', '$price', '$description')");

    if ($query) {
        header("location:?page=service&tambah=berhasil");
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
                        <label for="" class="form-label">Name* </label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required value="<?php echo $rowEdit['name'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">price* </label>
                        <input type="text" name="price" class="form-control" placeholder="Enter your price" required value="<?php echo $rowEdit['price'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">description</label>
                        <textarea name="description" class="form-control" placeholder="Enter your description" id=""><?php echo $rowEdit['description'] ?? '' ?></textarea>
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