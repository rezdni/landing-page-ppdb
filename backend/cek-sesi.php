<?php
session_start();
header('Content-Type: application/json');
require "../config/koneksi.php";

$respon = ["diotentikasi" => false, "role" => null];

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
            $respon = ["diotentikasi" => true, "role" => $_SESSION["user_role"]];
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "pesan" => $e]);
    }

} elseif (isset($_SESSION["user_id"])) {
    $respon = ["diotentikasi" => true, "role" => $_SESSION["user_role"]];
}

echo json_encode($respon);
exit();
?>