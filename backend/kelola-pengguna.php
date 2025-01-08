<?php
require_once "../config/koneksi.php";

// Create admin
if (isset($_POST["createAdmin"])) {
    // variable
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    // Decrypt password
    $password = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Prepare query
        $insertSql = "INSERT INTO pengguna (`id`, `nama`, `email`, `password`, `role`, `id_calon`, `lupa_sandi`, `sesi`) VALUES (null, :name, :email, :password, 'Admin', null, 0, null)";
        $insertStmt = $pdo->prepare($insertSql);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password
            
        ];
        
        // Insert to database
        $insertStmt->execute($data);
        echo json_encode(["status" => "berhasil"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "gagal", "keterangan" => $e]);
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
        echo json_encode(["status" => "berhasil"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "gagal", "keterangan" => $e]);
    }
}

// List akun
if (isset($_GET["listPengguna"])) {
    // List user account
    try {
        $showSql = "SELECT * FROM pengguna";
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
                    <li>
                        <div>
                            <span class="material-symbols-outlined dark-purple">
                            person
                            </span>
                            <p><?php echo $row["nama"]; ?></p>
                        </div>
                        <div>
                            <p><?php echo $row["role"]; ?></p>
                            <a href="edit-akun.html?id_akun=<?php echo $row['id']; ?>" class="material-symbols-outlined dark-green">
                                edit
                            </a>
                            <span class="material-symbols-outlined dark-red" onclick="hapusPengguna(<?php echo $row['id'] ?>, '<?php echo $row['nama'] ?>')">
                                delete
                            </span>
                        </div>
                    </li>
                <?php
            }
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "gagal", "keterangan" => $e]);
    }
}

// tampilkan salah satu akun
if (isset($_GET["idPengguna"])) {
    $idPengguna = htmlspecialchars($_GET["idPengguna"]);

    try {
        $showSql = "SELECT * FROM pengguna WHERE id = :idPengguna";
        $stmt = $pdo->prepare($showSql);

        $tampilkan = ["idPengguna" => $idPengguna];

        $stmt->execute($tampilkan);
        // get result
        $results = $stmt->fetchAll();
        if (empty($results)) {
            echo json_encode(["status" => "gagal", "data tidak tersedia" => $e]);
        } else {
            foreach ($results as $row) {
                echo json_encode(["status" => "berhasil", "data" => (["nama" => $row["nama"], "email" => $row["email"], "role" => $row["role"]])]);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "gagal", "keterangan" => $e]);
    }
}

// simpan perubahan akun
if (isset($_POST["simpanPerubahan"])) {
    $idPengguna = htmlspecialchars($_POST["idPengguna"]);
    $namaPengguna = htmlspecialchars($_POST["namaPengguna"]);
    $emailPengguna = htmlspecialchars($_POST["emailPengguna"]);
    $password = htmlspecialchars($_POST["password"]);
    $jenisPengguna = htmlspecialchars($_POST["jenisPengguna"]);

    try {
        if ($password == "") {
            $updateData = "UPDATE pengguna SET `nama` = :namaPengguna, `email` = :emailPengguna, `role` = :jenisPengguna WHERE `id` = :idPengguna";
            $stmt = $pdo->prepare($updateData);

            $masukanPerubahan = [
                "namaPengguna" => $namaPengguna,
                "emailPengguna" => $emailPengguna,
                "jenisPengguna" => $jenisPengguna,
                "idPengguna" => $idPengguna
            ];

            $stmt->execute($masukanPerubahan);

            echo json_encode(["status" => "berhasil"]);
        } else {
            $password = password_hash($password, PASSWORD_BCRYPT);

            $updateData = "UPDATE pengguna SET `nama` = :namaPengguna, `email` = :emailPengguna, `password` = :password, `role` = :jenisPengguna WHERE `id` = :idPengguna";
            $stmt = $pdo->prepare($updateData);

            $masukanPerubahan = [
                "namaPengguna" => $namaPengguna,
                "emailPengguna" => $emailPengguna,
                "password" => $password,
                "jenisPengguna" => $jenisPengguna,
                "idPengguna" => $idPengguna
            ];

            $stmt->execute($masukanPerubahan);

            echo json_encode(["status" => "berhasil"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "gagal", "keterangan" => $e]);
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
        echo json_encode(["status" => "berhasil"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "gagal", "keterangan" => $e]);
    }
}
?>