<?php
    $host     = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "if0_42967232_irrigazione_db";

    $cookie_string = $_COOKIE["token"] ?? null;
    if ($cookie_string === null) {
        header("Location: index.html");
        exit;
    }

    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }

    // --- 1) Token -> username (SICURO) ---
    $stmt = $conn->prepare("SELECT username FROM cookies WHERE valore = ?");
    $stmt->bind_param("s", $cookie_string);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Token non valido
        setcookie("token", "", time() - 3600, "/");
        header("Location: index.html");
        exit;
    }
    $row = $result->fetch_assoc();
    $user_name = $row['username'];
    $stmt->close();

    // --- 2) username -> super_user (SICURO) ---
    $stmt = $conn->prepare("SELECT id_utente,super_user FROM utenti WHERE username = ?");
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: index.html");
        setcookie("token", "", time() - 3600, "/");
        exit;
    }
    $row = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    $id_utente=$row["id_utente"];
    if ($row['super_user'] === 't') {
        //Super User:
        //Devo prendere tutti i nomi_db
        $query="SELECT * FROM super_utenti WHERE id_utente=" . $id_utente;
        $conn= new mysqli($host, $username, $password, $database);
        $result=$conn->query($query);

        while ($row = $result->fetch_assoc()) {
            // $row è un array associativo: ['id_utente' => 1, 'nome' => '...', ...]
            echo $row['nome'] . "<br>";
        }


    }

    else {
        //Utente Normale: 1 solo impianto.
        
    }

?>
