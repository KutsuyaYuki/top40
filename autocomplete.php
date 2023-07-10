<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['term'])) {
    $term = $_GET['term'];
  
    // Check if the term is empty
    if (empty($term)) {
      // Return an empty array as suggestions
      echo json_encode([]);
      exit;
    }
  
    // Set the request URL
    $url = "https://www.top40.nl/api/artist-search/" . urlencode($term);
  
    // Set the request headers
    $headers = [
      'Host: www.top40.nl',
      'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.5249.62 Safari/537.36',
      'Accept: application/json, text/plain, */*',
      'Referer: https://www.top40.nl/zoeken?searchTerm=' . urlencode($term),
      'Sec-Fetch-Site: same-origin',
      'Sec-Fetch-Mode: cors',
      'Sec-Fetch-Dest: empty',
      // Add any other necessary headers from the original request here
    ];
  
    // Create the stream context with the headers
    $context = stream_context_create([
      'http' => [
        'method' => 'GET',
        'header' => implode("\r\n", $headers)
      ]
    ]);
  
    // Make a GET request to the API endpoint with the provided headers
    $response = file_get_contents($url, false, $context);
  
    // Return the response
   echo $response;
}
?>
