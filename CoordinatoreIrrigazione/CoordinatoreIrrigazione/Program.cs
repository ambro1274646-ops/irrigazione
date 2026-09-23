using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using System.Xml.Linq;


namespace programma_aggiornamento_raspberry
{
    internal class Program
    {
        public class schedulazione_giorno
        {
            public int giorno;
            public string ora;
            public string minuto;
            public double durata;
            public string dispositivi;
        }

        public class linea_da_inserire
        {
            public string linea;
        }

        static void Main(string[] args)
        {
            //Leggo url_config.ini ed estraggo l'url:
            string url = "http://127.0.0.1/schedulazione.php?id_utente=1&id_impianto=1&i=1";
            //string url = File.ReadAllText("/irrigazione/FILE_CONFIG/url_config.ini").Trim('\n', ' ');
            JObject jobj = new JObject();

            using (HttpClient client = new HttpClient())
            {
               
                var risposta = client.GetAsync(url).Result.Content.ReadAsStringAsync().Result;
                jobj = JObject.Parse(risposta);
            }

            //Creo una lista di schedulazione giorno per averne uno per ogni giorno della settimana.
            List<schedulazione_giorno> schedulazione_settimana = new List<schedulazione_giorno>();
            for (int i = 0; i < 7; i++)
            {
                //Creo un oggetto schedulazione giorno chiamato gg per ogni giorno della settimana:
                schedulazione_giorno gg = new schedulazione_giorno();
                gg.giorno = Convert.ToInt32(jobj["schedulazione_settimana"][i]["giorno"]);
                //Controllo se non è null, se null allora inserisco in ora e in minuto null come stringa, altrimenti inserisco i valori dallo split
                if (jobj["schedulazione_settimana"][i]["orario"].ToString() == "null")
                {
                    gg.minuto = "null";
                    gg.ora = "null";
                    gg.durata = 0;
                    gg.dispositivi = "null";
                }
                else
                {
                    gg.ora = jobj["schedulazione_settimana"][i]["orario"].ToString().Split(':')[0];
                    gg.minuto = jobj["schedulazione_settimana"][i]["orario"].ToString().Split(':')[1];
                    gg.durata = Convert.ToDouble(jobj["schedulazione_settimana"][i]["durata"]);
                    gg.dispositivi = jobj["schedulazione_settimana"][i]["dispositivi"].ToString();
                }
                schedulazione_settimana.Add(gg);
            }

            //Inserisco le durate nel file di configurazione del programma attuatore:



            //------------Aggiorno crontab------------//
            //Elimino Righe che contengono il nome del programma con sed:

            
            String parameters = "-i /apri_pompa.exe/d /var/spool/cron/crontabs/root";
            ProcessStartInfo cmdsi = new ProcessStartInfo(@"sed");
            cmdsi.Arguments = parameters;
            cmdsi.CreateNoWindow = false;
            cmdsi.UseShellExecute = false;
            cmdsi.WorkingDirectory = "/irrigazione/aggiornamento_schedulazione";
            Process cmd = Process.Start(cmdsi);
            cmd.WaitForExit();
            

            //Genero le nuove linee da inserire nel file:
            //Per ogni giorno caricato in schedulazione_settimana inserisco una linea in linea.
            //In indice di posizione 0 abbiamo domenica, poi 1 lunedi....
            List<linea_da_inserire> linee_da_inserire = new List<linea_da_inserire>();
            foreach(schedulazione_giorno gior in schedulazione_settimana)
            {
                linea_da_inserire oggetto_linea = new linea_da_inserire();
                if (gior.ora != "null")
                {
                    //Se quel giorno è schedulato devo creare una linea.
                    oggetto_linea.linea = gior.minuto + " " + gior.ora + " * * " + Convert.ToString(gior.giorno) + " mono apri_pompa.exe";
                    linee_da_inserire.Add(oggetto_linea);
                }

            }
            //Aggiungo le linee:
            foreach(linea_da_inserire lin in linee_da_inserire)
            {
                File.AppendAllText("/var/spool/cron/crontabs/root", lin.linea + "\n");
            }
            

            //------Aggiorno File Config per comunicazione arduino-------//
            //irrigazione/FILE_CONFIG/durata_disp.ini
            List<string> contenuto_file = new List<string>();
            foreach(schedulazione_giorno giorn in schedulazione_settimana)
            {
                if (giorn.dispositivi != "null")
                {
                    //Struttura: giorn%disp|disp|...%durata
                    contenuto_file.Add(Convert.ToString(giorn.giorno) + "%" + giorn.dispositivi + "%" + Convert.ToString(giorn.durata));
                }
            }
            File.WriteAllLines("irrigazione/FILE_CONFIG/durata_disp.ini", contenuto_file);

        }
    }
}
