<?php
session_start();
header('Content-Type: application/json');
require "../config/koneksi.php";

$respon = ["authenticated" => false, "role" => null];

if (!isset($_SESSION["user_id"]) && isset($_COOKIE["login"])) {
    list($id, $token) = explode(":", $_COOKIE["login"]);

    try {
        $sql = "SELECT `id`, `nama`, `email`, `password`, `role`, `sesi` FROM pengguna WHERE `id` = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["userId" => $id]);
    
        $user = $stmt->fetch();

        if ($user && password_verify($token, $user["sesi"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["nama"];
            $_SESSION["user_role"] = $user["role"];
            $respon = ["authenticated" => true, "role" => $_SESSION["user_role"]];
        }
    } catch (Throwable $e) {
        echo json_encode([
            "status" => "error",
            "code" => 500,
            "message" => $e
        ], JSON_PRETTY_PRINT);
    }

} elseif (isset($_SESSION["user_id"])) {
    try {
        // cek akun pengguna
        $sql = "SELECT * FROM pengguna WHERE id = :id_pengguna";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            "id_pengguna" => $_SESSION["user_id"]
        ]);

        $result = $stmt->fetch();

        // Jika pengguna tidak ada di database
        if (empty($result)) {
            $respon = ["authenticated" => false, "role" => null];

            // Hapus cookie dan sesi pengguna
            session_destroy();
            setcookie("login", "", time() - 3600, "/");
        } else {
            // Jika ada
            $respon = ["authenticated" => true, "role" => $_SESSION["user_role"]];
        }
    } catch (Throwable $e) {
        echo json_encode([
            "status" => "error",
            "code" => 500,
            "message" => $e
        ], JSON_PRETTY_PRINT);
    }
}

echo json_encode([
    "status" => "success",
    "code" => 200,
    "details" => $respon
], JSON_PRETTY_PRINT);

exit();
?>