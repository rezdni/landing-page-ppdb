<?php
session_start();
header("Content-Type: application/json");
require_once "../config/koneksi.php";

if (isset($_SESSION) && $_SESSION["user_role"] === "Calon") {
    // Data diri
    if (isset($_POST["daftar"])) {
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

        try {
            // prepare query
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
            $calon_id = $pdo->lastInsertId();

            // reset
            $stmt = null;

            // Siapkan query untuk di hubungkan ke akun calon
            $sql = "UPDATE pengguna SET `id_calon` = :id_calon WHERE id = :id_pengguna";
            $stmt = $pdo->prepare($sql);

            // Hubungkan
            $stmt->execute([
                "id_calon" => $calon_id,
                "id_pengguna" => $_SESSION["user_id"]
            ]);

            echo json_encode(["status" => "berhasil", "pesan" => "Pendaftaran berhasil"]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "gagal", "keterangan" => $e]);
        }
    }

    // Data orang tua
    if (isset($_POST["orangtua"])) {
        $nama_orang_tua = htmlspecialchars($_POST['nama-orang-tua']);
        $no_telepon_orangtua = htmlspecialchars($_POST['nomor-telepon-orang-tua']);
        $pekerjaan_orangtua = htmlspecialchars($_POST['pekerjaan-orang-tua']);
        $alamat_orangtua = htmlspecialchars($_POST['alamat-orang-tua']);

        try {
            // cek akun pengguna
            $sql = "SELECT * FROM pengguna WHERE id = :id_pengguna";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                "id_pengguna" => $_SESSION["user_id"]
            ]);

            $result = $stmt->fetch();
            $id_calon = $result["id_calon"];
            $stmt = null;

            // Masukan data ortu ke database
            $sql = "INSERT INTO orang_tua (id_calon, nama_orang_tua, pekerjaan_orang_tua, nomor_telepon_orang_tua, alamat_orang_tua) VALUES (:id_calon, :nama_orang_tua, :pekerjaan_orang_tua, :nomor_telepon_orang_tua, :alamat_orang_tua)";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                'id_calon' => $id_calon,
                'nama_orang_tua' => $nama_orang_tua,
                'pekerjaan_orang_tua' => $pekerjaan_orangtua,
                'nomor_telepon_orang_tua' => $no_telepon_orangtua,
                'alamat_orang_tua' => $alamat_orangtua
            ]);

            echo json_encode(["status" => "berhasil", "pesan" => "Data orang tua ditambah"]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "gagal", "keterangan" => $e]);
        }
    }

    // Dokumentasi berkas
    if (isset($_POST["berkas"])) {
        // cek akun pengguna
        $sql = "SELECT * FROM pengguna WHERE id = :id_pengguna";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            "id_pengguna" => $_SESSION["user_id"]
        ]);

        $result = $stmt->fetch();
        $id_calon = $result["id_calon"];
        $stmt = null;

        // Konfigurasi unggahan file
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $max_file_size = 2 * 1024 * 1024; // 5MB
        $upload_folder = '../uploads/documents/';

        // Periksa apakah folder tujuan ada
        if (!is_dir($upload_folder)) {
            echo json_encode(["status" => "error", "pesan" => "Folder tujuan tidak tersedia"]);
            exit();
        }

        // Jenis file yang diunggah
        $file_types = [
            'ijasah' => $_FILES['ijasah'],
            'foto_profil' => $_FILES['foto-profil'],
            'dokumen_lainnya' => $_FILES['dokumen-lainnya']
        ];

        try {
            // Proses setiap file unggahan
            foreach ($file_types as $jenis_file => $file) {
                $file_name = $file['name'];
                $file_tmp = $file['tmp_name'];
                $file_size = $file['size'];
                $file_error = $file['error'];

                // Ekstensi file
                $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                // Validasi file
                if ($file_error === UPLOAD_ERR_OK) {
                    if (!in_array($file_extension, $allowed_extensions)) {
                        throw new Exception(json_encode(["status" => "gagal", "pesan" => "Ekstensi file untuk $jenis_file tidak diperbolehkan."]));
                    }

                    if ($file_size > $max_file_size) {
                        throw new Exception(json_encode(["status" => "gagal", "pesan" => "Ukuran file untuk $jenis_file terlalu besar."]));
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
                            ':file_path' => $file_path
                        ]);
                    } else {
                        throw new Exception(json_encode(["status" => "error", "pesan" => "Gagal memindahkan file $jenis_file ke folder tujuan."]));
                    }
                } else {
                    throw new Exception(json_encode(["status" => "error", "pesan" => "Terjadi kesalahan saat mengunggah file $jenis_file."]));
                }
            }

            echo json_encode(["status" => "berhasil", "pesan" => "Berkas berhasil di unggah"]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "gagal", "keterangan" => $e]);
        }
    }
    // echo json_encode([
    //     "id" => $_SESSION["user_id"],
    //     "user_name" => $_SESSION["user_name"],
    //     "user_role" => $_SESSION["user_role"]
    // ]);
} else {
    echo json_encode(["status" => "error", "pesan" => "Anda tidak memiliki akses untuk ini"]);
}
?>