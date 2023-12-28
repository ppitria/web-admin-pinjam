<?php
require_once 'connection.php';
$collectionName = 'kendaraan';
$collection = $database->selectCollection($collectionName);
?>

<?php
include("inc_header.php");

$nama = "";
$foto = "";
$foto_name = "";
$status = "";

$error = "";
$sukses = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = "";
}

if ($id != "") {
    $document = $collection->findOne(['_id' => new MongoDB\BSON\ObjectID($id)]);

    if ($document) {
        $nama = $document['nama'];
        $foto = $document['foto'];
        $status = $document['status'];

        if ($nama == '') {
            $error = "Data tidak ditemukan";
        }
    }
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $status = $_POST['status'];

    if ($nama == '') {
        $error = "Silakan masukkan semua data";
    }

    if ($_FILES['foto']['name']) {
        $foto_name = $_FILES['foto']['name'];
        $foto_file = $_FILES['foto']['tmp_name'];

        $detail_file = pathinfo($foto_name);
        $foto_ekstensi = $detail_file['extension'];

        $ekstensi = array("jpg", "jpeg", "png");
        if (!in_array($foto_ekstensi, $ekstensi)) {
            $error = "Ekstensi yang diperbolehkan adalah jpg, jpeg, dan png";
        }
    }

    if (empty($error)) {
        if ($foto_name) {
            $direktori = "image/kendaraan";

            @unlink($direktori . "/$foto");

            $foto_name = "kendaraan_" . time() . "_" . $foto_name;
            move_uploaded_file($foto_file, $direktori . "/" . $foto_name);

            $foto = $foto_name;
        } else {
            $foto_name = $foto;
        }

        if ($id != "") {
            $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectID($id)],
                ['$set' => ['nama' => $nama, 'foto' => $foto_name, 'status' => $status, 'tgl_isi' => new MongoDB\BSON\UTCDateTime()]]
            );
        } else {
            $collection->insertOne(['nama' => $nama, 'foto' => $foto_name, 'status' => $status, 'tgl_isi' => new MongoDB\BSON\UTCDateTime()]);
        }

        $sukses = "Sukses memasukkan data";
    }
}
?>

<div class="container">
    <h1 class="title">Halaman Input Item Kendaraan</h1>
    <div class="mb-3 row">
        <a class="back" href="kendaraan.php">
            << Kembali ke halaman admin</a>
    </div>

    <?php
    if ($error) {
        ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
        <?php
    }
    ?>

    <?php
    if ($sukses) {
        ?>
        <div class="alert alert-primary" role="alert">
            <?php echo $sukses; ?>
        </div>
        <?php
    }
    ?>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3 row">
            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nama" value="<?php echo $nama; ?>" name="nama">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="foto" class="col-sm-2 col-form-label">Foto</label>
            <div class="col-sm-10">
                <?php
                if ($foto) {
                    echo "<img src='image/kendaraan/$foto' style='max-height:100px; max-width:100px'>";
                }
                ?>
                <input type="file" class="form-control" id="foto" name="foto">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="status" class="col-sm-2 col-form-label">Status</label>
            <div class="col-sm-10">
                <select class="form-control" id="status" name="status">
                    <option value="tersedia" <?php echo ($status == 'tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                    <option value="disewa" <?php echo ($status == 'disewa') ? 'selected' : ''; ?>>Disewa</option>
                    <option value="perbaikan" <?php echo ($status == 'perbaikan') ? 'selected' : ''; ?>>Perbaikan</option>
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
                <input type="submit" name="simpan" value="Simpan Data" class="btn all-button" />
            </div>
        </div>
    </form>
</div>

<?php include("inc_footer.php"); ?>