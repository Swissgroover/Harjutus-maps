<!DOCTYPE html>
<html>
  <head>
    <title>Maps harjutus</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <style type="text/css">
      #map {
        height: 100%;
      }

      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }

      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: "Roboto", "sans-serif";
        line-height: 30px;
        padding-left: 10px;
      }
    </style>
    <script>
      let map;
      let markers = [];

      function initMap() {
        const haightAshbury = { lat: 59.436962, lng: 24.753574 };
        map = new google.maps.Map(document.getElementById("map"), {
          zoom: 12,
          center: haightAshbury,
          mapTypeId: "terrain",
        });
        map.addListener("click", (event) => {
          addMarker(event.latLng);
        });
        addMarker(haightAshbury);
      }

      function addMarker(location) {
        const marker = new google.maps.Marker({
          position: location,
          map: map,
        });
        markers.push(marker);
      }

      // Sets the map on all markers
      function setMapOnAll(map) {
        for (let i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }

      // Removes markers , but keeps in array.
      function clearMarkers() {
        setMapOnAll(null);
      }

      // Shows markers
      function showMarkers() {
        setMapOnAll(map);
      }

      // Deletes markers
      function deleteMarkers() {
        clearMarkers();
        markers = [];
      }
    </script>
  </head>
  <body>
    <div id="floating-panel">
      <input onclick="clearMarkers();" type="button" value="Hide Markers" />
      <input onclick="showMarkers();" type="button" value="Show All Markers" />
      <input onclick="deleteMarkers();" type="button" value="Delete Markers" />
    </div>
    <div id="map"></div>
    <p>Click on the map to add markers.</p>

    <script
      src="https://maps.googleapis.com/maps/api/js?key=&callback=initMap&libraries=&v=weekly"
      async
    ></script>
  </body>
</html>