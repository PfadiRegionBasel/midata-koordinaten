# midata-koordinaten

Zeige die von den (PRB-)Abteilungen hinterlegten MiData-Koordinaten an.

## Verwendung

```
Warnung: Aktuell ist noch viel Handarbeit dabei.
```

Zuerst die Dateien `credentials.php` und `credentials.py` erstellen und dort in der Variable `token` den MiData-API-Key abspeichern. Somit können das PHP- als auch das Python-Skript auf die MiData-API zugreifen.

Anschliessend lassen sich die Abteilungs-Koordinaten mittels dem Python-Skript auslesen und in die Datei `output.txt` schreiben.

Aktuell werden die Daten im Anschluss dann händisch kopiert, in diesem Fall in Tabellen, welche die Grundlage für eine [Google My Maps](https://www.google.com/intl/de_ch/maps/about/mymaps/) ist.
