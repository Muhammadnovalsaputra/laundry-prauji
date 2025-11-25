<?php

$id = isset($_GET['edit']) ? $_GET['edit'] : "";
$q_select = mysqli_query($koneksi, "SELECT * FROM products WHERE id = '$id'");
$product = mysqli_fetch_assoc($q_select);
$queryCategory = mysqli_query($koneksi, 'SELECT * FROM categories');
$categories = mysqli_fetch_all($queryCategory, MYSQLI_ASSOC);
$rowEdit = mysqli_fetch_assoc($q_select);

//product
$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$s_product = mysqli_query($koneksi, "SELECT * FROM products WHERE id='$id' ");
$p = mysqli_fetch_assoc($s_product);
// var_dump($product);

if (isset($_POST['update'])) {
    $c_id = $_POST['category_id'];
    $p_name = $_POST['product_name'];
    $p_price = $_POST['product_price'];
    $p_description = $_POST['product_description'];
    $p_photo = $_FILES['product_photo'];

    // tentukan path foto: kalau ada upload baru, pindah dan gunakan itu;
    // kalau tidak, pakai foto lama dari $rowEdit
    if ($p_photo['error'] === UPLOAD_ERR_OK) {
        $name = uniqid() . '-' . basename($p_photo['name']);
        $filePath = 'assets/uploads/' . $name;
        move_uploaded_file($p_photo['tmp_name'], $filePath);
    } else {
        // fallback ke foto lama
        $filePath = $rowEdit['product_photo'];
    }

    // sekarang product_photo sudah string path, bukan array
    $update = mysqli_query(
        $koneksi,
        "UPDATE products SET
            category_id      = '$c_id',
            product_name     = '$p_name',
            product_price    = '$p_price',
            product_description = '$p_description',
            product_photo    = '$filePath'
         WHERE id = '$id'
        "
    );

    header('Location:?page=product&ubah=berhasil');
    exit;
}

if (isset($_POST['simpan'])) {
    $c_id = $_POST['category_id'];
    $p_name = $_POST['product_name'];
    $p_price = $_POST['product_price'];
    $p_description = $_POST['product_description'];
    $p_photo = $_FILES['product_photo'];

    $filePath = "assets/uploads/" . uniqid() . "-" . $p_photo['name'];
    move_uploaded_file($p_photo['tmp_name'], $filePath);

    $q_product = mysqli_query($koneksi, "INSERT INTO products (category_id, product_name, product_price, product_description, product_photo) VALUES ('$c_id', '$p_name', '$p_price', '$p_description', '$filePath')");

    if ($q_product) {
        header("location:?page=product&tambah=berhasil");
    }
}

if (isset($_POST['update'])) {
    $id = $_GET['edit'];
    $c_id = $_POST['category_id'];
    $p_name = $_POST['product_name'];
    $p_price = $_POST['product_price'];
    $p_description = $_POST['product_description'];




    // $p_photo = $_FILES['product_photo'];
    $cek_foto = mysqli_query($koneksi, "SELECT product_photo FROM products WHERE id=$id");
    $row = mysqli_fetch_assoc($cek_foto);
    $oldFile = $row['product_[photo'];
    $filePath = $oldFile;
    if (!empty($p_photo['name'])) {
        if (file_exists($oldFile)) {
            unlink($oldFile);
        }
        $filePath = 'assets/uploads/' . time() . "-" . $p_photo['name'];
        move_uploaded_file($p_photo['tmp_name'], $filePath);
    }
    $update = mysqli_query($koneksi, "UPDATE products SET category_id='$c_id',product_name='$p_name',product_price='$p_price',product_description='$p_description',product_photo='$filePath' WHERE id = $id ");
    if ($update) {
        header("location:?page=product");
    }
}

?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h3>
                        <?php echo isset($_GET['edit']) ? 'Update' : 'Add' ?> Product
                    </h3>
                </div>
            </div>
            <div class="card-body w-50">
                <div align="right">
                    <a href="?page=product" class="btn btn-primary btn-sm mt-3">Back</a>
                </div>
                <form action="" method="post" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label" for="">Category Name</label>
                        <select class="form-select" name="category_id" required>
                            <option>-- Select Category --</option>
                            <?php
                            foreach ($categories as $c) {
                                $selected = (isset($product['category_id']) && $product['category_id'] == $c['id']) ? 'selected' : '';
                            ?>
                                <option <?php echo isset($p) && $p['id'] == $c['id'] ? 'selected' : '' ?> value="<?php echo $c['id'] ?>" <?php echo $selected ?>>
                                    <?php echo $c['category_name'] ?>
                                </option>
                            <?php
                            }
                            ?>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">
                            Product Name
                        </label>
                        <input type="text" class="form-control" name="product_name"
                            value="<?php echo $p ? $p['product_name'] : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">
                            Photo
                        </label>
                        <?php

                        ?>
                        <input type="file" class="form-control" name="product_photo">
                        <img src="<?php echo isset($_GET['edit']) ? $product['product_photo'] : '' ?>" class="w-50 mt-1">
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">
                            Product Price
                        </label>
                        <input type="number" class="form-control" name="product_price"
                            value="<?php echo $p ? $p['product_price'] : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">
                            Product Description
                        </label>
                        <textarea type="text" class="form-control" name="product_description" cols="30" rows="5"><?php echo $p['product_description'] ?? '' ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm mt-3"
                        name="<?php echo isset($_GET['edit']) ? 'update' : 'simpan' ?>" class="btn btn-primary mt-2">
                        <?php echo isset($_GET['edit']) ? 'Edit' : 'Create' ?>
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>