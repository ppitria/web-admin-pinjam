<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("location: login.php");
    exit();
}

require_once 'connection.php';
$collectionName = 'kendaraan';
$collection = $database->selectCollection($collectionName);
?>

<?php include("inc_header.php"); ?>

<?php
$nama = "";
$foto = "";
$foto_name = "";
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
        $nama = $document['nama'];
        $foto = $document['foto'];
        $status = $document['status'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $status = $_POST['status'];

    if ($nama == '' or $status == '') {
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

            // Hapus foto yang lama
            $collection->updateOne(['_id' => new MongoDB\BSON\ObjectID($id)], ['$unset' => ['foto' => '']]);
            @unlink($direktori . "/$foto");

            // Upload foto baru
            $foto_name = "kendaraan_" . time() . "_" . $foto_name;
            move_uploaded_file($foto_file, $direktori . "/" . $foto_name);

            $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectID($id)],
                ['$set' => ['nama' => $nama, 'foto' => $foto_name, 'status' => $status, 'tgl_isi' => new MongoDB\BSON\UTCDateTime()]]
            );
        } else {
            $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectID($id)],
                ['$set' => ['nama' => $nama, 'status' => $status, 'tgl_isi' => new MongoDB\BSON\UTCDateTime()]]
            );
        }

        $sukses = "Sukses memasukkan data";
    }
}
?>


<div class="container">
    <h1 class="title">Halaman List Kendaraan</h1>
    <p>
        <a href="kendaraan_input.php">
            <input type="button" class="btn all-button" value="Masukkan Kendaraan Baru" />
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
                <th class="col-2">Foto</th>
                <th>Nama</th>
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
                            ['nama' => new MongoDB\BSON\Regex($kunci, 'i')],
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
                        <?php
                        $foto_path = "image/kendaraan/" . $document['foto'];
                        echo '<img src="' . $foto_path . '" style="max-height:100px; max-width:100px" />';
                        ?>
                    </td>
                    <td>
                        <?php echo $document['nama'] ?>
                    </td>
                    <td>
                        <?php echo $document['status'] ?>
                    </td>
                    <td>
                        <a href="kendaraan_input.php?id=<?php echo $document['_id'] ?>">
                            <span class="badge bg-edit">Edit</span>
                        </a>

                        <a href="kendaraan.php?op=delete&id=<?php echo $document['_id'] ?>"
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
                        href="kendaraan.php?katakunci=<?php echo $katakunci ?>&cari=<?php echo $cari ?>&page=<?php echo $i ?>">
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