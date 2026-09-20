const int ANALOG_PIN = A0;   // pin analogico, dichiarato globalmente
int valore_letto;

void setup() {
  Serial.begin(9600);
}

void loop() {
  // ogni 20 secondi scrive sulla seriale
  valore_letto = analogRead(ANALOG_PIN);
  Serial.println(valore_letto);
  delay(20000);  // aspetta 20 secondi
}