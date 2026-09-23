<?php
$host     = "127.0.0.1";
$username = "root";
$password = "";
$database = "if0_42967232_irrigazione_db";

// Accetta solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /index.html");
    exit;
}

$user_name     = $_POST['username'] ?? '';
$user_password = $_POST['password'] ?? '';

if ($user_name === '' || $user_password === '') {
    header("Location: /index.html");
    exit;
}

// Connessione DB
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    error_log("DB connect error: " . $conn->connect_error);
    http_response_code(500);
    exit("Errore interno");
}

// Cerca utente (prepared)
$stmt = $conn->prepare("SELECT password FROM utenti WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $user_name);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Verifica password
// Se le password nel DB sono in chiaro (sconsigliato ma coerente col tuo codice attuale):
$password_ok = $row && $row['password'] === $user_password;

// SE INVECE usi password_hash(), sostituisci la riga sopra con:
// $password_ok = $row && password_verify($user_password, $row['password']);

if (!$password_ok) {
    $conn->close();
    header("Location: /index.html");
    exit;
}

// Genera token
function generaStringaCasuale($lunghezza = 32) {
    $caratteri = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $max = strlen($caratteri) - 1;
    $stringa = '';
    for ($i = 0; $i < $lunghezza; $i++) {
        $stringa .= $caratteri[random_int(0, $max)];
    }
    return $stringa;
}

$token = generaStringaCasuale(32);

// Salva nella tabella cookies
$stmt = $conn->prepare("INSERT INTO cookies (username, valore) VALUES (?, ?)");
$stmt->bind_param("ss", $user_name, $token);
$stmt->execute();
$stmt->close();
$conn->close();

// Manda il cookie
$mai = time() + (10 * 365 * 24 * 60 * 60); // 10 anni (come volevi tu)
setcookie("token", $token, [
    'expires'  => $mai,
    'path'     => '/',
    'secure'   => true,      // richiede HTTPS; se sei in HTTP togli questa riga
    'httponly' => true,
    'samesite' => 'Strict',
]);

// Redirect alla dashboard
header("Location: /dashboard.php");
exit;
?>
