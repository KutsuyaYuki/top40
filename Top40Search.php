<?php

class Top40Search
{
    /**
     * Search for artists matching the given name.
     *
     * @param string $artist The artist name to search for.
     * @return array An array of artists matching the search query.
     */
    public function searchArtist($artist)
    {
        $url = 'https://www.top40.nl/api/artist-search/' . urlencode($artist);
        $response = $this->makeRequest($url);

        if (!$response || !isset($response['data'])) {
            return null; // Return null instead of an empty array
        }

        $artists = [];
        foreach ($response['data'] as $artistData) {
            $artists[] = [
                'name' => $artistData['name'],
                'url' => $artistData['url'],
            ];
        }

        return $artists;
    }

    /**
     * Search for top 40 results for the given artist.
     *
     * @param string $artist The artist name to search for.
     * @return array An array of top 40 results for the artist.
     */
    public function searchTop40($artist)
    {
        $artistData = $this->searchArtist($artist);
        if ($artistData === null) { // Check for null instead of empty array
            return [];
        }

        $artistUrl = $artistData[0]['url'];
        $url = 'https://www.top40.nl/top40-artiesten/' . urlencode($artistUrl);
        $response = $this->makeRequest($url);

        if (!$response) {
            return [];
        }

        $results = [];
        $dom = new DOMDocument();
        $artistUrl = $artistData[0]['url'];

        $xpath = new DOMXPath($dom);
        $titleNodes = $xpath->query("//h3[@class='u-margin-bottom-10']/a");

        foreach ($titleNodes as $titleNode) {
            $title = $titleNode->nodeValue;
            $positionNode = $xpath->query("../../../div[@class='c-top40-list-position']");
            $position = $positionNode->item(0)->nodeValue;

            $results[] = [
                'title' => $title,
                'position' => $position,
            ];
        }

        return $results;
    }

    /**
     * Make an HTTP GET request to the given URL and retrieve the response.
     *
     * @param string $url The URL to make the request to.
     * @return array|null The decoded JSON response, or null on failure.
     */
    private function makeRequest($url)
    {
        $response = @file_get_contents($url);

        if ($response === false) {
            return null;
        }

        return json_decode($response, true);
    }
}
?>
