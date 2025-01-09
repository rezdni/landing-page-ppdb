<?php
require_once "../config/koneksi.php";

if (isset($_GET["listCalon"])) {
    try {
        if (isset($_GET["limit"])) {
            $limit = htmlspecialchars($_GET["limit"]);

            $sql = "SELECT * FROM pendaftaran LIMIT :limit";
            $stmt = $pdo->prepare($sql);

            $proses = ["limit" => $limit];
            $stmt->execute($proses);

            $hasil = $stmt->fetchAll();

            if (empty($hasil)) {
                echo json_encode(["kesalahan" => "Data tidak tersedia"]);
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
                echo json_encode($hasilData, JSON_PRETTY_PRINT);
            }
        } else {
            $sql = "SELECT * FROM pendaftaran";
            $stmt = $pdo->query($sql);

            $hasil = $stmt->fetchAll();

            if (empty($hasil)) {
                echo json_encode(["kesalahan" => "Data tidak tersedia"]);
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
                echo json_encode($hasilData, JSON_PRETTY_PRINT);
            }
        }
        
    } catch (PDOException $e) {
        echo $e;
    }
}
?>