<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>I tuoi campi - Irrigazione</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

	<style>
	:root {
		--color-bg: #F1F4EE;
		--color-surface: #FFFFFF;
		--color-border: #DDE4D6;
		--color-text: #22301F;
		--color-text-muted: #63735F;
		--color-primary: #2F5233;
		--color-primary-dark: #1E3A22;
		--color-accent: #A85C32;
		--font-display: 'Fraunces', Georgia, serif;
		--font-body: 'Work Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
	}

	* { box-sizing: border-box; }

	body {
		margin: 0;
		background: var(--color-bg);
		color: var(--color-text);
		font-family: var(--font-body);
		line-height: 1.5;
		-webkit-font-smoothing: antialiased;
	}

	.page {
		max-width: 640px;
		margin: 0 auto;
		padding: 20px 16px 60px;
	}

	/* Nome azienda (echo "<h1>...</h1>") */
	.page > h1 {
		font-family: var(--font-display);
		font-weight: 600;
		font-size: clamp(1.9rem, 5vw, 2.5rem);
		color: var(--color-primary-dark);
		margin: 8px 0 4px;
		line-height: 1.15;
	}

	/* Sottotitolo (echo subito dopo l'h1) */
	.page-subtitle {
		color: var(--color-text-muted);
		font-size: 0.95rem;
		margin: 0 0 24px;
	}

	.card {
		background: var(--color-surface);
		border: 1px solid var(--color-border);
		border-radius: 14px;
		padding: 10px;
	}

	.field-list {
		display: flex;
		flex-direction: column;
	}

	.field-card {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		padding: 16px 14px;
		border-radius: 10px;
		text-decoration: none;
		color: var(--color-text);
		border-bottom: 1px solid var(--color-border);
		transition: background .15s ease;
	}
	.field-list .field-card:last-child { border-bottom: none; }
	.field-card:hover { background: var(--color-bg); }

	.field-card__name {
		font-weight: 600;
		font-size: 1.05rem;
		color: var(--color-primary-dark);
	}

	.field-card__arrow {
		color: var(--color-accent);
		font-size: 1.3rem;
		line-height: 1;
		flex-shrink: 0;
	}

	@media (min-width: 700px) {
		.page { padding: 48px 24px 80px; }
		.card { padding: 14px; }
		.field-card { padding: 18px 18px; }
	}
	</style>
</head>

<body>
<div class="page">
<?php
    $host     = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "irrigazione_db";

    $cookie_string = $_COOKIE["token"] ?? null;
    if ($cookie_string === null) {
        header("Location: index.php");
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
        header("Location: index.php");
        exit;
    }
    $row = $result->fetch_assoc();
    $user_name = $row['username'];
    $stmt->close();

    // --- 2) username -> super_user (SICURO) ---
    $stmt = $conn->prepare("SELECT id_utente,super_user,nome_azienda FROM utenti WHERE username = ?");
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
    $nome_azienda=$row["nome_azienda"];
    //-------------------------------------------------------------//
    //---------DISTINZIONE TRA SUPER USER E UTENTE NORMALE---------//
    //-------------------------------------------------------------//
    if ($row['super_user'] === 't') {
        //+++++++++++++++++++++++++++++++++++//
        //++++++++++++Super User:++++++++++++//
        //+++++++++++++++++++++++++++++++++++//
        $query="SELECT * FROM super_utenti WHERE id_utente=" . $id_utente;
        $conn= new mysqli($host, $username, $password, $database);
        $result=$conn->query($query);
        $conn->close();

        echo "<h1>" . $nome_azienda . "</h1>";
        echo "<p class=\"page-subtitle\">Seleziona un campo per continuare</p>";
        echo "<div class=\"card\"><div class=\"field-list\">";
        while ($row = $result->fetch_assoc()) {
            //Ogni db associato all'utente:
            //$row è un array associativo: ['id_utente' => 1, 'nome' => '...', ...]
            //query per chiedere il nome di ogni campo:
            $query="SELECT nome_campo FROM anagrafica";
            $conn= new mysqli($host, $username, $password, $row['nome_db_impianto']);
            $result_campo=$conn->query($query);
            $conn->close();

            $row_campo = $result_campo->fetch_assoc();

            //questi sono i bottoni che portano ai vari impianti.
            echo "<a class=\"field-card\" href=\"/dashboard_impianto.php?nome_db=" . $row['nome_db_impianto'] . "\">";
            echo "<span class=\"field-card__name\">" . $row_campo["nome_campo"] . "</span>";
            echo "<span class=\"field-card__arrow\">&rsaquo;</span>";
            echo "</a>";
        }
        echo "</div></div>";

    }
    else {
        //+++++++++++++++++++++++++++++++++++++++//
        //++++++++++++Utente Normale:++++++++++++//
        //+++++++++++++++++++++++++++++++++++++++//
        //query per nome del DATABASE:
        $query="SELECT * FROM utenti WHERE id_utente=" . $id_utente;
        $conn= new mysqli($host, $username, $password, $database);
        $result=$conn->query($query);
        $row_utente = $result->fetch_assoc();
        $conn->close();
        
        //Una volta che ho il nome del database mi prendo il nome del CAMPO:
        $query="SELECT nome_campo FROM anagrafica";
        $conn= new mysqli($host, $username, $password, $row_utente['nome_db_impianto']);
        $result_campo=$conn->query($query);
        $conn->close();
        $row_campo = $result_campo->fetch_assoc();

        echo "<h1>" . $nome_azienda . "</h1>";
        echo "<p class=\"page-subtitle\">Seleziona un campo per continuare</p>";
        echo "<div class=\"card\"><div class=\"field-list\">";
        echo "<a class=\"field-card\" href=\"/dashboard_impianto.php?nome_db=" . $row_utente['nome_db_impianto'] . "\">";
        echo "<span class=\"field-card__name\">" . $row_campo["nome_campo"] . "</span>";
        echo "<span class=\"field-card__arrow\">&rsaquo;</span>";
        echo "</a>";
        echo "</div></div>";
    }
?>
</div>
</body>
</html>