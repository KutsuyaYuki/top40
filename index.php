<!DOCTYPE html>
<html>
<head>
  <title>Top 40 test</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
  <div class="container">
    <div class="search-form">
      <form id="searchForm" action="" method="get">
        <div class="input-group">
          <input type="text" class="form-control" id="artistInput" placeholder="Naam band" autofocus autocomplete="off">
          <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">Zoeken</button>
          </span>
        </div>
      </form>
    </div>
    <div id="resultsTable"></div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
    $(document).ready(function() {
      var urlParams = new URLSearchParams(window.location.search);
      var artistQuery = urlParams.get('artist');

      if (artistQuery) {
        $('#artistInput').val(artistQuery);
        searchArtist(artistQuery);
      }

      $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        var artist = $('#artistInput').data('artistUrl');
        var artistName = artist.substring(artist.lastIndexOf('/') + 1);
        searchArtist(artistName);
        history.pushState(null, null, '?artist=' + encodeURIComponent(artistName));
      });

      $('#artistInput').autocomplete({
        source: function(request, response) {
          var artist = request.term;

          if (!artist) {
            response([]);
            return;
          }

          var url = 'autocomplete.php?term=' + encodeURIComponent(artist);
          $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
              var suggestions = data.map(function(artist) {
                return { label: artist.name, value: artist.url };
              });
              response(suggestions);
            },
            error: function() {
              response([]);
            }
          });
        },
        select: function(event, ui) {
          event.preventDefault();
          $('#artistInput').val(ui.item.label).data('artistUrl', ui.item.value);
        }
      });

      function searchArtist(artist) {
        $('#resultsTable').html('<div class="text-center"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>');

        $.ajax({
          url: 'search.php',
          type: 'GET',
          data: { artist: artist },
          dataType: 'html',
          success: function(data) {
            $('#resultsTable').html(data);
          },
          error: function() {
            $('#resultsTable').html('<div class="alert alert-danger">An error occurred. Please try again later.</div>');
          }
        });
      }
    });
  </script>
</body>
</html>
