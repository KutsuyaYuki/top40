# Top40Search

Top40Search is a PHP library that allows you to search for artists and retrieve their top 40 results from the Top40.nl website.

## Installation

To use the Top40Search library, follow these steps:

1. Download the `Top40Search.php` file and include it in your PHP project.
2. Require the `Top40Search.php` file in your PHP script: `require_once 'Top40Search.php';`
3. Create an instance of the `Top40Search` class: `$top40Search = new Top40Search();`

## Usage

### Search for Artists

To search for artists matching a given name, use the `searchArtist($artist)` method. It returns an array of artists matching the search query.

```php
$artist = 'Coldplay';
$artists = $top40Search->searchArtist($artist);
```

The `$artists` array will contain the matching artists with their names and corresponding URLs.

### Search for Top 40 Results

To search for the top 40 results of a specific artist, use the `searchTop40($artist)` method. It returns an array of top 40 results for the artist.

```php
$artist = 'Ed Sheeran';
$results = $top40Search->searchTop40($artist);
```

The `$results` array will contain the top 40 results for the artist, including the song titles and positions on the chart.

## Requirements

- PHP 5.6 or above
- DOM extension enabled

## License

This project is licensed under the MIT License.