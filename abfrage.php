<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style> body { font-family: monospace; } </style>
</head>
<body>
<?php
  require("credentials.php");
  // Johanniter, KPK, Raurica, Rheinbund, Zytröseli
  $groupIds = array("765", "300", "766", "767", "768");
  
  getGroups(786);

  function query($qry) {
    global $token;

    $url = "https://db.scout.ch/de/groups" . $qry . ".json?token=" . $token;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-Token: '.$token, 'Accept: application/json'));

    $result = curl_exec($ch);

    curl_close($ch);

    $decoded = json_decode($result, TRUE);

    return $decoded;
  }

  function getGroups($groupIds) {
    $groupId = array();
    $groupId = explode(",", $groupIds);

    $data = array();
    foreach ($groupId as $id) {
      $qry = "/" . $id;
      $data = query($qry);
    }

    $abteilung = $data["groups"][0]["name"];
    $kp_vorname = $data["linked"]["people"][0]["first_name"];
    $kp_nachname = $data["linked"]["people"][0]["last_name"];
    $kp_vulgo = $data["linked"]["people"][0]["nickname"];
    $kp_mail = $data["linked"]["people"][0]["email"];
    $website = $data["groups"][0]["website"];

    ?>

    <h2 class="data-name"><a target="_blank" href="https://db.scout.ch/de/groups/<?php echo $groupIds ?>"><?php echo $abteilung;?></a></h2>
    <p><strong>Kontaktperson:</strong> 
      <span class="data-person">
        <?php echo($kp_vorname . " " . $kp_nachname) . " v/o " . $kp_vulgo; ?> (<a href="mailto:<?php echo($kp_mail);?>">E-Mail</a>)
      </span>
    </p>

    <table>
      <thead>
        <tr>
          <th>Lat</th>
          <th>Long</th>
        </tr>
      </thead>

    <?php

    echo "<tbody>";
    $i = 0;
    while($i < count($data["linked"]["geolocations"])) {
      echo("<tr><td>");
      echo($data["linked"]["geolocations"][$i]["lat"]);
      echo("</td><td>");
      echo($data["linked"]["geolocations"][$i]["long"]);
      echo("</td></tr>");
      $i++;
    }
  }
?>

  </tbody>
  </table>
</body>
</html>