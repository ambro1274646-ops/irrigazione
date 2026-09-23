<?php
    // Parametri di connessione
    $host = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "irrigazione_db";

    $mysqli = new mysqli($host, $username, $password, $database);

    $id_impianto = $_GET["id_impianto"];
    $id_utente = $_GET["id_utente"];

    $nome_db_impianto = null; // inizializzata per sicurezza
    $query;
    $query_gen_result;
    $super_user_result;
    $row;

    $risposta_json;
    

    // Controllo errori di connessione, adesso stampa output dopo invia errore che ferma il programma sul rasperry
    if ($mysqli->connect_error) {
        die("Errore di connessione: " . $mysqli->connect_error);
    }

    $mysqli->set_charset("utf8mb4");

    // Prima query: recupero il campo super_user
    $query = "SELECT super_user FROM utenti WHERE id_utente=" . $id_utente;
    $query_gen_result = $mysqli->query($query);
    $super_user_result = $query_gen_result->fetch_assoc();

    // Se non è super user
    if ($super_user_result['super_user'] == "f") {
        $query = "SELECT nome_db_impianto FROM utenti WHERE id_utente=" . $id_utente;
        $query_gen_result = $mysqli->query($query);
        $row = $query_gen_result->fetch_assoc();
        $nome_db_impianto = $row['nome_db_impianto'];
    }
    // Se è super user vado a cercare il suo id impianto nella tabella super_utenti
    else if ($super_user_result['super_user'] == "t") {
        $query = "SELECT nome_db_impianto FROM super_utenti WHERE id_impianto=" . $id_impianto;
        $query_gen_result = $mysqli->query($query);
        $row = $query_gen_result->fetch_assoc();
        $nome_db_impianto = $row['nome_db_impianto'];
    }
    //echo "Il tuo nome db impianto è " . $nome_db_impianto ."\n";

    //Adesso abbiamo il nome del db, costruiamo la query per reperire i giorni e le durate.
    //Chiudiamo questa connessione al db e ne apriamo un altra al nuovo db.
    $mysqli->close();

    $mysqli2 = new mysqli($host, $username, $password, $nome_db_impianto);
    if ($mysqli2->connect_error) {
        die("Errore di connessione al db impianto: " . $mysqli2->connect_error);
    }
    $mysqli2->set_charset("utf8mb4");
    $query="SELECT * FROM schedulazione";
    //Riutilizzo di query gen result per storage del risultato query:
    $query_gen_result=$mysqli2->query($query);
    $mysqli2->close();


    //Inserisco anche i pin dei dispositivi che vanno utilizzati dalla tabella "schedulazione" per ogni giorno: formato: pin|pin1...
    $risposta_json='{"sistema":123,"schedulazione_settimana":[ ';
    $i=0;
    while ($row = $query_gen_result->fetch_assoc()) {
        // qui uso $row['nome_colonna']
        if($i<6){
            if($row["ora_inizio"]==null){
                $risposta_json=$risposta_json . '{"orario":"null","durata":null,"giorno":' . $row["id_giorno"] . ',"dispositivi":"null"},';
            }
            else{
                $risposta_json=$risposta_json . '{"orario":"' . $row["ora_inizio"] . '","durata":' . (string) $row["durata"] . ',"giorno":' . $row["id_giorno"] . ',"dispositivi":"' . $row["dispositivi"] . '"},';
            } 
        }
        else if($i==6){
            if($row["ora_inizio"]==null){
                $risposta_json=$risposta_json . '{"orario":"null","durata":null,"giorno":' . $row["id_giorno"] . ',"dispositivi":"null"}]';
            }
            else{
                $risposta_json=$risposta_json . '{"orario":"' . $row["ora_inizio"] . '","durata":' . (string) $row["durata"] . ',"giorno":' . $row["id_giorno"] . ',"dispositivi":"' . $row["dispositivi"] . '"}]';
            } 
        }
        $i=$i+1;
    }
    $risposta_json=$risposta_json . "}";
    echo($risposta_json);

?>
