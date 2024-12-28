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
                            <span class="material-symbols-outlined dark-green">
                                edit
                            </span>
                            <span class="material-symbols-outlined dark-red" onclick="hapusPengguna(<?php echo $row['id'] ?>, '<?php echo $row['nama'] ?>')">
                                delete
                            </span>
                        </div>
                    </li>
                <?php
            }
        }
    } catch (PDOException $th) {
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