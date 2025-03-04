<?php
session_start();
require "../../config/koneksi.php";

// Convert date
function konversiTanggal($tgl) {
    $bln = array(
      1 => "Januari",
      "Februari",
      "Maret",
      "April",
      "Mei",
      "Juni",
      "Juli",
      "Agustus",
      "September",
      "Oktober",
      "November",
      "Desember"
    );
    
    $pecahkan = explode("-", $tgl);
    return $pecahkan[2] . " " . $bln[(int)$pecahkan[1]] . " " . $pecahkan[0];
}

// get data
function getUserData($database, $sesi, $tabel){
    $sql = "SELECT id_calon FROM pengguna WHERE id = :user_id";
    $stmt = $database->prepare($sql);
    $stmt->execute(["user_id" => $sesi["user_id"]]);
    $hasil = $stmt->fetch();

    if (empty($hasil) || $hasil["id_calon"] === null) {
        return "Data tidak tersedia";
    } else {
        $calonId = $hasil["id_calon"];

        if ($tabel === "calon") {
            $sql = "SELECT * FROM pendaftaran WHERE id_calon = :id_calon";
        } elseif ($tabel === "orang_tua") {
            $sql = "SELECT * FROM orang_tua WHERE id_calon = :id_calon";
        } else {
            return "Parameter salah";
        }
    
        $cekDataCalon = $database->prepare($sql);
        $cekDataCalon->execute(["id_calon" => $calonId]);
        $hasilData = $cekDataCalon->fetch();

        if (!empty($hasilData)) {
            return $hasilData;
        } else {
            return "Data tidak tersedia";
        }
    }
}

$dataCalon = getUserData($pdo, $_SESSION, "calon");
$dataOrtu = getUserData($pdo, $_SESSION, "orang_tua");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- font-owesome -->
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />
        <!-- css -->
        <link rel="stylesheet" href="../../assets/styles/invoice.css" />
        <title>Invoice</title>
    </head>
    <body>
        <div class="invoice-wrapper" id="print-area">
            <div class="invoice">
                <div class="invoice-container">
                    <div class="invoice-head">
                        <div class="invoice-head-top">
                            <div class="invoice-head-top-left text-star">
                                <img
                                    src="../../assets/images/logo-login.png"
                                    alt=""
                                />
                            </div>
                            <div class="invoice-head-top-right text-end">
                                <h2>
                                    YAYASAN PENDIDIKAN PONDOK PESANTREN MODERN
                                    ALFA SANAH
                                </h2>
                                <h1>SMA AL FASANAH</h1>
                                <p>Status: Terakreditasi B</p>
                                <p>Sk. No. 104/BAN-PDM/SK/2024</p>
                                <p>MSS: 302300424002 NPSN: 20603170</p>
                                <p>
                                    Alamat: Jl. Lingkar Selatan Kp. Anamui
                                    Rt.03/03 Desa Suradita Kec. Cisauk Kab.
                                    Tangerang - Banten
                                </p>
                            </div>
                        </div>
                        <div class="hr"></div>
                        <div class="hr"></div>
                        <div class="invoice-head-bottom">
                            <div class="invoice-head-bottom">
                                <ul>
                                    <li class="text-bold">BUKTI PENDAFTARAN</li>
                                    <li>Registarasi Pendaftaran</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-view">
                        <div class="invoice-body">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="text-bold" colspan="4">
                                            Data Diri Siswa
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="text-bold" colspan="4">
                                            Nomor Ujian Nasional
                                            <p id="nis"><?php echo $dataCalon["no_nis"] ?></p>
                                        </th>
                                    </tr>

                                    <tr>
                                        <td>Nama Siswa</td>
                                        <td id="name">: <?php echo $dataCalon["nama_calon_siswa"] ?></td>
                                        <td>Alamat</td>
                                        <td id="addres">: <?php echo $dataCalon["alamat_tinggal"] ?></td>
                                    </tr>

                                    <tr>
                                        <td>Tanggal Lahir</td>
                                        <td id="birth-date">: <?php echo konversiTanggal($dataCalon["tanggal_lahir"]) ?></td>
                                        <td>No. Telepon</td>
                                        <td id="phone">: <?php echo $dataCalon["no_telepon"] ?></td>
                                    </tr>

                                    <tr>
                                        <td>Jenis Kelamin</td>
                                        <td id="gender">: <?php echo $dataCalon["jenis_kelamin"] ?></td>
                                        <td>Tanggal Daftar</td>
                                        <td id="regist-date">: <?php echo konversiTanggal($dataCalon["tanggal_daftar"]) ?></td>
                                    </tr>
                                    <th class="text-bold" colspan="4">
                                        Data Orang Tua Siswa
                                    </th>

                                    <tr>
                                        <td>Nama Orang tua</td>
                                        <td id="parent-name">: <?php echo $dataOrtu["nama_orang_tua"] ?></td>

                                        <td>Pekerjaan</td>
                                        <td id="work">: <?php echo $dataOrtu["pekerjaan_orang_tua"] ?></td>
                                    </tr>

                                    <tr>
                                        <td>No. Telepon</td>
                                        <td id="parent-phone">: <?php echo $dataOrtu["nomor_telepon_orang_tua"] ?></td>

                                        <td>Alamat</td>
                                        <td id="parent-addres">: <?php echo $dataOrtu["alamat_orang_tua"] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="invoice-foot text-center">
                        <div class="invoice-btns" onclick="printInvoice()">
                            <button type="button" class="invoice-btn">
                                <span><i class="fa-solid fa-print"></i></span>
                                <span>Print</span>
                            </button>

                            <button type="button" class="invoice-btn">
                                <span
                                    ><i class="fa-solid fa-download"></i
                                ></span>
                                <span>Download</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../assets/scripts/invoice.js"></script>
    </body>
</html>
