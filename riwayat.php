<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("location: login.php");
    exit();
}

require_once 'connection.php';
$collectionName = 'riwayat';
$collection = $database->selectCollection($collectionName);
?>

<?php include("inc_header.php"); ?>

<?php
$peminjam = "";
$kendaraan = "";
$status = "";

$error = "";
$sukses = "";
$katakunci = isset($_GET['katakunci']) ? $_GET['katakunci'] : '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max(1, $page);

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
    } else {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $peminjam = $_POST['peminjam'];
    $status = $_POST['status'];

    if ($nama == '' or $status == '') {
        $error = "Silakan masukkan semua data yakni adalah data peminjam dan statusnya.";
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
    <h1 class="title">Halaman Riwayat Peminjaman</h1>
    <p>
        <a href="riwayat_input.php">
            <input type="button" class="btn all-button" value="Masukkan Riwayat Baru" />
        </a>
    </p>
    <?php
    if ($sukses) {
        ?>
        <div class="alert alert-primary" role="alert">
            <?php echo $sukses ?>
        </div>
        <?php
    }
    ?>
    <form class="row g-3" method="get">
        <div class="col-auto">
            <input type="text" class="form-control" placeholder="Masukkan Kata Kunci" name="katakunci"
                value="<?php echo $katakunci ?>" />
        </div>
        <div class="col-auto">
            <input type="submit" name="cari" value="Cari" class="btn bg-edit" />
        </div>
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="col-1">#</th>
                <th class="col-2">Peminjam</th>
                <th>Kendaraan</th>
                <th>Status</th>
                <th class="col-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sqltambahan = [];
            $per_halaman = 5;

            if ($katakunci != '') {
                $array_katakunci = explode(" ", $katakunci);
                foreach ($array_katakunci as $kunci) {
                    $sqltambahan[] = [
                        '$or' => [
                            ['peminjam' => new MongoDB\BSON\Regex($kunci, 'i')],
                            ['kendaraan' => new MongoDB\BSON\Regex($kunci, 'i')],
                            ['status' => new MongoDB\BSON\Regex($kunci, 'i')]
                        ]
                    ];
                }
            }

            $pipeline = [];

            if (!empty($sqltambahan)) {
                $pipeline[] = ['$match' => ['$or' => $sqltambahan]];
            }

            $pipeline[] = ['$sort' => ['_id' => -1]];
            $pipeline[] = ['$skip' => ($page - 1) * $per_halaman];
            $pipeline[] = ['$limit' => $per_halaman];

            $cursor = $collection->aggregate($pipeline);

            $page = max(1, $page);

            $nomor = $page > 1 ? ($page * $per_halaman) - $per_halaman + 1 : 1;

            foreach ($cursor as $document) {
                ?>
                <tr>
                    <td>
                        <?php echo $nomor++ ?>
                    </td>
                    <td>
                        <?php echo $document['peminjam'] ?>
                    </td>
                    <td>
                        <?php echo $document['kendaraan'] ?>
                    </td>
                    <td>
                        <?php echo $document['status'] ?>
                    </td>
                    <td>
                        <a href="riwayat_input.php?id=<?php echo $document['_id'] ?>">
                            <span class="badge bg-edit">Edit</span>
                        </a>

                        <a href="riwayat.php?op=delete&id=<?php echo $document['_id'] ?>"
                            onclick="return confirm('Apakah yakin akan menghapus data?')">
                            <span class="badge all-button">Delete</span>
                        </a>
                    </td>

                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php
            $katakunci = isset($_GET['katakunci']) ? $_GET['katakunci'] : '';
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $page = max(1, $page);
            $cari = isset($_GET['cari']) ? $_GET['cari'] : "";


            $total_documents = $collection->countDocuments();
            $pages = ceil($total_documents / $per_halaman);

            for ($i = 1; $i <= $pages; $i++) {
                ?>
                <li class="page-item">
                    <a class="page-link"
                        href="riwayat.php?katakunci=<?php echo $katakunci ?>&cari=<?php echo $cari ?>&page=<?php echo $i ?>">
                        <?php echo $i ?>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>
    </nav>
</div>
<?php include("inc_footer.php") ?>