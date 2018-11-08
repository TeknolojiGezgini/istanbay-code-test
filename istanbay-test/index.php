<?php 
  $dbcon = mysqli_connect('localhost','test','mpRukc93A6RnMEGX','istanbay-test');
?>
<!DOCTYPE html>
<html>
  <head>
    <title>istanbay code test</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 50vh;
        width: 100vw;
      }
      #pac-input {
      width:400px;
      padding:10px 15px;
      margin:20px;
      }
    </style>
  </head>
  <body>
    <input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="map"></div>
    <script>
      var markers = [];
      function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 40.961398, lng: 29.113676199999986},
          zoom: 8
        });
        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Aramadan konum seçildiğinde konum seçimi
            placeMarker(place.geometry.location);

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
  google.maps.event.addListener(map, 'click', function(event) {
    placeMarker(event.latLng);
  });
  var cmarkers = [];
  function placeMarker(location) {
    //Önceden seçilmiş marker/işaretçileri siliyor/temizliyor.
    for(i=0; i<cmarkers.length; i++){
      cmarkers[i].setMap(null);
    }
    //google maps api değişik bir metodla obje oluşturduğu için direk değeri alamadığım için bu fonksiyonu kullandım lat ve long geo lokasyon bilgisi.
    var picked_location = JSON.stringify(location);
    //yazıya çevirdiğim lokasyon bilgisini parçaladım.
    var matches = picked_location.match(/{"lat":(.*),"lng":(.*)}/i);
    //seçilen alana işaret/marker atıyor.
    var marker = new google.maps.Marker({
      position: location,
      map: map
    });
  //Markerları sonradan haritadan silebilmek için bir array'ın içine girmek zorundayım.
  cmarkers.push(marker);
  //Ajax - ajax_load_agencies.php
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      //ajax_load_agencies.php dosyasından ajax ile aldığım bilgi JSON formatında yazıyı JSON.parse ile parçalıyorum ve obje oluşturuyorum.
      var agencies = JSON.parse(this.responseText);
      // 5km mesafedeki ajanslar listesini konsola giriyorum.
      console.log(agencies);
      //Objedeki ajans sayısı kadar
      for(var i=0;i<agencies.agencies.length;i++){
        //haritaya işaretleyici/markerları yerleştiriyorum her ajans için.
        var marker = new google.maps.Marker({
          position: new google.maps.LatLng(agencies.agencies[i].lat,agencies.agencies[i].lng),
          label: agencies.agencies[i].name,
          map: map
        });
        //Markerları sonradan haritadan silebilmek için bir array'ın içine girmek zorundayım.
        cmarkers.push(marker);
      }
    }
  };
  xhttp.open("GET", "ajax_load_agencies.php?lat="+matches[1]+"&lng="+matches[2], true);
  xhttp.send();
  //aranan/seçilen konuma haritayı merkezle
  map.panTo(location);
}
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgsKsXvP0O-faYIPBUs_QrIrlnp4pSn6Q&libraries=places&callback=initAutocomplete"
    async defer></script>
  </body>
</html>