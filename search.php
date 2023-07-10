<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['artist'])) {
  // Get the entered artist name
  $artist = $_GET['artist'];

  // Sanitize the artist name
  $artist = htmlspecialchars($artist, ENT_QUOTES, 'UTF-8');

  // URL-encode the artist name for the search query
  $encodedArtist = urlencode($artist);

  // De URL van de live website
  $url = "https://www.top40.nl/top40-artiesten/{$encodedArtist}";

  // De HTML van de live website inladen
  $html = file_get_contents($url);

  // Een nieuwe DOMDocument instantie maken
  $doc = new DOMDocument();

  // De HTML inladen in de DOMDocument
  @$doc->loadHTML($html);

  // Een nieuwe DOMXPath instantie maken
  $xpath = new DOMXPath($doc);

  // Het maximale aantal div-elementen dat je wilt doorlopen
  $max = 50;  // Stel dit in op het juiste aantal gebaseerd op de pagina

  // Een array maken om de resultaten in op te slaan
  $results = [];

  // Itereren over de div-elementen
  for ($i = 1; $i <= $max; $i++) {
      // De XPath expressies opbouwen
      $titleExpression = "/html/body/main/div[4]/div[1]/div[4]/div[2]/div[$i]/div[2]/div[1]/a/h3";
      $artistExpression = "/html/body/main/div[4]/div[1]/div[4]/div[2]/div[$i]/div[2]/div[1]/a/p";
      $allTimeHighExpression = "/html/body/main/div[4]/div[1]/div[4]/div[2]/div[$i]/div[2]/div[2]/div[2]/h5";

      // De XPath queries uitvoeren
      $titleNodes = $xpath->query($titleExpression);
      $artistNodes = $xpath->query($artistExpression);
      $allTimeHighNodes = $xpath->query($allTimeHighExpression);

      foreach ($titleNodes as $index => $titleNode) {
          // De titel van het nummer ophalen
          $title = $titleNode->nodeValue;
      
          // De song ID ophalen
          $songId = basename($titleNode->getAttribute('href'));
      
          // De API url opbouwen
          $apiUrl = "https://www.top40.nl/api/chart-position/$songId/1";
      
          // De API aanroepen
          $apiResponse = @file_get_contents($apiUrl);
      
          // Het API antwoord omzetten naar een array
          $apiData = json_decode($apiResponse, true);
      
          // De huidige positie ophalen
          $position = $apiData['currentPosition'] ?? null;

          // De all-time high ophalen
          $allTimeHigh = $allTimeHighNodes->item($index)->nodeValue ?? null;

          // De artiest ophalen
          $artist = $artistNodes->item($index)->nodeValue ?? null;
      
          // De titel, de artiest, de positie en de all-time high toevoegen aan de resultaten
          $results[] = ['title' => $title, 'artist' => $artist, 'position' => $position, 'allTimeHigh' => $allTimeHigh];
      }
  }

// De resultaten uitprinten in een Bootstrap tabel met een donker thema
if (!empty($results)) {
    echo '<div class="table-container">';
    echo '<div class="table-responsive">';
    echo '<table class="table table-dark table-hover">';
    echo '<thead><tr><th>Titel</th><th>Artiest</th><th>Hoogste Positie</th></tr></thead>';
    echo '<tbody>';
    foreach ($results as $result) {
        echo '<tr>';
        echo "<td>{$result['title']}</td>";
        echo "<td>{$result['artist']}</td>";
        echo "<td>{$result['allTimeHigh']}</td>";
        echo '</tr>';
    }
    echo '</tbody></table>';
    echo '</div>';
    echo '</div>';
  } else {
    echo '<div class="alert alert-danger">No results found for the entered artist.</div>';
  }
  
}
?>
