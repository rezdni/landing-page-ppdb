<?php
require_once "../config/koneksi.php";

// Buat berita
if (isset($_POST["buatBerita"])) {
    // variable
    $judulBerita = htmlspecialchars($_POST["judulBerita"]);
    $isiBerita = htmlspecialchars($_POST["isiBerita"]);

    try {
        // Prepare query
        $insertSql = "INSERT INTO pengumuman (`id_pengumuman`, `judul`, `isi_pengumuman`) VALUES (null, :judulBerita, :isiBerita)";
        $insertStmt = $pdo->prepare($insertSql);

        $data = [
            'judulBerita' => $judulBerita,
            'isiBerita' => $isiBerita
        ];
        
        // Insert to database
        $insertStmt->execute($data);
        echo json_encode(["status" => "berhasil"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "gagal", "keterangan" => $e]);
    }
}

// List pengumuman
if (isset($_GET["listPengumuman"])) {
    // List user account
    try {
        $showSql = "SELECT * FROM pengumuman ORDER BY id_pengumuman DESC";
        $stmt = $pdo->query($showSql);

        // get result
        $results = $stmt->fetchAll();
        if (empty($results)) {
            ?>
                <h2>Data tidak tersedia</h2>
            <?php
        } else {
            foreach ($results as $row) {
                ?>
                    <div class="berita hasil">
                        <h3><?php echo $row["judul"]; ?></h3>
                        <p><?php echo $row["isi_pengumuman"]; ?></p>
                        <button name="hapus-berita" id="hapus-berita" onclick="hapusBerita('<?php echo $row['id_pengumuman']; ?>')">Hapus</button>
                    </div>
                <?php
            }
        }
    } catch (PDOException $th) {
        echo json_encode(["status" => "gagal", "keterangan" => $e]);
    }
}

// hapus akun
if (isset($_POST["hapusBerita"])) {
    $idPengumuman = htmlspecialchars($_POST["idBerita"]);

    try {
        $deleteSql = "DELETE FROM pengumuman WHERE id_pengumuman = :idPengumuman";
        $stmt = $pdo->prepare($deleteSql);

        $deleteParam = ["idPengumuman" => $idPengumuman];

        $stmt->execute($deleteParam);
        echo json_encode(["status" => "berhasil"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "gagal", "keterangan" => $e]);
    }
}
?>