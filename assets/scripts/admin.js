// Hapus akun pengguna
function hapusPengguna(idPengguna, namaPengguna) {
    if (confirm("Anda yakin ingin menghapus akun " + namaPengguna + "?")) {
        // buat koneksi xhttprequest
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../backend/kelola-pengguna.php", true);
        xhr.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
        );

        // terima respon back-end
        xhr.onload = function () {
            if (xhr.status === 200) {
                let respon = JSON.parse(xhr.responseText);
                if (respon.status === "berhasil") {
                    alert("Akun berhasil dihapus");
                    showUsers();
                } else {
                    alert("Kesalahan dalam menghapus akun");
                    console.log(respon.keterangan);
                }
            } else {
                alert("Kesalahan dalam menghubungkan ke server");
            }
        };

        // Kirim request ke back-end
        xhr.send("removeUser=true&userId=" + encodeURIComponent(idPengguna));
    }
}
