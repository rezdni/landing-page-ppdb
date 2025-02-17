<?php
session_start();
header('Content-Type: application/json');
require_once "../config/koneksi.php";

// List calon siswa
if (isset($_GET["list_calon"])) {
    if (isset($_SESSION) && isset($_SESSION["user_role"]) && $_SESSION["user_role"] === "Admin") {
        try {
            // Jika di limit
            if (isset($_GET["limit"])) {
                $limit = htmlspecialchars($_GET["limit"]);
    
                $sql = "SELECT * FROM pendaftaran LIMIT :limit";
                $stmt = $pdo->prepare($sql);
    
                $proses = ["limit" => $limit];
                $stmt->execute($proses);
    
                $hasil = $stmt->fetchAll();
    
                if (empty($hasil)) {
                    echo json_encode([
                        "status" => "error",
                        "code" => 404,
                        "message" => "Data tidak tersedia"
                    ], JSON_PRETTY_PRINT);
                } else {
                    $hasilData = [];
                    $nomor = 1;
                    foreach ($hasil as $baris) {
                        $hasilData[] = [
                            "nomor" => $nomor,
                            "id" => $baris["id_calon"],
                            "nama" => $baris["nama_calon_siswa"],
                            "lahir" => $baris["tanggal_lahir"],
                            "nis" => $baris["no_nis"],
                            "kelamin" => $baris["jenis_kelamin"],
                            "agama" => $baris["agama"],
                            "asal" => $baris["sekolah_asal"],
                            "kewarganegaraan" => $baris["kewarganegaraan"],
                            "darah" => $baris["golongan_darah"],
                            "alamat" => $baris["alamat_tinggal"],
                            "provinsi" => $baris["provinsi"],
                            "kabupaten" => $baris["kota_kabupaten"],
                            "kecamatan" => $baris["kecamatan"],
                            "kelurahan" => $baris["kelurahan"],
                            "pos" => $baris["kode_post"],
                            "daftar" => $baris["tanggal_daftar"],
                            "telepon" => $baris["no_telepon"],
                            "jurusan" => $baris["jurusan"],
                            "gelombang" => $baris["gelombang"]
                        ];
                        $nomor++;
                    }

                    echo json_encode([
                        "status" => "success",
                        "code" => 200,
                        "message" => "Data berhasil diambil",
                        "data" => $hasilData
                    ], JSON_PRETTY_PRINT);
                }
            } else {
                // Jika tidak di limit
                $sql = "SELECT * FROM pendaftaran";
                $stmt = $pdo->query($sql);
    
                $hasil = $stmt->fetchAll();
    
                if (empty($hasil)) {
                    echo json_encode([
                        "status" => "error",
                        "code" => 404,
                        "message" => "Data tidak tersedia"
                    ], JSON_PRETTY_PRINT);
                } else {
                    $hasilData = [];
                    $nomor = 1;
                    foreach ($hasil as $baris) {
                        $hasilData[] = [
                            "nomor" => $nomor,
                            "id" => $baris["id_calon"],
                            "nama" => $baris["nama_calon_siswa"],
                            "lahir" => $baris["tanggal_lahir"],
                            "nis" => $baris["no_nis"],
                            "kelamin" => $baris["jenis_kelamin"],
                            "agama" => $baris["agama"],
                            "asal" => $baris["sekolah_asal"],
                            "kewarganegaraan" => $baris["kewarganegaraan"],
                            "darah" => $baris["golongan_darah"],
                            "alamat" => $baris["alamat_tinggal"],
                            "provinsi" => $baris["provinsi"],
                            "kabupaten" => $baris["kota_kabupaten"],
                            "kecamatan" => $baris["kecamatan"],
                            "kelurahan" => $baris["kelurahan"],
                            "pos" => $baris["kode_post"],
                            "daftar" => $baris["tanggal_daftar"],
                            "telepon" => $baris["no_telepon"],
                            "jurusan" => $baris["jurusan"],
                            "gelombang" => $baris["gelombang"]
                        ];
                        $nomor++;
                    }

                    echo json_encode([
                        "status" => "success",
                        "code" => 200,
                        "message" => "Data berhasil diambil",
                        "data" => $hasilData
                    ], JSON_PRETTY_PRINT);
                }
            }
            
        } catch (Throwable $e) {
            echo json_encode([
                "status" => "error",
                "code" => 500,
                "message" => $e
            ], JSON_PRETTY_PRINT);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "code" => 403,
            "message" => "Anda tidak memiliki akses untuk ini",
        ], JSON_PRETTY_PRINT);
        exit();
    }
}

// Minta data calon
if (isset($_GET["id-calon"])) {
    $idCalon = htmlspecialchars($_GET["id-calon"]);

    // Minta data ortu calon
    if (isset($_GET["data-ortu"])) {
        $sql = "SELECT * FROM orang_tua WHERE id_calon = :idCalon";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["idCalon" => $idCalon]);
        $hasil = $stmt->fetch();
    
        if (empty($hasil)) {
            echo json_encode([
                "status" => "error",
                "code" => 404,
                "message" => "Data tidak tersedia"
            ], JSON_PRETTY_PRINT);
        } else {
            $data = [
                $hasil["nama_orang_tua"],
                $hasil["nomor_telepon_orang_tua"],
                $hasil["pekerjaan_orang_tua"],
                $hasil["alamat_orang_tua"]
            ];
    
            echo json_encode([
                "status" => "success",
                "code" => 200,
                "message" => "Data berhasil diambil",
                "data" => $data
            ], JSON_PRETTY_PRINT);
        }
    } else if(isset($_GET["berkas"])) {
        // Minta dokumen pendukung
        $sql = "SELECT * FROM dokumen_pendaftaran WHERE id_calon = :idCalon";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["idCalon" => $idCalon]);
        $hasil = $stmt->fetchAll();
    
        if (empty($hasil)) {
            echo json_encode([
                "status" => "error",
                "code" => 404,
                "message" => "Data tidak tersedia"
            ], JSON_PRETTY_PRINT);
        } else {
            $data = [];
            
            foreach ($hasil as $list) {
                $data[] = [
                    "jenis" => $list["jenis_dokumen"],
                    "path" => $list["file_path"]
                ];
            }
    
            echo json_encode([
                "status" => "success",
                "code" => 200,
                "message" => "Data berhasil diambil",
                "data" => $data
            ], JSON_PRETTY_PRINT);
        }
    } else {
        // Minta data calon siswa
        $sql = "SELECT * FROM pendaftaran WHERE id_calon = :idCalon";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["idCalon" => $idCalon]);
        $hasil = $stmt->fetch();
    
        if (empty($hasil)) {
            echo json_encode([
                "status" => "error",
                "code" => 404,
                "message" => "Data tidak tersedia"
            ], JSON_PRETTY_PRINT);
        } else {
            $data = [
                $hasil["nama_calon_siswa"],
                $hasil["tanggal_lahir"],
                $hasil["no_nis"],
                $hasil["jenis_kelamin"],
                $hasil["agama"],
                $hasil["sekolah_asal"],
                $hasil["kewarganegaraan"],
                $hasil["golongan_darah"],
                $hasil["alamat_tinggal"],
                $hasil["provinsi"],
                $hasil["kota_kabupaten"],
                $hasil["kecamatan"],
                $hasil["kelurahan"],
                $hasil["kode_post"],
                $hasil["no_telepon"],
                $hasil["jurusan"],
                $hasil["gelombang"]
            ];
    
            echo json_encode([
                "status" => "success",
                "code" => 200,
                "message" => "Data berhasil diambil",
                "data" => $data
            ], JSON_PRETTY_PRINT);
        }
    }

}

// Hapus calon siswa
if (isset($_POST["removeCalon"])) {
    if (isset($_SESSION) && $_SESSION["user_role"] === "Admin") {
        $calonId = htmlspecialchars($_POST["calonId"]);
        
        try {
            // Cek dokumen calon
            $stmt = $pdo->prepare("SELECT file_path FROM dokumen_pendaftaran WHERE id_calon = :idCalon");
            $stmt->execute(["idCalon" => $calonId]);

            $dokumen = $stmt->fetchAll();

            // Hapus dokumen calon
            if (!empty($dokumen)) {
                foreach ($dokumen as $row) {
                    $sumberFile = $_SERVER["DOCUMENT_ROOT"] . $row["file_path"];
                    if (file_exists($sumberFile)) {
                        unlink($sumberFile);
                    }
                }
            }

            // Hapus calon
            $deleteSql = "DELETE FROM pendaftaran WHERE `id_calon` = :idCalon";
            $stmt = $pdo->prepare($deleteSql);
        
            $deleteParam = ["idCalon" => $calonId];
        
            $stmt->execute($deleteParam);

            echo json_encode([
                "status" => "success",
                "code" => 200,
                "message" => "Berhasil menghapus calon siswa"
            ], JSON_PRETTY_PRINT);

        } catch (Throwable $e) {
            echo json_encode([
                "status" => "error",
                "code" => 500,
                "message" => $e
            ], JSON_PRETTY_PRINT);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "code" => 403,
            "message" => "Anda tidak memiliki akses untuk ini",
        ], JSON_PRETTY_PRINT);
        exit();
    }
}
?>