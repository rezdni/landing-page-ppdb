<?php
session_start();
header("Content-Type: application/json");
require_once "../config/koneksi.php";

if (isset($_SESSION) && $_SESSION["user_role"] === "Calon") {
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
    // echo json_encode([
    //     "id" => $_SESSION["user_id"],
    //     "user_name" => $_SESSION["user_name"],
    //     "user_role" => $_SESSION["user_role"]
    // ]);
} else {
    echo json_encode(["status" => "error", "pesan" => "Anda tidak memiliki akses untuk ini"]);
}
?>