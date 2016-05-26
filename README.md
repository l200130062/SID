# SID

Alvian Dwi Mardani
L200130062

# Sistim-Informasi-Terdistribusi
TUGAS (Soal UTS)

4.	1. Menyiapkan database yang akan digunakan
Sebelum menulis kode program untuk aplikasi server, terlebih dahulu Anda siapkan database yang akan digunakan, yaitu data mengenai mahasiswa.

mysql> CREATE DATABASE Mhs;
mysql> CREATE TABLE Mahasiswa(nama VARCHAR(20) PRIMARY KEY, nim
VARCHAR(30) NOT NULL, alamat VARCHAR(255) NOT NULL, ipk
VARCHAR(4)
NOT NULL
);

2. Menulis kode program untuk aplikasi server

Setelah Anda membuat database ‘Mhs’, langkah selanjutnya yaitu membuat kode program untuk aplikasi server dari Web Service.

<?
require_once('nusoap.php');
$ns = "http://localhost:libnusoap/";
$server = new soap_server;
$server->configureWSDL('Mahasiswa', $ns);
$server->wsdl->schemaTargetNamespace = $ns;
$server->register('Mahasiswa', array('nama’ => 'xsd:string'),
array('return'=>'xsd:string'), $ns);
function RamalanZodiak($nama) {
if (!$nama) {
return new soap_fault('Client', '', 'Harus ada nilainya!', '');
}
9
if ($conn = mysql_connect("host", "user", "password")) {
if ($db = mysql_select_db("Mhs")) {
$result = mysql_query("SELECT * FROM Mahasiswa WHERE
nama = '$nama ");
while ($row = mysql_fetch_array($result)) {
$nama = $row["nama "];
$nim = $row["nim"];
$alamat = $row["alamat"];
$ipk = $row["ipk"];
}
} else {
return new soap_fault('Database Server', '', 'Koneksi ke
database gagal!', '');
}
} else {
return new soap_fault('Database Server', '', 'Koneksi ke database
gagal!', '');
}
return "<b>nama: </b>$nama<br>
<b>nim: </b>$nim<br>
<b>alamat: </b>$alamat<br>
<b>ipk: </b>$ipk<br>;
}
$server->service($HTTP_RAW_POST_DATA);
exit();
?>
Untuk memastikan apakah aplikasi server yang telah dibangun dapat berjalan dengan baik atau tidak, ada baiknya kalau Anda melakukan pengetesan terlebih dahulu sebelum dnda menulis kode program untuk aplikasi client dari Web Service Jika pengetesan yang Anda lakukan berhasil, maka pada browser Anda akan tampil seperti gambar di atas. Anda dapat melihat deskripsi dari Web Service yang Anda bangun dengan memilih menu WSDL pada bagian kiri atas. 

3. 	Menulis kode program untuk aplikasi client

Langkah berikutnya adalah menulis kode program untuk aplikasi client. Aplikasi client akan melakukan permintaan layanan pada server Web Service, dan akan menerima nilai yang dikembalikan oleh server Web Service.

<?php
require_once "lib/nusoap.php";

$wsdl = "http://localhost/sid/soapjsonserver.php?wsdl"; 
$client = new nusoap_client($wsdl,'wsdl');

$error = $client->getError(); 
if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
    exit(); 
}


$result = $client->call("getProd", array("category" => "profile"));

if ($result) {
    echo "Hasil: "  . $result; 
    print("<hr>");
    $ojson = json_decode($result);
    print_r( $ojson );
    print("<hr>");
    echo "Nama: " . $ojson->nama ."<br>";
    echo "NIM: " . $ojson->nim ."<br>";
    echo "Alamat: " . $ojson->alamat ."<br>";
    echo "IPK: " . $ojson->ipk ."<br>";

} else {

}


5.
<?php
require_once "lib/nusoap.php";


	function getProd($category) {
		if ($category == "profile") {
			
			
			$buku = array("The WordPress Anthology",
				"PHP Master: Write Cutting Edge Code",
				"Build Your Own Website the Right Way");
			
			
			$profile = array(
					"nama" => "Dyah",
					"nim" => "L200130035",
					"alamat" => "Salatiga",
					"ipk" => 3,25
			);

			//$profile["nama"]   ... output = "Dyah" 
			
			
			$output = json_encode( $profile );  //konversi array assc -> json string
			
			return  $output;
		}
		else {
			return "No products listed under that category";
		}
	}

$server = new soap_server();
// $server->register("getProd");

$server->configureWSDL("productlist", "urn:productlist");
// $server->wsdl->schemaTargetNamespace = 'urn:productlist/xsd/';
$server->register("getProd",
	array("category" => "xsd:string"),
	array("return" => "xsd:string"),
    "urn:productlist",
    "urn:productlist#getProd",
    "rpc",
    "encoded",
    "Get a listing of products by category");

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);



