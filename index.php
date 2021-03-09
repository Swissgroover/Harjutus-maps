<?php
    $config = require_once 'config.php';
    include_once 'db.php';
    include_once 'locations_model.php';

    function connection ($config) {
        $dsn = "mysql:host=" . $config['host'] . ";dbname=" . $config['database'];

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
             return new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (\PDOException $e) {
             throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
<?
    
    
<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
      src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"
      src="https://maps.googleapis.com/maps/api/js?key=&callback=initMap&libraries=&v=weekly"
      defer
    ></script>
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
    </style>
    <script>
      let map;

      let markers = [<?php 
            foreach ($markers as $index => $row) {
              if ($index === array_key_last($markers)){
                echo "[" . $row->id . "," . $row->latitude . "," . $row->longitude . ",'" . $row->name . "','" . $row->description . "']";
              } else {
                echo "[" . $row->id . "," . $row->latitude . "," . $row->longitude . ",'" . $row->name . "','" . $row->description . "'],";
              }
            }?>];

      function initMap() {

        

        map = new google.maps.Map(document.getElementById("map"), {
          center: { lat: 58, lng: 22 },
          zoom: 8,
        });

        markers.forEach(item => {
          let marker = new google.maps.Marker({
            position: {lat: item[1], lng: item[2]},
            animation: google.maps.Animation.DROP,
            draggable: true,
            map:map,
          })

          let infowindow = new google.maps.InfoWindow({
            content: 
              "<form method='post'>\
                  <div>Latitude: " + item[1] + "</div>\
                  <div>Longitude: " + item[2] + "</div>\
                  <input type='hidden' name='latitude' value=" + item[1] + ">\
                  <input type='hidden' name='longitude' value=" + item[2] + ">\
                  <input type='hidden' name='id' value=" + item[0] + ">\
                  <div>\
                    <label for='name'>Name</label>\
                    <input type=text name='name' value='" + item[3] + "'>\
                  </div>\
                  <div>\
                    <label for='description'>Description</label>\
                    <input type=text name='description' value='" + item[4] +"'>\
                  <div>\
                  <button type='submit' name='edit'>Edit</button>\
                  <button type='submit' name='delete'>Delete</button>\
                </form>\
              "
          });
          marker.addListener("click", () => {
            infowindow.open(map, marker);
          });

          marker.addListener("dragend", function(event) {
            infowindow.setContent(  
                "<form method='post'>\
                    <div>Latitude: " + event.latLng.lat().toFixed(4)  + "</div>\
                    <div>Longitude: " + event.latLng.lng().toFixed(4) + "</div>\
                    <input type='hidden' name='latitude' value=" + event.latLng.lat() + ">\
                    <input type='hidden' name='longitude' value=" + event.latLng.lng() + ">\
                    <input type='hidden' name='id' value=" + item[0] + ">\
                    <div>\
                      <label for='name'>Name</label>\
                      <input type=text name='name' value='" + item[3] + "'>\
                    </div>\
                    <div>\
                      <label for='description'>Description</label>\
                      <input type=text name='description' value='" + item[4] +"'>\
                    <div>\
                    <button type='submit' name='edit'>Edit</button>\
                    <button type='submit' name='delete'>Delete</button>\
                  </form>\
                ");
            
          });
        });

        google.maps.event.addListener(map, "click", function(event) {
          addMarker(event.latLng);
        });

        function addMarker(data) {

          let marker = new google.maps.Marker({
            position: data,
            animation: google.maps.Animation.DROP,
            map:map,
          });

          let infowindow = new google.maps.InfoWindow({
            content: 
              "<form method='post'>\
                  <div>Latitude: " + data.lat().toFixed(4)  + "</div>\
                  <div>Longitude: " + data.lng().toFixed(4)  + "</div>\
                  <input type='hidden' name='latitude' value=" + data.lat() +">\
                  <input type='hidden' name='longitude' value=" + data.lng() +">\
                  <div>\
                    <label for='name'>Name</label>\
                    <input type=text name='name'>\
                  </div>\
                  <div>\
                    <label for='description'>Description</label>\
                    <input type=text name='description'>\
                  <div>\
                  <button type='submit' name='action'>Save</button>\
                </form>\
              "
          });
         
          infowindow.open(map, marker);

          infowindow.addListener("closeclick", () => {
            marker.setMap(null);
          });

          map.addListener("click", () => {
            marker.setMap(null);
          })

        };
      }
      var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: `${m4.png}/m`});
    </script>
  </head>
  <body>
    <div id="map"></div>
  </body>
</html>
