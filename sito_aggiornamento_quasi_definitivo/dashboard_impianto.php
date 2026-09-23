<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Campo - Irrigazione</title>

	<!-- Font: un serif organico per i titoli, un sans pulito per il resto -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

	<!-- jQuery + ClockPicker (selettore ora "a orologio") -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.min.js"></script>

	<!-- Styles -->
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
		max-width: 880px;
		margin: 0 auto;
		padding: 20px 16px 60px;
	}

	/* Titolo del campo (generato dal php con echo "<h1>...</h1>") */
	.page > h1 {
		font-family: var(--font-display);
		font-weight: 600;
		font-size: clamp(2rem, 5vw, 2.75rem);
		color: var(--color-primary-dark);
		margin: 8px 0 28px;
		line-height: 1.15;
	}

	/* Messaggio "Schedulazione aggiornata correttamente." */
	.page > p {
		background: #E4EFE1;
		border: 1px solid var(--color-primary);
		color: var(--color-primary-dark);
		padding: 10px 16px;
		border-radius: 8px;
		font-size: 0.9rem;
		margin: 0 0 20px;
	}

	.card {
		background: var(--color-surface);
		border: 1px solid var(--color-border);
		border-radius: 14px;
		padding: 24px 18px;
		margin-bottom: 28px;
	}

	.card h2 {
		font-family: var(--font-display);
		font-weight: 600;
		font-size: 1.5rem;
		color: var(--color-primary-dark);
		margin: 0 0 18px;
	}

	/* --- Riga di ogni giorno nella schedulazione --- */
	.day-row {
		display: flex;
		flex-direction: column;
		gap: 10px;
		padding: 16px 0;
		border-bottom: 1px solid var(--color-border);
	}
	.day-row:last-of-type { border-bottom: none; padding-bottom: 0; }

	.day-row__name {
		font-weight: 600;
		color: var(--color-primary-dark);
		font-size: 1.05rem;
	}

	.day-row__fields {
		display: flex;
		flex-wrap: wrap;
		gap: 12px;
	}

	.field {
		position: relative;
		display: flex;
		flex-direction: column;
		gap: 4px;
		font-size: 0.82rem;
		color: var(--color-text-muted);
	}
	.field input {
		font-family: inherit;
		font-size: 0.95rem;
		padding: 8px 10px;
		border: 1px solid var(--color-border);
		border-radius: 8px;
		background: #fff;
		color: var(--color-text);
		min-width: 130px;
	}
	.field input:focus {
		outline: 2px solid var(--color-primary);
		outline-offset: 1px;
		border-color: var(--color-primary);
	}
	/* Ora e durata si compilano solo tramite i selettori grafici */
	.field input[readonly] {
		cursor: pointer;
		background: #fff;
	}

	.day-row__devices {
		display: flex;
		flex-wrap: wrap;
		gap: 8px;
	}

	.device-chip { position: relative; cursor: pointer; }
	.device-chip input {
		position: absolute;
		opacity: 0;
		width: 1px;
		height: 1px;
	}
	.device-chip span {
		display: inline-block;
		padding: 6px 14px;
		border-radius: 999px;
		border: 1px solid var(--color-border);
		font-size: 0.85rem;
		color: var(--color-text-muted);
		background: #fff;
		transition: background .15s ease, color .15s ease, border-color .15s ease;
	}
	.device-chip input:checked + span {
		background: var(--color-primary);
		border-color: var(--color-primary);
		color: #fff;
	}
	.device-chip input:focus-visible + span {
		outline: 2px solid var(--color-primary);
		outline-offset: 2px;
	}

	.btn-save {
		margin-top: 22px;
		padding: 12px 28px;
		border: none;
		border-radius: 8px;
		background: var(--color-primary);
		color: #fff;
		font-family: inherit;
		font-size: 1rem;
		font-weight: 600;
		cursor: pointer;
		width: 100%;
	}
	.btn-save:hover { background: var(--color-primary-dark); }

	/* --- Selettore grafico della durata (ore + minuti) --- */
	.duration-popover {
		display: none;
		position: absolute;
		top: calc(100% + 6px);
		left: 0;
		z-index: 20;
		background: #fff;
		border: 1px solid var(--color-border);
		border-radius: 10px;
		box-shadow: 0 8px 24px rgba(30, 58, 34, 0.18);
		padding: 12px;
		grid-template-columns: auto auto;
		gap: 10px 16px;
	}
	.duration-popover.open { display: grid; }

	.duration-popover__col-label {
		font-size: 0.72rem;
		color: var(--color-text-muted);
		text-align: center;
		margin: 0 0 4px;
	}
	.duration-popover__list {
		display: flex;
		flex-direction: column;
		gap: 4px;
		max-height: 150px;
		overflow-y: auto;
	}
	.duration-popover__list button {
		border: 1px solid var(--color-border);
		background: #fff;
		border-radius: 6px;
		padding: 5px 14px;
		font-family: inherit;
		font-size: 0.85rem;
		color: var(--color-text);
		cursor: pointer;
		min-width: 46px;
	}
	.duration-popover__list button.selected {
		background: var(--color-primary);
		border-color: var(--color-primary);
		color: #fff;
	}
	.duration-popover__done {
		grid-column: 1 / -1;
		justify-self: end;
		border: none;
		background: var(--color-primary);
		color: #fff;
		padding: 6px 16px;
		border-radius: 6px;
		font-family: inherit;
		font-size: 0.82rem;
		cursor: pointer;
	}

	/* --- Grafici sensori --- */
	.chart-block { margin-bottom: 32px; }
	.chart-block:last-child { margin-bottom: 0; }
	.chart-block h3 {
		font-weight: 600;
		font-size: 1.05rem;
		color: var(--color-text);
		margin: 0 0 10px;
	}
	.chart-block p { color: var(--color-text-muted); font-size: 0.9rem; margin: 0; }

	[id^="chartdiv_"] {
		width: 100%;
		height: 300px;
		max-width: 100%;
	}

	/* --- ClockPicker: adattiamo i colori alla palette del sito --- */
	.clockpicker-popover.popover { border-color: var(--color-border); }
	.clockpicker-popover .popover-title {
		background: var(--color-primary);
		color: #fff;
	}
	.clockpicker-tick.active,
	.clockpicker-tick:hover {
		background-color: var(--color-primary) !important;
		color: #fff;
	}
	.clockpicker-canvas-bg,
	.clockpicker-canvas-bearing {
		fill: var(--color-primary) !important;
	}
	.clockpicker-canvas-fg { stroke: var(--color-primary) !important; }
	.clockpicker-button { color: var(--color-primary); }
	.clockpicker-button:hover { background-color: #E4EFE1; }

	/* --- Schermi più larghi --- */
	@media (min-width: 700px) {
		.page { padding: 40px 24px 80px; }
		.card { padding: 32px 36px; }

		.day-row {
			flex-direction: row;
			align-items: center;
			gap: 20px;
		}
		.day-row__name { width: 100px; flex-shrink: 0; }
		.day-row__fields { flex-shrink: 0; }
		.day-row__devices { margin-left: auto; }

		.btn-save { width: auto; }

		[id^="chartdiv_"] { height: 420px; }
	}
	</style>

	<!-- Resources -->
	<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
	<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
	<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
</head>

<body>
<div class="page">
    <?php
        $host     = "127.0.0.1";
        $username = "root";
        $password = "";
        $database = "irrigazione_db";

        //Se il cookie non esiste allora redirect:
        $cookie_string = $_COOKIE["token"] ?? null;
        if ($cookie_string === null) {
            header("Location: index.php");
            exit;
        }

        //Problemi di connessione:
        $conn = new mysqli($host, $username, $password, $database);
        if ($conn->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        //recuper il token dal db, se esiste prendo l'user associato:
        $stmt = $conn->prepare("SELECT username FROM cookies WHERE valore = ?");
        $stmt->bind_param("s", $cookie_string);
        $stmt->execute();
        $result = $stmt->get_result();
        $conn->close();
        $stmt->close();

        //Se il cookie è settato ma il valore non viene trovato, redirect e togli il disset del cokookie:
        if ($result->num_rows === 0) {
            // Token non valido
            setcookie("token", "", time() - 3600, "/");
            header("Location: index.php");
            exit;
        }

        //LOGGATO CORRETTAMENTE
        //Prendiamo il nome del db dal parametro get:
        $nome_db=$_GET["nome_db"];
        $conn= new mysqli($host, $username, $password,$nome_db);

        //Query per l'anagrafica dell'impianto:
        $query="SELECT * FROM anagrafica";
        $risultati_anagrafica=$conn->query($query);
        $risultati_anagrafica_array=$risultati_anagrafica->fetch_assoc();
        echo "<h1>" . $risultati_anagrafica_array["nome_campo"] . "</h1>";


        //------------------------------------------//
        //--------------SCHEDULAZIONE:--------------//
        //------------------------------------------//
        // Nomi dei giorni, stesso indice usato in id_giorno (0 = domenica)
        $nomi_giorni = [
            0 => "Domenica",
            1 => "Lunedì",
            2 => "Martedì",
            3 => "Mercoledì",
            4 => "Giovedì",
            5 => "Venerdì",
            6 => "Sabato"
        ];
        
        // Se l'utente ha inviato il form, salviamo le modifiche
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            for ($giorno = 0; $giorno <= 6; $giorno++) {
                $ora_inizio = $_POST["ora_inizio_" . $giorno] ?? null;
                $durata     = $_POST["durata_" . $giorno] ?? null;
        
                // Le checkbox selezionate arrivano come array di pin, es. ["1","2"]
                $dispositivi_selezionati = $_POST["dispositivi_" . $giorno] ?? [];
                $dispositivi_stringa = implode("|", $dispositivi_selezionati);
        
                if ($ora_inizio === "") { $ora_inizio = null; }
                if ($durata === "")     { $durata = null; }
        
                $stmt = $conn->prepare("UPDATE schedulazione SET ora_inizio = ?, durata = ?, dispositivi = ? WHERE id_giorno = ?");
                $stmt->bind_param("sdsi", $ora_inizio, $durata, $dispositivi_stringa, $giorno);
                $stmt->execute();
                $stmt->close();
            }
            echo "<p>Schedulazione aggiornata correttamente.</p>";
        }
        
        // Prendiamo l'elenco dei dispositivi (nome + pin) per costruire le checkbox
        $query = "SELECT * FROM dispositivi";
        $risultati_dispositivi = $conn->query($query);
        $lista_dispositivi = [];
        while ($riga = $risultati_dispositivi->fetch_assoc()) {
            $lista_dispositivi[] = $riga;
        }
        
        // Prendiamo la schedulazione aggiornata di tutti i 7 giorni
        $query = "SELECT * FROM schedulazione ORDER BY id_giorno";
        $risultati_schedulazione_tutti = $conn->query($query);
        $schedulazione_completa = [];
        while ($riga = $risultati_schedulazione_tutti->fetch_assoc()) {
            $schedulazione_completa[$riga["id_giorno"]] = $riga;
        }
        
        $conn->close();
        
        ?>

    <!--+++++++++++++++++++ QUI VIENE GENERATO L'HTML SCHEDULAZIONE ++++++++++++++++++-->
    <section class="card">
    <h2>Schedulazione settimanale</h2>
    <form method="POST" action="">
        <?php foreach ($nomi_giorni as $id_giorno => $nome_giorno): ?>
            <?php $riga_giorno = $schedulazione_completa[$id_giorno]; ?>
            <div class="day-row">
                <div class="day-row__name"><?php echo $nome_giorno; ?></div>

                <div class="day-row__fields">
                    <label class="field">
                        <span>Ora inizio</span>
                        <input type="text" class="clockpicker" readonly
                            name="ora_inizio_<?php echo $id_giorno; ?>"
                            value="<?php echo $riga_giorno["ora_inizio"]; ?>"
                            placeholder="--:--">
                    </label>

                    <div class="field field--duration">
                        <span>Durata</span>
                        <input type="text" class="duration-display" readonly placeholder="--h --m">
                        <input type="hidden" class="duration-value"
                            name="durata_<?php echo $id_giorno; ?>"
                            value="<?php echo $riga_giorno["durata"]; ?>">

                        <div class="duration-popover">
                            <div>
                                <p class="duration-popover__col-label">Ore</p>
                                <div class="duration-popover__list" data-unit="ore">
                                    <?php for ($o = 0; $o <= 6; $o++): ?>
                                        <button type="button" data-val="<?php echo $o; ?>"><?php echo $o; ?></button>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div>
                                <p class="duration-popover__col-label">Minuti</p>
                                <div class="duration-popover__list" data-unit="minuti">
                                    <?php foreach ([0, 15, 30, 45] as $m): ?>
                                        <button type="button" data-val="<?php echo $m; ?>"><?php echo str_pad($m, 2, "0", STR_PAD_LEFT); ?></button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <button type="button" class="duration-popover__done">Fatto</button>
                        </div>
                    </div>
                </div>

                <div class="day-row__devices">
                <?php
                    // Pin già selezionati per questo giorno, es. "1|2" -> ["1","2"]
                    $pin_selezionati = $riga_giorno["dispositivi"] !== "" ? explode("|", $riga_giorno["dispositivi"]) : [];
                ?>
                <?php foreach ($lista_dispositivi as $dispositivo): ?>
                    <label class="device-chip">
                        <input type="checkbox"
                            name="dispositivi_<?php echo $id_giorno; ?>[]"
                            value="<?php echo $dispositivo["pin"]; ?>"
                            <?php echo in_array($dispositivo["pin"], $pin_selezionati) ? "checked" : ""; ?>>
                        <span><?php echo $dispositivo["nome_dispositivo"]; ?></span>
                    </label>
                <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn-save">Salva schedulazione</button>
    </form>
    </section>


<!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
<!--++++++++++++++++++ Generazione Grafici ++++++++++++++++++-->
<!--+++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
<?php
    // Riapriamo la connessione per leggere i dati dei sensori
    $conn = new mysqli($host, $username, $password, $nome_db);
 
    // Invece di ancorare la finestra a "oggi" (data reale del server),
    // la anconiamo all'ultimo dato registrato in ogni tabella: così il
    // grafico mostra sempre gli ultimi 7 giorni di dati disponibili,
    // sia con dati di esempio vecchi sia in produzione.
 
    // --- Umidità aria ---
    $query = "SELECT * FROM dati_aria
              WHERE data_ora >= (SELECT MAX(data_ora) FROM dati_aria) - INTERVAL 7 DAY
              ORDER BY data_ora";
    $risultati_umidita_aria = $conn->query($query);
    $dati_aria_array = [];
    while ($riga = $risultati_umidita_aria->fetch_assoc()) {
        $dati_aria_array[] = [
            "date"  => str_replace(" ", "T", $riga["data_ora"]),
            "value" => $riga["valore_umidita_aria"]
        ];
    }
 
    // --- Umidità terreno ---
    $query = "SELECT * FROM dati_terreno
              WHERE data_ora >= (SELECT MAX(data_ora) FROM dati_terreno) - INTERVAL 7 DAY
              ORDER BY data_ora";
    $risultati_umidita_terreno = $conn->query($query);
    $dati_terreno_array = [];
    while ($riga = $risultati_umidita_terreno->fetch_assoc()) {
        $dati_terreno_array[] = [
            "date"  => str_replace(" ", "T", $riga["data_ora"]),
            "value" => $riga["valore_umidita_terreno"]
        ];
    }
 
    // --- Pioggia ---
    $query = "SELECT * FROM dati_pioggia
              WHERE data_ora >= (SELECT MAX(data_ora) FROM dati_pioggia) - INTERVAL 7 DAY
              ORDER BY data_ora";
    $risultati_pioggia = $conn->query($query);
    $dati_pioggia_array = [];
    while ($riga = $risultati_pioggia->fetch_assoc()) {
        $dati_pioggia_array[] = [
            "date"  => str_replace(" ", "T", $riga["data_ora"]),
            "value" => $riga["mm_pioggia"]
        ];
    }
 
    $conn->close();
?>

<section class="card">
<h2>Sensori — ultimi 7 giorni</h2>

<div class="chart-block">
<h3>Umidità aria</h3>
<?php if (count($dati_aria_array) === 0): ?>
    <p>Nessun dato disponibile.</p>
<?php else: ?>
    <div id="chartdiv_aria"></div>
<?php endif; ?>
</div>

<div class="chart-block">
<h3>Umidità terreno</h3>
<?php if (count($dati_terreno_array) === 0): ?>
    <p>Nessun dato disponibile.</p>
<?php else: ?>
    <div id="chartdiv_terreno"></div>
<?php endif; ?>
</div>

<div class="chart-block">
<h3>Pioggia</h3>
<?php if (count($dati_pioggia_array) === 0): ?>
    <p>Nessun dato disponibile.</p>
<?php else: ?>
    <div id="chartdiv_pioggia"></div>
<?php endif; ?>
</div>

</section>

<script>
// Attiviamo l'orologio grafico su tutti i campi "ora inizio"
$(document).ready(function () {
    $('.clockpicker').clockpicker({
        autoclose: true,
        donetext: 'OK',
        placement: 'top',
        align: 'left',
        twelvehour: false
    });
});

// Selettore grafico della durata (ore + minuti), stesso spirito dell'orologio
function initDurationPickers() {
    var campi = document.querySelectorAll('.field--duration');

    campi.forEach(function (campo) {
        var display       = campo.querySelector('.duration-display');
        var hidden        = campo.querySelector('.duration-value');
        var popover       = campo.querySelector('.duration-popover');
        var bottoni       = campo.querySelectorAll('.duration-popover__list button');
        var bottoneFatto  = campo.querySelector('.duration-popover__done');

        function leggiSelezione(unita) {
            var attivo = campo.querySelector('.duration-popover__list[data-unit="' + unita + '"] button.selected');
            return attivo ? parseInt(attivo.dataset.val, 10) : 0;
        }

        function mostraValore(ore, minuti) {
            display.value = ore + "h " + (minuti < 10 ? "0" + minuti : minuti) + "m";
        }

        // Inizializza vista e selezione a partire dal valore salvato (ore decimali)
        function inizializza() {
            if (hidden.value === "" || hidden.value === null) {
                display.value = "";
                return;
            }
            var totale = parseFloat(hidden.value);
            var ore    = Math.floor(totale);
            var minuti = Math.round(((totale - ore) * 60) / 15) * 15;
            if (minuti === 60) { ore++; minuti = 0; }

            mostraValore(ore, minuti);

            bottoni.forEach(function (b) {
                var unita  = b.parentElement.dataset.unit;
                var valore = parseInt(b.dataset.val, 10);
                var attivo = (unita === "ore" && valore === ore) || (unita === "minuti" && valore === minuti);
                b.classList.toggle("selected", attivo);
            });
        }

        // Click su un'ora o un minuto: aggiorna selezione e valore salvato
        bottoni.forEach(function (b) {
            b.addEventListener("click", function () {
                var lista = b.parentElement;
                lista.querySelectorAll("button").forEach(function (x) { x.classList.remove("selected"); });
                b.classList.add("selected");

                var ore    = leggiSelezione("ore");
                var minuti = leggiSelezione("minuti");
                hidden.value = (ore + minuti / 60).toFixed(2);
                mostraValore(ore, minuti);
            });
        });

        // Non chiudere il popover quando si clicca dentro
        popover.addEventListener("click", function (e) { e.stopPropagation(); });

        bottoneFatto.addEventListener("click", function () {
            popover.classList.remove("open");
        });

        display.addEventListener("click", function (e) {
            e.stopPropagation();
            document.querySelectorAll(".duration-popover.open").forEach(function (p) {
                if (p !== popover) p.classList.remove("open");
            });
            popover.classList.toggle("open");
        });

        inizializza();
    });

    // Click fuori da un popover: lo chiude
    document.addEventListener("click", function () {
        document.querySelectorAll(".duration-popover.open").forEach(function (p) {
            p.classList.remove("open");
        });
    });
}

document.addEventListener("DOMContentLoaded", initDurationPickers);
</script>

<script>
setTimeout(function () {
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);

        // Funzione riutilizzabile: crea un grafico a linea semplice dentro un div
        function creaGraficoLinea(idDiv, dati, etichettaValore, colore) {
            var chart = am4core.create(idDiv, am4charts.XYChart);
            chart.paddingRight = 20;
            chart.paddingLeft = 0;
            chart.data = dati;

            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
            dateAxis.renderer.grid.template.location = 0;
            dateAxis.renderer.grid.template.stroke = am4core.color("#DDE4D6");
            dateAxis.renderer.labels.template.fill = am4core.color("#63735F");

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.grid.template.stroke = am4core.color("#DDE4D6");
            valueAxis.renderer.labels.template.fill = am4core.color("#63735F");

            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.dateX = "date";
            series.dataFields.valueY = "value";
            series.tooltipText = "{valueY}";
            series.tooltip.pointerOrientation = "vertical";
            series.stroke = am4core.color(colore);
            series.strokeWidth = 2.5;
            series.fill = am4core.color(colore);
            series.fillOpacity = 0.12;
            series.tensionX = 0.8;

            var bullet = series.bullets.push(new am4charts.CircleBullet());
            bullet.circle.radius = 3;
            bullet.circle.fill = am4core.color(colore);
            bullet.circle.strokeWidth = 0;

            chart.cursor = new am4charts.XYCursor();
            chart.cursor.xAxis = dateAxis;
        }

        <?php if (count($dati_aria_array) > 0): ?>
        creaGraficoLinea("chartdiv_aria", <?php echo json_encode($dati_aria_array); ?>, "Umidità aria (%)", "#3E8E7E");
        <?php endif; ?>

        <?php if (count($dati_terreno_array) > 0): ?>
        creaGraficoLinea("chartdiv_terreno", <?php echo json_encode($dati_terreno_array); ?>, "Umidità terreno (%)", "#A85C32");
        <?php endif; ?>

        <?php if (count($dati_pioggia_array) > 0): ?>
        creaGraficoLinea("chartdiv_pioggia", <?php echo json_encode($dati_pioggia_array); ?>, "Pioggia (mm)", "#2F6690");
        <?php endif; ?>
    });
nascondiChart();
}, 100);
</script>

<script>
	function nascondiChart() {
		var aTags = document.getElementsByTagName("title");
		var searchText = "Chart created using amCharts library";
		var found;

		for (var i = 0; i < aTags.length; i++) {
			if (aTags[i].textContent == searchText) {
				found = aTags[i];
				found.parentElement.style.display = 'none';

			}
		}
	}
</script>

</div>
</body>
</html>