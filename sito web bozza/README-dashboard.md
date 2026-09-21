# Dashboard web

La nuova UI è disponibile in `htdocs/dashboard_home.php` e `htdocs/dashboard_impianto.php`. È stata mantenuta isolata dal coordinatore C# e dai programmi Arduino.

## Collegamento ai dati
- La sessione può valorizzare `company_name`, `company_logo`, `irrigation_systems`, `schedule`, `soil_readings`, `air_readings` e `rain_readings`.
- Il form della schedulazione invia i dati a `salva_schedulazione.php`, lasciando invariata la logica backend esistente.
- I grafici usano SVG senza dipendenze esterne e sono pronti a ricevere le serie provenienti dalle future tabelle sensori.
- Per pubblicare la cartella, includere `htdocs/` nell'archivio del sito già utilizzato dal deploy.
