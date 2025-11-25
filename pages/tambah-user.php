<?php

$id = isset($_GET['edit']) ? $_GET['edit'] : "";
$queryEdit = mysqli_query($config, "SELECT * FROM users WHERE id = '$id'");
$rowEdit = mysqli_fetch_assoc($queryEdit);

$queryLevels = mysqli_query($config, "SELECT * FROM levels ORDER BY id DESC");
$rowLevels = mysqli_fetch_all($queryLevels, MYSQLI_ASSOC);

if (isset($_POST['update'])) {
    // $_POST
    $name = $_POST['name'];
    $email = $_POST['email'];
    $level_id = $_POST['level_id'];
    $password = sha1($_POST['password']);

    // SET kolom apa yang ini di ubah
    if ($_POST['$password']) {
        $query = mysqli_query($config, "UPDATE users SET name='$name', email='$email', password='$password', level_id='$level_id' WHERE id='$id'");
    } else {
        $query = mysqli_query($config, "UPDATE users SET name='$name', email='$email',level_id='$level_id' WHERE id='$id'");
    }

    if ($query) {
        header("location:?page=user&ubah=berhasil");
    }
}

if (isset($_POST['simpan'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $level_id = $_POST['level_id'];
    $password = $_POST['password'];

    // masukkan ke dalam sebuah tabel user jika di laravel $fillable
    $query = mysqli_query($config, "INSERT INTO users (name, email, password,level_id) values ('$name', '$email', '$password','$level_id')");

    if ($query) {
        header("location:?page=user&tambah=berhasil");
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
                        <label for="" class="form-label">Level Name* </label>
                        <select type="text" name="level_id" class="form-control">
                            <option value="">Choose One</option>
                            <?php foreach ($rowLevels as $rowLevel): ?>
                                <option value="<?php echo $rowLevel['id'] ?>"><?php echo $rowLevel['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Name* </label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required value="<?php echo $rowEdit['name'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Email* </label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required value="<?php echo $rowEdit['email'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Password* <small>Kosongkan jika tidak ingin mengubah</small></label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password">
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