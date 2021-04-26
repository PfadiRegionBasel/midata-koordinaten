import requests

from credentials import token

# Bezirke: Johanniter, KPK, Raurica, Rheinbund, Zytröseli

abteilungenJohanniter = [8, 786, 788, 789, 790, 791] # ohne Blauen 787 und MPR 793
abteilungenKPK = [800, 798, 796, 810, 794, 805, 809, 795, 797, 9024, 802, 803, 804, 806, 301, 807, 9150]
abteilungenRaurica = [811, 812, 813, 814, 815]
abteilungenRheinbund = [816, 817, 818, 819, 6828]
abteilungenZytroeseli = [820, 821, 822, 823, 824]

abteilungsIds = abteilungenJohanniter + abteilungenKPK + abteilungenRaurica + abteilungenRheinbund + abteilungenZytroeseli
textFile = open("output.txt", "w")

i = 0
while i < len(abteilungsIds):
  response = requests.get("https://db.scout.ch/de/groups/" + str(abteilungsIds[i]) + ".json?token=" + token)
  nameAbteilung = response.json()["groups"][0]["name"]
  nameBezirk = response.json()["linked"]["groups"][0]["name"]
  lenCoordinates = len(response.json()["linked"]["geolocations"]) # Anzahl Einträge mit Koordinaten abfragen
  j = 0
  while j < lenCoordinates:
    coordinateLat = response.json()["linked"]["geolocations"][j]["lat"]
    coordinateLong = response.json()["linked"]["geolocations"][j]["long"]

    text = (str(abteilungsIds[i]) + "," + nameAbteilung + "," + nameBezirk + "," + coordinateLat + "," + coordinateLong + "\n")
    textFile.write(text)

    # print(text)
    j = j + 1
  i = i + 1
textFile.close
