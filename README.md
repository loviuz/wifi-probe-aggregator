# wifi-probe-aggregator
E' un progetto che permette di dimostrare quanto i dispositivi dotati di WiFi siano tracciabili semplicemente lasciando l'antenna attiva e quante informazioni si possono ricavare aggregandoli insieme.

E' composto da:
 - **reader**: fork di https://github.com/hkm/whoishere.py. Questo script in Python permette di raccogliere i MAC address dei dispositivi WiFi nelle vicinanze e di inviarli al web service
 - **web service**: raccoglie i dati inviati dal reader, li salva su database e li restituisce alla GUI
 - **GUI**: visualizza e interpreta i dati salvati per analisi e statistiche

