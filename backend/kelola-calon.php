<?php
session_start();
header('Content-Type: application/json');
require_once "../config/koneksi.php";

if (isset($_SESSION) && $_SESSION["user_role"] === "Admin") {
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
                                "id_calon" => $baris["id_calon"],
                                "nama_calon_siswa" => $baris["nama_calon_siswa"],
                                "tanggal_lahir" => $baris["tanggal_lahir"],
                                "no_nis" => $baris["no_nis"],
                                "jenis_kelamin" => $baris["jenis_kelamin"],
                                "agama" => $baris["agama"],
                                "sekolah_asal" => $baris["sekolah_asal"],
                                "kewarganegaraan" => $baris["kewarganegaraan"],
                                "golongan_darah" => $baris["golongan_darah"],
                                "alamat_tinggal" => $baris["alamat_tinggal"],
                                "provinsi" => $baris["provinsi"],
                                "kota_kabupaten" => $baris["kota_kabupaten"],
                                "kecamatan" => $baris["kecamatan"],
                                "kelurahan" => $baris["kelurahan"],
                                "kode_post" => $baris["kode_post"],
                                "tanggal_daftar" => $baris["tanggal_daftar"],
                                "no_telepon" => $baris["no_telepon"],
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
                                "id_calon" => $baris["id_calon"],
                                "nama_calon_siswa" => $baris["nama_calon_siswa"],
                                "tanggal_lahir" => $baris["tanggal_lahir"],
                                "no_nis" => $baris["no_nis"],
                                "jenis_kelamin" => $baris["jenis_kelamin"],
                                "agama" => $baris["agama"],
                                "sekolah_asal" => $baris["sekolah_asal"],
                                "kewarganegaraan" => $baris["kewarganegaraan"],
                                "golongan_darah" => $baris["golongan_darah"],
                                "alamat_tinggal" => $baris["alamat_tinggal"],
                                "provinsi" => $baris["provinsi"],
                                "kota_kabupaten" => $baris["kota_kabupaten"],
                                "kecamatan" => $baris["kecamatan"],
                                "kelurahan" => $baris["kelurahan"],
                                "kode_post" => $baris["kode_post"],
                                "tanggal_daftar" => $baris["tanggal_daftar"],
                                "no_telepon" => $baris["no_telepon"],
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
    if (isset($_GET["nis"])) {
        $nisCalon = htmlspecialchars($_GET["nis"]);
        try {
            // Dapatkan id calon
            $sql = "SELECT id_calon FROM pendaftaran WHERE no_nis = :nis";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["nis" => $nisCalon]);
            $result = $stmt->fetch();
            $idCalon = $result["id_calon"];
    
            // Minta data ortu calon
            if (isset($_GET["data_ortu"])) {
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
                        "nama_orang_tua" => $hasil["nama_orang_tua"],
                        "nomor_telepon_orang_tua" => $hasil["nomor_telepon_orang_tua"],
                        "pekerjaan_orang_tua" => $hasil["pekerjaan_orang_tua"],
                        "alamat_orang_tua" => $hasil["alamat_orang_tua"]
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
                            "jenis_dokumen" => $list["jenis_dokumen"],
                            "file_path" => $list["file_path"]
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
                        "nama_calon_siswa" => $hasil["nama_calon_siswa"],
                        "tanggal_lahir" => $hasil["tanggal_lahir"],
                        "no_nis" => $hasil["no_nis"],
                        "jenis_kelamin" => $hasil["jenis_kelamin"],
                        "agama" => $hasil["agama"],
                        "sekolah_asal" => $hasil["sekolah_asal"],
                        "kewarganegaraan" => $hasil["kewarganegaraan"],
                        "golongan_darah" => $hasil["golongan_darah"],
                        "alamat_tinggal" => $hasil["alamat_tinggal"],
                        "provinsi" => $hasil["provinsi"],
                        "kota_kabupaten" => $hasil["kota_kabupaten"],
                        "kecamatan" => $hasil["kecamatan"],
                        "kelurahan" => $hasil["kelurahan"],
                        "kode_post" => $hasil["kode_post"],
                        "no_telepon" => $hasil["no_telepon"],
                        "jurusan" => $hasil["jurusan"],
                        "gelombang" => $hasil["gelombang"]
                    ];
            
                    echo json_encode([
                        "status" => "success",
                        "code" => 200,
                        "message" => "Data berhasil diambil",
                        "data" => $data
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
    }
    
    // Hapus calon siswa
    if (isset($_POST["removeCalon"])) {
        if (isset($_SESSION) && $_SESSION["user_role"] === "Admin") {
            $nisCalon = htmlspecialchars($_POST["nis"]);
            
            try {
                // Dapatkan id calon
                $sql = "SELECT id_calon FROM pendaftaran WHERE no_nis = :nis";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(["nis" => $nisCalon]);
                $result = $stmt->fetch();
                $idCalon = $result["id_calon"];
    
                // Cek dokumen calon
                $stmt = $pdo->prepare("SELECT file_path FROM dokumen_pendaftaran WHERE id_calon = :idCalon");
                $stmt->execute(["idCalon" => $idCalon]);
    
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
                $deleteSql = "DELETE FROM pendaftaran WHERE `no_nis` = :nis";
                $stmt = $pdo->prepare($deleteSql);
            
                $deleteParam = ["nis" => $nisCalon];
            
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

    // Kirim data
    // Data calon
    if (isset($_POST["daftar_calon"])) {
        // Ambil datanya
        $nama_calon = htmlspecialchars($_POST["nama-siswa"]);
        $tanggal_lahir = htmlspecialchars($_POST["tanggal-lahir"]);
        $no_nis = htmlspecialchars($_POST["no-nis"]);
        $jenis_kelamin = htmlspecialchars($_POST["jenis-kelamin"]);
        $agama = htmlspecialchars($_POST["agama"]);
        $kewarganegaraan = htmlspecialchars($_POST["kewarganegaraan"]);
        $asal_sekolah = htmlspecialchars($_POST["asal-sekolah"]);
        $golongan_darah = htmlspecialchars($_POST["golongan-darah"]);
        $alamat = htmlspecialchars($_POST["alamat-tinggal"]);
        $provinsi = htmlspecialchars($_POST["provinsi"]);
        $kota_kabupaten = htmlspecialchars($_POST["kota-kabupaten"]);
        $kecamatan = htmlspecialchars($_POST["kecamatan"]);
        $kelurahan = htmlspecialchars($_POST["kelurahan"]);
        $kode_pos = htmlspecialchars($_POST["kode-post"]);
        $no_telepon = htmlspecialchars($_POST["no-telepon"]);
        $jurusan = htmlspecialchars($_POST["jurusan"]);
        $gelombang = htmlspecialchars($_POST["gelombang"]);

        // ambil nim calon saat ini jika ada
        $defaultNis = null;
        (isset($_GET["default_nis"])) ? $defaultNis = htmlspecialchars($_GET["default_nis"]) : $defaultNis = null;

        try {
            // Cek apakah sudah mendaftar
            $sql = "SELECT * FROM pendaftaran WHERE no_nis = :no_nis";
            $cekCalon = $pdo->prepare($sql);
            $cekCalon->execute(["no_nis" => $defaultNis]);
            $hasil = $cekCalon->fetch();

            if (empty($hasil) || $hasil["id_calon"] === null) {
                // Jika belum mendaftar, daftarkan calon
                $sql = "INSERT INTO pendaftaran (nama_calon_siswa, tanggal_lahir, no_nis, jenis_kelamin, agama, sekolah_asal, kewarganegaraan, golongan_darah, alamat_tinggal, provinsi, kota_kabupaten, kecamatan, kelurahan, kode_post, tanggal_daftar, no_telepon, jurusan, gelombang) VALUES (:nama_calon_siswa, :tanggal_lahir, :no_nis, :jenis_kelamin, :agama, :sekolah_asal, :kewarganegaraan, :golongan_darah, :alamat_tinggal, :provinsi, :kota_kabupaten, :kecamatan, :kelurahan, :kode_post, :tanggal_daftar, :no_telepon, :jurusan, :gelombang)";
                $stmt = $pdo->prepare($sql);

                // Masukan ke database
                $stmt->execute([
                    'nama_calon_siswa' => $nama_calon,
                    'tanggal_lahir' => $tanggal_lahir,
                    'no_nis' => $no_nis,
                    'jenis_kelamin' => $jenis_kelamin,
                    'agama' => $agama,
                    'sekolah_asal' => $asal_sekolah,
                    'kewarganegaraan' => $kewarganegaraan,
                    'golongan_darah' => $golongan_darah,
                    'alamat_tinggal' => $alamat,
                    'provinsi' => $provinsi,
                    'kota_kabupaten' => $kota_kabupaten,
                    'kecamatan' => $kecamatan,
                    'kelurahan' => $kelurahan,
                    'kode_post' => $kode_pos,
                    'tanggal_daftar' => date("Y-m-d"),
                    'no_telepon' => $no_telepon,
                    'jurusan' => $jurusan,
                    'gelombang' => $gelombang,
                ]);

                // Dapatkan ID calon yang baru ditambahkan
                // $calon_id = $pdo->lastInsertId();

                echo json_encode([
                    "status" => "success",
                    "code" => 200,
                    "message" => "Pendaftaran berhasil",
                    "data" => [
                        "no_nis" => $no_nis
                    ]
                ], JSON_PRETTY_PRINT);

            } else {
                // Jika sudah mendaftar, ubah perbarui datanya
                $sql = "UPDATE pendaftaran SET `nama_calon_siswa`= :nama_calon_siswa, `tanggal_lahir` = :tanggal_lahir, `no_nis` = :no_nis, `jenis_kelamin` = :jenis_kelamin, `agama` = :agama, `sekolah_asal` = :sekolah_asal, `kewarganegaraan` = :kewarganegaraan, `golongan_darah` = :golongan_darah, `alamat_tinggal` = :alamat_tinggal, `provinsi` = :provinsi, `kota_kabupaten` = :kota_kabupaten, `kecamatan` = :kecamatan, `kelurahan` = :kelurahan, `kode_post` = :kode_post, `no_telepon` = :no_telepon, `jurusan` = :jurusan, `gelombang` = :gelombang WHERE id_calon = :id_calon";
                $stmt = $pdo->prepare($sql);
                
                // Masukan ke database
                $stmt->execute([
                    'nama_calon_siswa' => $nama_calon,
                    'tanggal_lahir' => $tanggal_lahir,
                    'no_nis' => $no_nis,
                    'jenis_kelamin' => $jenis_kelamin,
                    'agama' => $agama,
                    'sekolah_asal' => $asal_sekolah,
                    'kewarganegaraan' => $kewarganegaraan,
                    'golongan_darah' => $golongan_darah,
                    'alamat_tinggal' => $alamat,
                    'provinsi' => $provinsi,
                    'kota_kabupaten' => $kota_kabupaten,
                    'kecamatan' => $kecamatan,
                    'kelurahan' => $kelurahan,
                    'kode_post' => $kode_pos,
                    'no_telepon' => $no_telepon,
                    'jurusan' => $jurusan,
                    'gelombang' => $gelombang,
                    'id_calon' => $hasil["id_calon"]
                ]);

                echo json_encode([
                    "status" => "success",
                    "code" => 200,
                    "message" => "Data calon berhasil diubah",
                    "data" => [
                        "no_nis" => $no_nis
                    ]
                ], JSON_PRETTY_PRINT);

            }
            
        } catch (PDOException $e) {
            echo json_encode([
                "status" => "error",
                "code" => 500,
                "message" => $e
            ], JSON_PRETTY_PRINT);
        }
    }

    // Data orang tua
    if (isset($_POST["orangtua"])) {
        $nama_orang_tua = htmlspecialchars($_POST['nama-orang-tua']);
        $no_telepon_orangtua = htmlspecialchars($_POST['nomor-telepon-orang-tua']);
        $pekerjaan_orangtua = htmlspecialchars($_POST['pekerjaan-orang-tua']);
        $alamat_orangtua = htmlspecialchars($_POST['alamat-orang-tua']);

        // ambil nim calon saat ini jika ada
        $defaultNis = null;
        (isset($_GET["default_nis"])) ? $defaultNis = htmlspecialchars($_GET["default_nis"]) : $defaultNis = null;

        try {
            // cek akun pengguna
            $sql = "SELECT * FROM pendaftaran WHERE no_nis = :no_nis";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                "no_nis" => $defaultNis
            ]);

            $result = $stmt->fetch();

            if (empty($result) || $result["id_calon"] === null) {
                echo json_encode([
                    "status" => "error",
                    "code" => 404,
                    "message" => "Data tidak ditemukan"
                ], JSON_PRETTY_PRINT);
                exit();
            } else {
                // Cek apakah calon sudah memasukan data orang tua
                $id_calon = $result["id_calon"];

                $sql = "SELECT * FROM orang_tua WHERE id_calon = :id_calon";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(["id_calon" => $id_calon]);
                $hasil = $stmt->fetch();

                if (empty($hasil)) {
                    // Jika belum, Masukan data ortu ke database
                    $sql = "INSERT INTO orang_tua (id_calon, nama_orang_tua, pekerjaan_orang_tua, nomor_telepon_orang_tua, alamat_orang_tua) VALUES (:id_calon, :nama_orang_tua, :pekerjaan_orang_tua, :nomor_telepon_orang_tua, :alamat_orang_tua)";
                    $stmt = $pdo->prepare($sql);
        
                    $stmt->execute([
                        'id_calon' => $id_calon,
                        'nama_orang_tua' => $nama_orang_tua,
                        'pekerjaan_orang_tua' => $pekerjaan_orangtua,
                        'nomor_telepon_orang_tua' => $no_telepon_orangtua,
                        'alamat_orang_tua' => $alamat_orangtua
                    ]);
        
                    echo json_encode([
                        "status" => "success",
                        "code" => 200,
                        "message" => "Data orang tua ditambah",
                        "data" => [
                            "no_nis" => $defaultNis
                        ]
                    ], JSON_PRETTY_PRINT);
                } else {
                    // Jika sudah, ubah data ortu ke database
                    $sql = "UPDATE orang_tua SET `nama_orang_tua` = :nama_orang_tua, `pekerjaan_orang_tua` = :pekerjaan_orang_tua, `nomor_telepon_orang_tua` = :nomor_telepon_orang_tua, `alamat_orang_tua` = :alamat_orang_tua WHERE id_calon = :id_calon";
                    $stmt = $pdo->prepare($sql);
        
                    $stmt->execute([
                        'id_calon' => $id_calon,
                        'nama_orang_tua' => $nama_orang_tua,
                        'pekerjaan_orang_tua' => $pekerjaan_orangtua,
                        'nomor_telepon_orang_tua' => $no_telepon_orangtua,
                        'alamat_orang_tua' => $alamat_orangtua
                    ]);
        
                    echo json_encode([
                        "status" => "success",
                        "code" => 200,
                        "message" => "Data orang tua diubah",
                        "data" => [
                            "no_nis" => $defaultNis
                        ]
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
    }

    // Dokumentasi berkas
    if (isset($_POST["berkas"])) {
        // Konfigurasi unggahan file
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_file_size = 2 * 1024 * 1024; // 2MB
        $upload_folder = '../uploads/documents/';

        // Periksa apakah folder tujuan ada
        if (!is_dir($upload_folder)) {
            echo json_encode([
                "status" => "error",
                "code" => "FOLDER_NOT_FOUND",
                "message" => "Folder tujuan tidak tersedia",
                "details" => [
                    "folder_path" => "/uploads/documents/",
                    "issue" => "Folder tidak ditemukan atau tidak dapat diakses"
                ]
            ], JSON_PRETTY_PRINT);
            exit();
        }
        
        // Jenis file yang diunggah
        $file_types = [
            'ijasah' => $_FILES['ijasah'],
            'foto_profil' => $_FILES['foto-profil'],
            'dokumen_lainnya' => $_FILES['dokumen-lainnya']
        ];

        // ambil nim calon saat ini jika ada
        $defaultNis = null;
        (isset($_GET["default_nis"])) ? $defaultNis = htmlspecialchars($_GET["default_nis"]) : $defaultNis = null;
        
        try {
            // cek akun pengguna
            $sql = "SELECT * FROM pendaftaran WHERE no_nis = :no_nis";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                "no_nis" => $defaultNis
            ]);
    
            $result = $stmt->fetch();
            if (empty($result) || $result["id_calon"] === null) {
                echo json_encode([
                    "status" => "error",
                    "code" => 404,
                    "message" => "Data tidak ditemukan"
                ], JSON_PRETTY_PRINT);
            } else {
                $id_calon = $result["id_calon"];
                $fileDiUpload = 0;
                $jsonMsg = [];

                foreach ($file_types as $jenis_file => $file) {
                    // Cek apakah calon sudah mengunggah berkas
                    $sql = "SELECT * FROM dokumen_pendaftaran WHERE id_calon = :id_calon AND jenis_dokumen = :jenis_dokumen";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        "id_calon" => $id_calon,
                        "jenis_dokumen" => $jenis_file
                    ]);
                    $hasil = $stmt->fetch();

                    if (empty($hasil)) {
                        // Jika belum
                        // Proses setiap file unggahan
                        if ($file['error'] === 1) {
                            echo json_encode([
                                "status" => "error",
                                "code" => "FILE_TOO_LARGE",
                                "message" => "Ukuran file terlalu besar.",
                                "details" => [
                                    "file" => $jenis_file,
                                    "issue" => "Ukuran file untuk $jenis_file terlalu besar."
                                ]
                            ], JSON_PRETTY_PRINT);
                            exit();
                        }
                        if ($file['error'] !== 4) {
                            $file_name = $file['name'];
                            $file_tmp = $file['tmp_name'];
                            $file_size = $file['size'];
                            $file_error = $file['error'];
                
                            // Ekstensi file
                            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                
                            // Validasi file
                            if ($file_error === 0) {
                                if (!in_array($file_extension, $allowed_extensions)) {
                                    throw new Exception(json_encode([
                                        "status" => "error",
                                        "code" => "EXTENSION_NOT_ALLOWED",
                                        "message" => "Ekstensi file tidak diperbolehkan.",
                                        "details" => [
                                            "file", $jenis_file,
                                            "issue" => "Ekstensi file untuk $jenis_file tidak diperbolehkan."
                                        ]
                                    ], JSON_PRETTY_PRINT));
                                }
                
                                if ($file_size > $max_file_size) {
                                    throw new Exception(json_encode([
                                        "status" => "error",
                                        "code" => "FILE_TOO_LARGE",
                                        "message" => "Ukuran file terlalu besar.",
                                        "details" => [
                                            "file" => $jenis_file,
                                            "issue" => "Ukuran file untuk $jenis_file terlalu besar."
                                        ]
                                    ], JSON_PRETTY_PRINT));
                                }
                
                                // Buat nama file unik
                                $unique_file_name = uniqid($jenis_file . '_', true) . '.' . $file_extension;
                                $file_path = $upload_folder . $unique_file_name;
                
                                // Pindahkan file ke folder tujuan
                                if (move_uploaded_file($file_tmp, $file_path)) {
                                    $stmt = $pdo->prepare("INSERT INTO dokumen_pendaftaran (id_calon, jenis_dokumen, file_path) VALUES (:id_calon, :jenis_dokumen, :file_path)");
                                    $stmt->execute([
                                        ':id_calon' => $id_calon,
                                        ':jenis_dokumen' => $jenis_file,
                                        ':file_path' => '/uploads/documents/' . $unique_file_name
                                    ]);

                                    $fileDiUpload++;
                                } else {
                                    throw new Exception(json_encode([
                                        "status" => "error",
                                        "code" => "FOLDER_NOT_FOUND",
                                        "message" => "Gagal memindahkan file ke folder tujuan.",
                                        "details" => [
                                            "file" => $jenis_file,
                                            "issue" => "Gagal memindahkan file dikarenakan folder tujuan tidak tersedia atau tidak dapat diakses."
                                        ]
                                    ], JSON_PRETTY_PRINT));
                                }
                            }
                        }

                        if ($fileDiUpload === 0) {
                            $jsonMsg = [
                                "status" => "error",
                                "code" => 400,
                                "message" => "Mohon setidaknya unggah satu berkas"
                            ];
                        } else {
                            $jsonMsg = [
                                "status" => "success",
                                "code" => 200,
                                "message" => "Berkas berhasil di unggah"
                            ];
                        }
                    } else {
                        // Jika sudah
                        // ganti berkas baru
                        if ($file['error'] === 1) {
                            echo json_encode([
                                "status" => "error",
                                "code" => "FILE_TOO_LARGE",
                                "message" => "Ukuran file terlalu besar.",
                                "details" => [
                                    "file" => $jenis_file,
                                    "issue" => "Ukuran file untuk $jenis_file terlalu besar."
                                ]
                            ], JSON_PRETTY_PRINT);
                            exit();
                        }
                        if ($file['error'] !== 4) {
                            $file_name = $file['name'];
                            $file_tmp = $file['tmp_name'];
                            $file_size = $file['size'];
                            $file_error = $file['error'];
                
                            // Ekstensi file
                            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                
                            // Validasi file
                            if ($file_error === 0) {
                                if (!in_array($file_extension, $allowed_extensions)) {
                                    echo json_encode([
                                        "status" => "error",
                                        "code" => "EXTENSION_NOT_ALLOWED",
                                        "message" => "Ekstensi file tidak diperbolehkan.",
                                        "details" => [
                                            "file", $jenis_file,
                                            "issue" => "Ekstensi file untuk $jenis_file tidak diperbolehkan."
                                        ]
                                    ], JSON_PRETTY_PRINT);
                                    exit();
                                }
                
                                if ($file_size > $max_file_size) {
                                    echo json_encode([
                                        "status" => "error",
                                        "code" => "FILE_TOO_LARGE",
                                        "message" => "Ukuran file terlalu besar.",
                                        "details" => [
                                            "file" => $jenis_file,
                                            "issue" => "Ukuran file untuk $jenis_file terlalu besar."
                                        ]
                                    ], JSON_PRETTY_PRINT);
                                    exit();
                                }
                
                                // Buat nama file unik
                                $unique_file_name = uniqid($jenis_file . '_', true) . '.' . $file_extension;
                                $file_path = $upload_folder . $unique_file_name;

                                // Hapus dokumen sebelumnya
                                $stmt = $pdo->prepare("SELECT file_path FROM dokumen_pendaftaran WHERE id_calon = :idCalon AND jenis_dokumen = :jenis_dokumen");
                                $stmt->execute([
                                    "idCalon" => $id_calon,
                                    "jenis_dokumen" => $jenis_file
                                ]);

                                $dokumen = $stmt->fetchAll();

                                if (!empty($dokumen)) {
                                    foreach ($dokumen as $row) {
                                        $sumberFile = $_SERVER["DOCUMENT_ROOT"] . $row["file_path"];
                                        if (file_exists($sumberFile)) {
                                            unlink($sumberFile);
                                        }
                                    }
                                }
                
                                // Pindahkan file ke folder tujuan
                                if (move_uploaded_file($file_tmp, $file_path)) {
                                    $stmt = $pdo->prepare("UPDATE dokumen_pendaftaran SET `file_path` = :file_path WHERE id_calon = :id_calon AND jenis_dokumen = :jenis_dokumen");
                                    $stmt->execute([
                                        ':id_calon' => $id_calon,
                                        ':jenis_dokumen' => $jenis_file,
                                        ':file_path' => '/uploads/documents/' . $unique_file_name
                                    ]);

                                    $fileDiUpload++;
                                } else {
                                    throw new Exception(json_encode([
                                        "status" => "error",
                                        "code" => "FOLDER_NOT_FOUND",
                                        "message" => "Gagal memindahkan file ke folder tujuan.",
                                        "details" => [
                                            "file" => $jenis_file,
                                            "issue" => "Gagal memindahkan file dikarenakan folder tujuan tidak tersedia atau tidak dapat diakses."
                                        ]
                                    ], JSON_PRETTY_PRINT));
                                }
                            }
                        }

                        if ($fileDiUpload === 0) {
                            $jsonMsg = [
                                "status" => "error",
                                "code" => 400,
                                "message" => "Mohon setidaknya unggah satu berkas"
                            ];
                        } else {
                            $jsonMsg = [
                                "status" => "success",
                                "code" => 200,
                                "message" => "Berkas berhasil di ganti"
                            ];
                        }
                    }
                }

                echo json_encode($jsonMsg, JSON_PRETTY_PRINT);
            }
        } catch (Throwable $e) {
            echo json_encode([
                "status" => "error",
                "code" => 500,
                "message" => $e
            ], JSON_PRETTY_PRINT);
        }
    }
}
?>