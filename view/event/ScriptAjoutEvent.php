<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuog5LlTmtUH8-wB5IjxdJMY_Cq-CqhVU&language=fr&callback=initMap">
</script>

<script>
    var marker;

    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 2,
            center: {lat: 0, lng: 0 }
        });

        map.addListener('click', function(e) {
            placeMarkerAndPanTo(e.latLng, map);
        });

        if(document.getElementById("update")) { //Place un marqueur aux coordonnées de l'event à update
            var latitude = document.getElementById("latitude_id").value;
            var longitude = document.getElementById("longitude_id").value;

            var LatLng = new google.maps.LatLng(latitude, longitude)

            marker = new google.maps.Marker({
                position: LatLng,
                map: map
            });
            map.panTo(LatLng);
        }
    }

    function placeMarkerAndPanTo(latLng, map) {
        if(typeof marker !== 'undefined'){  //Détruit le précédent marqueur si on en avait placé un
            marker.setMap(null);
        }
        marker = new google.maps.Marker({
            position: latLng,
            map: map
        });
        document.getElementById("latitude_id").value = marker.getPosition().lat();
        document.getElementById("longitude_id").value = marker.getPosition().lng();
        map.panTo(latLng);
    }
</script>
