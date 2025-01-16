<?php
session_start();
header('Content-Type: application/json');
require_once "../config/koneksi.php";

if (isset($_SESSION) && $_SESSION["user_role"] === "Admin") {
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
    if (isset($_GET["list_pengumuman"])) {
        try {
            $showSql = "SELECT * FROM pengumuman ORDER BY id_pengumuman DESC";
            $stmt = $pdo->query($showSql);
    
            // get result
            $results = $stmt->fetchAll();
            if (empty($results)) {
                echo json_encode(["kesalahan" => "Data tidak tersedia"]);
            } else {
                $pengumuman = [];
                foreach ($results as $row) {
                    $pengumuman[] = [
                        "id_pengumuman" => $row["id_pengumuman"],
                        "judul" => $row["judul"],
                        "isi_pengumuman" => $row["isi_pengumuman"]
                    ];
                }
                echo json_encode($pengumuman, JSON_PRETTY_PRINT);
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
} else if(isset($_SESSION) && $_SESSION["user_role"] === "Calon") {
    if (isset($_GET["list_pengumuman"])) {
        try {
            $showSql = "SELECT * FROM pengumuman ORDER BY id_pengumuman DESC";
            $stmt = $pdo->query($showSql);
    
            // get result
            $results = $stmt->fetchAll();
            if (empty($results)) {
                echo json_encode(["kesalahan" => "Data tidak tersedia"]);
            } else {
                $listPengumuman = [];
                foreach ($results as $row) {
                    $listPengumuman[] = [
                        "judul" => $row["judul"],
                        "isi" => $row["isi_pengumuman"]
                    ];
                }
                echo json_encode($listPengumuman, JSON_PRETTY_PRINT);
            }
        } catch (PDOException $th) {
            echo json_encode(["status" => "gagal", "keterangan" => $e]);
        }
    }
} else {
    echo json_encode(["status" => "error", "pesan" => "Anda tidak memiliki akses untuk ini"]);
    exit();
}
?>