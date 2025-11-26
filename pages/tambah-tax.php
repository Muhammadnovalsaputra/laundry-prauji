<?php

$id = isset($_GET['edit']) ? $_GET['edit'] : "";
$query = mysqli_query($config, "SELECT * FROM taxs WHERE id = '$id'");
$rowEdit = mysqli_fetch_assoc($query);

//product
$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$s_product = mysqli_query($config, "SELECT * FROM taxs WHERE id='$id' ");
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
    $percent = $_POST['percent'];
    $is_active = $_POST['is_active'];

    $query = mysqli_query($config, "INSERT INTO taxs (percent, is_active) VALUES ('$percent', '$is_active')");

    if ($query) {
        header("location:?page=tax&tambah=berhasil");
    }
}

if (isset($_POST['update'])) {
    $id = $_GET['edit'];
    $percent = $_POST['percent'];
    $is_active = $_POST['is_active'];



    // $p_photo = $_FILES['product_photo'];

    $update = mysqli_query($koneksi, "UPDATE taxs SET percent='$percent',is_active='$is_active' WHERE id = $id ");
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
                        <?php echo isset($_GET['edit']) ? 'Edit' : 'Create' ?> Tax
                    </h3>
                </div>
            </div>
            <div class="card-body w-50">
                <form action="" method="post" enctype="multipart/form-data">

                    <div class="w-50">
                        <div class="mb-3">
                            <label for="" class="form-label">
                                percent
                            </label>
                            <input type="number" class="form-control" name="percent"
                                value="<?php echo $rowEdit ? $rowEdit['percent'] : '' ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">
                            Is Active
                        </label>
                        <br>
                        <input type="radio" name="is_active" value="0">Draft
                        <br>
                        <input type="radio" name="is_active" <?php echo $rowEdit ? $rowEdit == 1 ? 'checked' : '' : '' ?>
                            value="1">Active
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