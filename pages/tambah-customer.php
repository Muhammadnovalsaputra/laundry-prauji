<?php

$id = isset($_GET['edit']) ? $_GET['edit'] : "";
$queryEdit = mysqli_query($config, "SELECT * FROM customers WHERE id = '$id'");
$rowEdit = mysqli_fetch_assoc($queryEdit);

if (isset($_POST['update'])) {
    // $_POST
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // SET kolom apa yang ini di ubah
    if ($address) {
        $query = mysqli_query($config, "UPDATE customers SET name='$name', phone='$phone', address='$address' WHERE id='$id'");
    } else {
        $query = mysqli_query($config, "UPDATE customers SET name='$name', phone='$phone', address='$address' WHERE id='$id'");
    }

    if ($query) {
        header("location:?page=customer&ubah=berhasil");
    }
}

if (isset($_POST['simpan'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];


    // masukkan ke dalam sebuah tabel user jika di laravel $fillable
    $query = mysqli_query($config, "INSERT INTO customers (name, phone, address) values ('$name', '$phone', '$address')");

    if ($query) {
        header("location:?page=customer&customer=berhasil");
    }
}

?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">
                    <?php echo isset($_GET['edit']) ? 'Edit' : 'Add' ?> User
                </h3>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="" class="form-label">Name* </label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required value="<?php echo $rowEdit['name'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">phone* </label>
                        <input type="text" name="phone" class="form-control" placeholder="Enter your phone" required value="<?php echo $rowEdit['phone'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">address* </label>
                        <input type="text" name="address" class="form-control" placeholder="Enter your address" value="<?php echo $rowEdit['address'] ?? '' ?>">
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