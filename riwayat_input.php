<?php
require_once 'connection.php';
$collectionName = 'riwayat';
$collection = $database->selectCollection($collectionName);
?>

<?php
include("inc_header.php");

$peminjam = "";
$kendaraan = "";
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
        $peminjam = $document['peminjam'];
        $kendaraan = $document['kendaraan'];
        $status = $document['status'];

        if ($peminjam == '') {
            $error = "Data tidak ditemukan";
        }
    }
}

if (isset($_POST['simpan'])) {
    $peminjam = $_POST['peminjam'];
    $kendaraan = $_POST['kendaraan'];
    $status = $_POST['status'];

    if ($peminjam == '') {
        $error = "Silakan masukkan semua data";
    }


    if (empty($error)) {

        if ($id != "") {
            $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectID($id)],
                ['$set' => ['peminjam' => $peminjam, 'kendaraan' => $kendaraan, 'status' => $status, 'tgl_isi' => new MongoDB\BSON\UTCDateTime()]]
            );
        } else {
            $collection->insertOne(['peminjam' => $peminjam, 'kendaraan' => $kendaraan, 'status' => $status, 'tgl_isi' => new MongoDB\BSON\UTCDateTime()]);
        }

        $sukses = "Sukses memasukkan data";
    }
}
?>

<div class="container">
    <h1 class="title">Halaman Input Riwayat Peminjaman</h1>
    <div class="mb-3 row">
        <a class="back" href="riwayat.php">
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
            <label for="peminjam" class="col-sm-2 col-form-label">Peminjam</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="peminjam" value="<?php echo $peminjam; ?>" name="peminjam">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="status" class="col-sm-2 col-form-label">Status</label>
            <div class="col-sm-10">
                <select class="form-control" id="status" name="status">
                    <option value="belum" <?php echo ($status == 'belum') ? 'selected' : ''; ?>>Belum Selesai</option>
                    <option value="selesai" <?php echo ($status == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="kendaraan" class="col-sm-2 col-form-label">Kendaraan</label>
            <div class="col-sm-10">
                <select class="form-control" id="kendaraan" name="kendaraan">
                    <option value="Honda VCX Vario" <?php echo ($kendaraan == 'Honda VCX Vario') ? 'selected' : ''; ?>>
                        Honda VCX Vario
                    </option>
                    <option value="Kawasaki KLX" <?php echo ($kendaraan == 'Kawasaki KLX') ? 'selected' : ''; ?>>Kawasaki
                        KLX</option>
                    <option value="Yamaha NMAX" <?php echo ($kendaraan == 'Yamaha NMAX') ? 'selected' : ''; ?>>Yamaha NMAX
                    </option>
                    <option value="Honda Brio" <?php echo ($kendaraan == 'Honda Brio') ? 'selected' : ''; ?>>Honda Brio
                    </option>
                    <option value="Toyota Avanza" <?php echo ($kendaraan == 'Toyota Avanza') ? 'selected' : ''; ?>>Toyota
                        Avanza
                    </option>
                    <option value="Daihatsu Luxio" <?php echo ($kendaraan == 'Daihatsu Luxio') ? 'selected' : ''; ?>>
                        Daihatsu Luxio</option>
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