using System;
using System.IO.Ports;

class Program
{
    static void Main()
    {
        // 1. Crea la connessione alla porta seriale
        //    Cambia "COM3" con la porta giusta per il tuo Arduino
        //    Il numero 9600 deve essere uguale a quello scritto in Serial.begin() su Arduino
        SerialPort porta = new SerialPort("COM3", 9600);

        // 2. Apre la porta
        porta.Open();
        Console.WriteLine("Connesso! In attesa di dati...");

        // 3. Ciclo infinito: legge e stampa i dati appena arrivano
        while (true)
        {
            string dato = porta.ReadLine();  // aspetta una riga di testo
            Console.WriteLine("Valore ricevuto: " + dato);
        }
    }
}