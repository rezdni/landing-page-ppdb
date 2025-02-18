<?php
session_start();
require_once "../config/koneksi.php";

if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] === "Admin") {
    header('Content-Type: application/json');
    
    // List akun
    if (isset($_GET["list_pengguna"])) {
        // List user account
        try {
            $showSql = "SELECT * FROM pengguna";
            $stmt = $pdo->query($showSql);
    
            // get result
            $results = $stmt->fetchAll();
            if (empty($results)) {
                echo json_encode([
                    "status" => "error",
                    "code" => 404,
                    "message" => "Data tidak tersedia"
                ], JSON_PRETTY_PRINT);
            } else {
                $hasilData = [];
                foreach ($results as $row) {
                    $hasilData[] = [
                        "id" => $row["id"],
                        "nama" => $row["nama"],
                        "email" => $row["email"],
                        "role" => $row["role"],
                        "id_calon" => $row["id_calon"]
                    ];
                }

                echo json_encode([
                    "status" => "success",
                    "code" => 200,
                    "message" => "Data berhasil diambil",
                    "data" => $hasilData
                ], JSON_PRETTY_PRINT);
            }
        } catch (Throwable $e) {
            echo json_encode([
                "status" => "error",
                "code" => 500,
                "message" => $e
            ], JSON_PRETTY_PRINT);
        }
    }
    
    // tampilkan salah satu akun
    if (isset($_GET["id_pengguna"])) {
        $idPengguna = htmlspecialchars($_GET["id_pengguna"]);
    
        try {
            $showSql = "SELECT * FROM pengguna WHERE id = :idPengguna";
            $stmt = $pdo->prepare($showSql);
    
            $tampilkan = ["idPengguna" => $idPengguna];
    
            $stmt->execute($tampilkan);
            // get result
            $results = $stmt->fetch();
            if (empty($results)) {
                echo json_encode([
                    "status" => "error",
                    "code" => 404,
                    "message" => "Data tidak ditemukan"
                ], JSON_PRETTY_PRINT);
            } else {
                $nis = null;

                $cekNim = $pdo->prepare("SELECT no_nis FROM pendaftaran WHERE id_calon = :id_calon");
                $cekNim->execute(["id_calon" => $results["id_calon"]]);
                $hasilNis = $cekNim->fetch();

                if (!empty($hasilNis)) {
                    $nis = $hasilNis["no_nis"];
                }

                echo json_encode([
                    "status" => "success",
                    "code" => 200,
                    "message" => "Data ditemukan",
                    "data" => [
                        "id" => $results["id"],
                        "nama" => $results["nama"],
                        "email" => $results["email"],
                        "role" => $results["role"],
                        "no_nis" => $nis
                    ]
                ], JSON_PRETTY_PRINT);
            }
        } catch (Throwable $e) {
            echo json_encode([
                "status" => "error",
                "code" => 500,
                "message" => $e
            ], JSON_PRETTY_PRINT);
        }
    }
    
    // simpan perubahan akun
    if (isset($_POST["simpanPerubahan"])) {
        $idPengguna = htmlspecialchars($_POST["idPengguna"]);
        $namaPengguna = htmlspecialchars($_POST["nama-pengguna"]);
        $emailPengguna = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);
        $jenisPengguna = htmlspecialchars($_POST["jenis-pengguna"]);

        $nisPengguna = null;
        $idCalon = null;

        (isset($_POST["no-nis"]) && $_POST["no-nis"] !== "") ? $nisPengguna = htmlspecialchars($_POST["no-nis"]) : $nisPengguna = null;
        
        try {
            // Cek NIS
            if (isset($_POST["no-nis"]) && $_POST["no-nis"] !== "") {
                $sql = "SELECT * FROM pendaftaran WHERE no_nis = :no_nis";
                $stmt = $pdo->prepare($sql);

                $stmt->execute(["no_nis" => $nisPengguna]);

                $hasil = $stmt->fetch();
                
                if (empty($hasil)) {
                    echo json_encode([
                        "status" => "error",
                        "code" => 404,
                        "message" => "NIS tidak tersedia"
                    ], JSON_PRETTY_PRINT);
                    exit();
                } else {
                    $idCalon = $hasil["id_calon"];
                }
            }

            // Cek perubahan password
            if ($password == "") {
                $updateData = "UPDATE pengguna SET `nama` = :namaPengguna, `email` = :emailPengguna, `role` = :jenisPengguna, id_calon = :idCalon WHERE `id` = :idPengguna";
                $stmt = $pdo->prepare($updateData);
    
                $masukanPerubahan = [
                    "namaPengguna" => $namaPengguna,
                    "emailPengguna" => $emailPengguna,
                    "jenisPengguna" => $jenisPengguna,
                    "idPengguna" => $idPengguna,
                    "idCalon" => $idCalon
                ];
    
                $stmt->execute($masukanPerubahan);
    
                echo json_encode([
                    "status" => "success",
                    "code" => 200,
                    "message" => "Data pengguna berhasil diubah"
                ], JSON_PRETTY_PRINT);

            } else {
                $password = password_hash($password, PASSWORD_BCRYPT);
    
                $updateData = "UPDATE pengguna SET `nama` = :namaPengguna, `email` = :emailPengguna, `password` = :password, `role` = :jenisPengguna, id_calon = :idCalon WHERE `id` = :idPengguna";
                $stmt = $pdo->prepare($updateData);
    
                $masukanPerubahan = [
                    "namaPengguna" => $namaPengguna,
                    "emailPengguna" => $emailPengguna,
                    "password" => $password,
                    "jenisPengguna" => $jenisPengguna,
                    "idPengguna" => $idPengguna,
                    "idCalon" => $idCalon
                ];
    
                $stmt->execute($masukanPerubahan);
    
                echo json_encode([
                    "status" => "success",
                    "code" => 200,
                    "message" => "Data pengguna berhasil diubah"
                ], JSON_PRETTY_PRINT);

            }
        } catch (Throwable $e) {
            echo json_encode([
                "status" => "error",
                "code" => 500,
                "message" => $e
            ], JSON_PRETTY_PRINT);
        }
    }

    // Buat akun baru
    if (isset($_POST["buatAkun"])) {
        $namaPengguna = htmlspecialchars($_POST["nama-pengguna"]);
        $emailPengguna = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);
        $jenisPengguna = htmlspecialchars($_POST["jenis-pengguna"]);

        $nisPengguna = null;
        $idCalon = null;

        (isset($_POST["no-nis"]) && $_POST["no-nis"] !== "") ? $nisPengguna = htmlspecialchars($_POST["no-nis"]) : $nisPengguna = null;
        
        try {
            // Cek NIS
            if (isset($_POST["no-nis"]) && $_POST["no-nis"] !== "") {
                $sql = "SELECT * FROM pendaftaran WHERE no_nis = :no_nis";
                $stmt = $pdo->prepare($sql);

                $stmt->execute(["no_nis" => $nisPengguna]);

                $hasil = $stmt->fetch();
                
                if (empty($hasil)) {
                    echo json_encode([
                        "status" => "error",
                        "code" => 404,
                        "message" => "NIS tidak tersedia"
                    ], JSON_PRETTY_PRINT);
                    exit();
                } else {
                    $idCalon = $hasil["id_calon"];
                }
            }

            // Cek perubahan password
            if ($password == "" || $password === null) {
                echo json_encode([
                    "status" => "error",
                    "code" => 400,
                    "message" => "Sandi tidak boleh kosong"
                ], JSON_PRETTY_PRINT);
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT);
    
                $updateData = "INSERT INTO pengguna (`id`, `nama`, `email`, `password`, `role`, `id_calon`, `lupa_sandi`, `sesi`) VALUES (null, :namaPengguna, :emailPengguna, :password, :jenisPengguna, :idCalon, 0, null)";
                $stmt = $pdo->prepare($updateData);
    
                $tambah = [
                    "namaPengguna" => $namaPengguna,
                    "emailPengguna" => $emailPengguna,
                    "password" => $password,
                    "jenisPengguna" => $jenisPengguna,
                    "idCalon" => $idCalon
                ];
    
                $stmt->execute($tambah);
    
                echo json_encode([
                    "status" => "success",
                    "code" => 200,
                    "message" => "Pengguna baru berhasil ditambahkan"
                ], JSON_PRETTY_PRINT);

            }
        } catch (Throwable $e) {
            echo json_encode([
                "status" => "error",
                "code" => 500,
                "message" => $e
            ], JSON_PRETTY_PRINT);
        }
    }
    
    // hapus akun
    if (isset($_POST["removeUser"])) {
        $userId = htmlspecialchars($_POST["userId"]);
    
        try {
            $deleteSql = "DELETE FROM pengguna WHERE id = :idPengguna";
            $stmt = $pdo->prepare($deleteSql);
    
            $deleteParam = ["idPengguna" => $userId];
    
            $stmt->execute($deleteParam);
            echo json_encode([
                "status" => "success",
                "code" => 200,
                "message" => "Berhasil menghapus pengguna"
            ], JSON_PRETTY_PRINT);

        } catch (Throwable $e) {
            echo json_encode([
                "status" => "error",
                "code" => 500,
                "message" => $e
            ], JSON_PRETTY_PRINT);
        }
    }
}

// Create Calon
if (isset($_POST["createCalon"])) {
    // variable
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    // Decrypt password
    $password = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Prepare query
        $insertSql = "INSERT INTO pengguna (`id`, `nama`, `email`, `password`, `role`, `id_calon`, `lupa_sandi`, `sesi`) VALUES (null, :name, :email, :password, 'Calon', null, 0, null)";
        $insertStmt = $pdo->prepare($insertSql);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password
            
        ];
        
        // Insert to database
        $insertStmt->execute($data);
        echo json_encode([
            "status" => "success",
            "code" => 200,
            "message" => "Berhasil menambah calon"
        ], JSON_PRETTY_PRINT);

    } catch (Throwable $e) {
        echo json_encode([
            "status" => "error",
            "code" => 500,
            "message" => $e
        ], JSON_PRETTY_PRINT);
    }
}
?>