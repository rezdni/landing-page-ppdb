<?php
session_start();
header('Content-Type: application/json');
require_once '../config/koneksi.php';

if (!isset($_SESSION) || !isset($_SESSION["user_role"]) || !$_SESSION["user_role"] === "Admin") {
    echo json_encode([
        "status" => "error",
        "code" => 403,
        "message" => "Anda tidak memiliki akses untuk ini",
    ], JSON_PRETTY_PRINT);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari formulir
    // Data calon
    $nama_calon = htmlspecialchars($_POST['nama-siswa']);
    $tanggal_lahir = htmlspecialchars($_POST['tanggal-lahir']);
    $no_nis = htmlspecialchars($_POST['no-nis']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis-kelamin']);
    $agama = htmlspecialchars($_POST['agama']);
    $kewarganegaraan = htmlspecialchars($_POST['kewarganegaraan']);
    $asal_sekolah = htmlspecialchars($_POST['asal-sekolah']);
    $golongan_darah = htmlspecialchars($_POST['golongan-darah']);
    $alamat = htmlspecialchars($_POST['alamat-tinggal']);
    $provinsi = htmlspecialchars($_POST['provinsi']);
    $kota_kabupaten = htmlspecialchars($_POST['kota-kabupaten']);
    $kecamatan = htmlspecialchars($_POST['kecamatan']);
    $kelurahan = htmlspecialchars($_POST['kelurahan']);
    $kode_pos = htmlspecialchars($_POST['kode-post']);
    $no_telepon = htmlspecialchars($_POST['no-telepon']);
    $jurusan = htmlspecialchars($_POST['jurusan']);
    $gelombang = htmlspecialchars($_POST['gelombang']);

    // Data orang tua
    $nama_orang_tua = htmlspecialchars($_POST['nama-orang-tua']);
    $no_telepon_orangtua = htmlspecialchars($_POST['nomor-telepon-orang-tua']);
    $pekerjaan_orangtua = htmlspecialchars($_POST['pekerjaan-orang-tua']);
    $alamat_orangtua = htmlspecialchars($_POST['alamat-orang-tua']);

    // Konfigurasi unggahan file
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $max_file_size = 2 * 1024 * 1024; // 5MB
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

    // Mulai transaksi
    $pdo->beginTransaction();

    try {
        // Simpan data diri ke tabel calon_peserta
        $stmt = $pdo->prepare("INSERT INTO pendaftaran (nama_calon_siswa, tanggal_lahir, no_nis, jenis_kelamin, agama, sekolah_asal, kewarganegaraan, golongan_darah, alamat_tinggal, provinsi, kota_kabupaten, kecamatan, kelurahan, kode_post, tanggal_daftar, no_telepon, jurusan, gelombang) VALUES (:nama_calon_siswa, :tanggal_lahir, :no_nis, :jenis_kelamin, :agama, :sekolah_asal, :kewarganegaraan, :golongan_darah, :alamat_tinggal, :provinsi, :kota_kabupaten, :kecamatan, :kelurahan, :kode_post, :tanggal_daftar, :no_telepon, :jurusan, :gelombang)");
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
        $calon_id = $pdo->lastInsertId();

        // Simpan data orang tua ke tabel orang_tua
        $stmt = $pdo->prepare("INSERT INTO orang_tua (id_calon, nama_orang_tua, pekerjaan_orang_tua, nomor_telepon_orang_tua, alamat_orang_tua) VALUES (:id_calon, :nama_orang_tua, :pekerjaan_orang_tua, :nomor_telepon_orang_tua, :alamat_orang_tua)");
        $stmt->execute([
            'id_calon' => $calon_id,
            'nama_orang_tua' => $nama_orang_tua,
            'pekerjaan_orang_tua' => $pekerjaan_orangtua,
            'nomor_telepon_orang_tua' => $no_telepon_orangtua,
            'alamat_orang_tua' => $alamat_orangtua
        ]);

        // Proses setiap file unggahan
        foreach ($file_types as $jenis_file => $file) {
            if ($file['error'] === 1) {
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
                            ':id_calon' => $calon_id,
                            ':jenis_dokumen' => $jenis_file,
                            ':file_path' => '/uploads/documents/' . $unique_file_name
                        ]);
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
        }

        // Commit transaksi
        $pdo->commit();

        echo json_encode([
            "status" => "success",
            "code" => 200,
            "message" => "Data berhasil disimpan"
        ], JSON_PRETTY_PRINT);
        exit();
    } catch (Exception $e) {
        // Rollback jika ada kesalahan
        $pdo->rollBack();
        // echo json_encode(["status" => "error", "message" => "Terjadi kesalahan: " . $e->getMessage()]);
        echo $e->getMessage();
    }
}
?>