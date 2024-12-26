<?php
require_once "../config/koneksi.php";

if (isset($_POST["createUser"])) {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $role = htmlspecialchars($_POST["role"]);

    $password = password_hash($password, PASSWORD_BCRYPT);

    try {
        $insertSql = "INSERT INTO pengguna (`id`, `nama`, `email`, `password`, `role`, `id_calon`, `lupa_sandi`) VALUES (null, :name, :email, :password, :role, null, 0)";
        $insertStmt = $pdo->prepare($insertSql);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role
            
        ];
        
        $insertStmt->execute($data);
        echo json_encode(["status" => "berhasil"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "gagal", "keterangan" => $e]);
    }
    // echo json_encode(["name" => $name, "email" => $email, "password" => $password, "role" => $role]);
}
?>