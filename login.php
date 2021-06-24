<?php 

// Import script autoload agar bisa menggunakan library
require_once('./vendor/autoload.php');
// Import library
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header('Content-Type: application/json');

// Cek method request apakah POST atau tidak
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
 http_response_code(405);
 exit();
}

// Ambil JSON yang dikirim oleh user
$json = file_get_contents('php://input');
// Decode json tersebut agar mudah mengambil nilainya
$input_user = json_decode($json);

// Jika tidak ada data email atau password
if (!isset($input_user->email) || !isset($input_user->password)) {
 http_response_code(400);
 exit();
}

// Cuma data mock/dummy, bisa diganti dengan data dari database
$user = [
 'email' => 'atasyaar@example.com',
 'password' => 'asdfghjkl'
];

// Jika email atau password tidak sesuai
if ($input_user->email !== $user['email'] || $input_user->password !== $user['password']) {
    echo json_encode([
      'success' => false,
      'data' => null,
      'message' => 'Email atau password tidak sesuai'
    ]);
    exit();
  }

// 15 * 60 (detik) = 15 menit
$waktu_kadaluarsa = time() + (15 * 60);

$payload = [
 'email' => $input_user->email,
 'exp' => $waktu_kadaluarsa
];

$access_token = JWT::encode($payload,
$_ENV['ACCESS_TOKEN_SECRET']);
echo json_encode([
 'accessToken' => $access_token,
 'expiry' => date(DATE_ISO8601, $waktu_kadaluarsa)
]);

// Ubah waktu kadaluarsa lebih lama (1 jam)
$payload['exp'] = time() + (60 * 60);
$refresh_token = JWT::encode($payload,
$_ENV['REFRESH_TOKEN_SECRET']);
// Simpan refresh token di http-only cookie
setcookie('refreshToken', $refresh_token, $payload['exp'], '',
'', false, true);

?>