<?php
  require("credentials.php");
  
  // Johanniter, KPK, Raurica, Rheinbund, Zytröseli
  $groupIds = array("765", "300", "766", "767", "768");
  
  if(!empty($_POST["abteilungsId"])){
    getGroups(htmlspecialchars($_POST["abteilungsId"]));
  }

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
    if(!empty($data["linked"]["people"][0]["first_name"])) {
      $kp_vorname = $data["linked"]["people"][0]["first_name"];
      $kp_nachname = $data["linked"]["people"][0]["last_name"];
      $kp_vulgo = $data["linked"]["people"][0]["nickname"];
      $kp_mail = $data["linked"]["people"][0]["email"];
      $website = $data["groups"][0]["website"];
      $kontaktperson = $kp_vorname . " " . $kp_nachname . " / " . $kp_vulgo . " (<a href='mailto:" . ($kp_mail) . "'>E-Mail</a>)";
    }
    
    ?>
    <article class="abteilung">
    <h3 class="data-name"><a target="_blank" href="https://db.scout.ch/de/groups/<?php echo $groupIds ?>"><?php echo $abteilung;?></a></h3>
    <p><strong>Kontaktperson:</strong> 
      <span class="data-person">
        <?php
          if(!empty($data["linked"]["people"][0]["first_name"])) {
            echo($kontaktperson);
          } else {
            echo("<em>Keine Kontaktangaben, bitte direkt auf MiData nachschlagen</em>");
          }
        ?>
      </span>
    </p>
    <p><strong>Koordinaten:</strong></p>

  <?php

    if(!empty($data["linked"]["geolocations"][0]["lat"])) {
      
  ?>

  <div class="table-container">    
    <table>
      <thead>
        <tr>
          <th>Koordinaten</th>
          <th>Link</th>
        </tr>
      </thead>
      <tbody>
  <?php
    $i = 0;
    while($i < count($data["linked"]["geolocations"])) {
      echo("<tr><td>");
      echo($data["linked"]["geolocations"][$i]["lat"]);
      echo(", ");
      echo($data["linked"]["geolocations"][$i]["long"]);
      echo("</td><td>");
      echo("<a href='https://google.com/maps?q=" . $data["linked"]["geolocations"][$i]["lat"] . ",+" . $data["linked"]["geolocations"][$i]["long"] . "' target='_blank' rel='noopener noreferrer'>auf Google Maps anzeigen</a>");
      echo("</td></tr>");
      $i++;
    }
    echo("</tbody></table>");
  } else {
    echo("<p><em>Noch keine Daten erfasst.</em></p>");
  } 
}
?>
</article>