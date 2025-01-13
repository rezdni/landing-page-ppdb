<?php
session_start();
header('Content-Type: application/json');
require "../config/koneksi.php";

if (isset($_POST["login"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    try {
        $sql = "SELECT `id`, `nama`, `email`, `password`, `role` FROM pengguna WHERE `email` = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["email" => $email]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["nama"];
            $_SESSION["user_role"] = $user["role"];

            $stmt = null;

            $token = bin2hex(random_bytes(16));
            $hashedToken = password_hash($token, PASSWORD_BCRYPT);

            $sql = "UPDATE pengguna SET `sesi` = :token WHERE id = :userId";
            $stmt = $pdo->prepare($sql);

            $setSession = [
                "token" => $hashedToken,
                "userId" => $_SESSION["user_id"],
            ];

            $stmt->execute($setSession);

            setcookie("login", $user["id"] . ":" . $token, [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'httponly' => true,
                'secure' => false, //enable https
                'samesite' => 'Strict',
            ]);

            if ($_SESSION["user_role"] == "Admin") {
                echo json_encode(["status" => "berhasil", "alihkan" => "/views/admin/"]);
            } else {
                echo json_encode(["status" => "berhasil", "alihkan" => "/views/user/"]);
            }

        } else {
            $_SESSION["error"] = "Email atau Password salah.";
            echo json_encode(["status" => "gagal", "pesan" => "Email atau Password salah."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "pesan" => $e]);
    }
}
?>