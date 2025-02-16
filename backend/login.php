<?php
session_start();
header('Content-Type: application/json');
require "../config/koneksi.php";

// Sistem login
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
                echo json_encode([
                    "status" => "success",
                    "code" => 200,
                    "message" => "Berhasil login",
                    "redirect" => "/views/admin/"
                ], JSON_PRETTY_PRINT);
            } else {
                echo json_encode([
                    "status" => "success",
                    "code" => 200,
                    "message" => "Berhasil login",
                    "redirect" => "/views/user/"
                ], JSON_PRETTY_PRINT);
            }

        } else {
            $_SESSION["error"] = "Email atau Password salah.";
            echo json_encode([
                "status" => "error",
                "code" => "USER_NOT_FOUND",
                "message" => "Email atau Password salah."
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

// Reset sandi
if (isset($_POST["reset"])) {
    $email = htmlspecialchars($_POST["email"]);
    $newPass = htmlspecialchars($_POST["newpassword"]);
    $rePass = htmlspecialchars($_POST["retypepassword"]);
    $hashPass;

    if ($newPass !== $rePass) {
        echo json_encode([
            "status" => "error",
            "code" => 400,
            "message" => "Kata sandi tidak sesuai"
        ], JSON_PRETTY_PRINT);
        exit();
    } else {
        $hashPass = password_hash($rePass, PASSWORD_BCRYPT);
    }

    try {
        // Cek apakah pengguna adalah admin
        $sql = "SELECT `role` FROM pengguna WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["email" => $email]);
        $hasil = $stmt->fetch();

        if (empty($hasil)) {
            echo json_encode([
                "status" => "error",
                "code" => 404,
                "message" => "Pengguna tidak tersedia"
            ], JSON_PRETTY_PRINT);
        } else {
            if ($hasil["role"] === "Admin") {
                echo json_encode([
                    "status" => "error",
                    "code" => 403,
                    "message" => "Anda tidak bisa mengubah sandi pengguna ini"
                ], JSON_PRETTY_PRINT);
            } else {
                // Proses reset sandi
                $sql = "UPDATE pengguna SET `password` = :password WHERE email = :email";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    "email" => $email,
                    "password" => $hashPass
                ]);

                echo json_encode([
                    "status" => "success",
                    "code" => 200,
                    "message" => "Sandi berhasil diubah"
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
?>