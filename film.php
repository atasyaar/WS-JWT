<?php

// Import script autoload agar bisa menggunakan library
require_once('./vendor/autoload.php');
// Import library
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
 http_response_code(405);
 exit();
}

$headers = getallheaders();

if (!isset($headers['Authorization'])) {
 http_response_code(401);
 exit();
}

list(, $token) = explode(' ', $headers['Authorization']);

try {
 // Men-decode token. Dalam library ini juga sudah sekaligus memverfikasinya
 JWT::decode($token, $_ENV['ACCESS_TOKEN_SECRET'], ['HS256']);
// Data film yang akan dikirim jika token valid
 $film = [
 [
 'title' => 'Habibie Ainun',
 'genre' => 'Romance'
 ],
 [
 'title' => 'K2',
 'genre' => 'Action'
 ],
 [
 'title' => 'While You Were Sleeping',
 'genre' => 'Fantasy'
 ],
 [
 'title' => 'Waikiki',
 'genre' => 'Comedy'
 ],
 [
 'title' => 'Reply 1988',
 'genre' => 'Drama'
 ] 
 ];
echo json_encode($film);
} catch (Exception $e) {
 // Bagian ini akan jalan jika terdapat error saat JWT diverifikasi atau di-decode
 http_response_code(401);
 exit();
}

