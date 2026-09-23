<?php
    // dashboard.php

$host     = "127.0.0.1";
$username = "root";
$password = "";
$database = "if0_42967232_irrigazione_db";

// 1) Prendi il token dal cookie
$token = $_COOKIE['token'] ?? null;

// Nessun cookie -> torna al login
if ($token === null || $token === '') {
    header("Location: /index.php");
    exit;
}

// 2) Connessione DB
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    error_log("DB connect error: " . $conn->connect_error);
    http_response_code(500);
    exit("Errore interno");
}

// 3) Cerca il token nella tabella cookies
$stmt = $conn->prepare("SELECT username FROM cookies WHERE valore = ? LIMIT 1");
$stmt->bind_param("s", $token);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

// 4) Token non trovato -> cookie non valido, torna al login
if (!$row) {
    setcookie("token", "", [
        'expires'  => time() - 3600,
        'path'     => '/',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    $conn->close();
    header("Location: /index.php");
    exit;
}

// 5) Autenticato
$utente_loggato = $row['username'];
$conn->close();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: system-ui, sans-serif; max-width: 700px; margin: 4rem auto; padding: 0 1rem; }
        .box { border: 1px solid #ddd; border-radius: 10px; padding: 1.5rem; }
        a.btn { display: inline-block; margin-top: 1rem; padding: .6rem 1rem; background: #333; color: #fff; text-decoration: none; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Ciao, <?= htmlspecialchars($utente_loggato, ENT_QUOTES, 'UTF-8') ?> 👋</h1>
        <p>Sei autenticato correttamente.</p>
        <a class="btn" href="/logout.php">Logout</a>
    </div>
</body>
</html>
?>
