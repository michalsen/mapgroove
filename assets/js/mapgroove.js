jQuery(document).ready(function($) {

    // Submit API Key
    $('#api_button').click(function(e) {
        var pressed = $('#api').val();
        console.log(pressed);
        api(pressed);
    });


    // Get API Key
    var api = function(e) {
        var api_key = (e);
        var sendData = $.ajax({
            type: 'POST',
            url: '/wp-content/plugins/mapgroove/assets/ajax/admin_ajax.php',
            data: {api_key},
            success: function(resultData) {
                $('.content_result').html(resultData);
            }
        }); // end ajax call
    }

    // LOAD MAP
    var LeafIcon = L.Icon.extend({
        options: {
            iconSize:    [25, 25],
            iconAnchor:  [10, 0],
            popupAnchor: [0, 0]
        }
    });

    var greenIcon = new LeafIcon({
        iconUrl: 'http://maptest.lndo.site/wp-content/plugins/mapgroove/includes/icons/skull.png',
        // iconUrl: 'http://maptest.lndo.site/wp-admin/images/marker.png',
        // shadowUrl: 'http://maptest.lndo.site/wp-content/plugins/mapgroove/assets/css/images/marker-shadow.png'
    })

    var map = L.map('mapid').setView([39.8097343, -98.5556199], 4);

    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'A Straight North Map'
    })
        .addTo(map);
    // END LOAD MAP

    // MAP FILTER
    $('form :input').change(function() {
        var  part = $('#selectpart').val();
        var  city = $('#selectcity').val();
        var  type = $('#selecttype').val();

        console.log(part);
        console.log(city);
        console.log(type);

        if ( type.length > 1 ) {
          var part_select_box = document.getElementById("selectpart");
          part_select_box.selectedIndex = 0;
          var city_select_box = document.getElementById("selectcity");
          city_select_box.selectedIndex = 0;
        }


        // $("#selectpart").remove();

        jQuery.ajax({
            type: "POST",
            url: '/wp-content/plugins/mapgroove/listings.php',
            data: {part:part, city:city, type:type},
            success: function(data) {
                // console.log(data);
                var latlngs = [];
                var couple = [];
                $.each(JSON.parse(data), function (idx, obj) {
                    latlngs.push(
                        [obj['lat'], obj['lng']]
                    );
                });

                /**
                 *
                 * Distance Evaluation
                 */
                var locations = [],
                    latLongPair,
                    longestDistances,
                    savecompare = [],
                    savecomparisons = [];

                $.each(latlngs, function (idx, obj) {
                    locations.push(obj);
                });
                if (locations.length > 1) {
                    longestDistances = locations.map(function (outerLatLong) {
                        var comparisons = locations.map(function (innerLatLong) {
                            var first = outerLatLong.toString();
                            var second = innerLatLong.toString();
                            latLongPair = first + ',' + second;
                            checkPair = latLongPair.split(',');
                            var checkdistance = distanceto(checkPair[0], checkPair[1], checkPair[2], checkPair[3], latLongPair);
                            savecompare.push(checkdistance);
                            return checkdistance;
                        });
                        savecomparisons.push(comparisons[1][0]);
                        var check = Math.max.apply(null, savecomparisons);
                        return check;
                    });
                    var longestDistance = Math.max.apply(null, longestDistances);

                    var loop = savecompare.length;
                    for (var i = 0; i < loop; i++) {
                        if (savecompare[i][0] == longestDistance) {
                            checkPair = savecompare[i][1].split(',');
                            var point1 = new L.LatLng(checkPair[0], checkPair[1]);
                            var point2 = new L.LatLng(checkPair[2], checkPair[3]);
                            var bounds = new L.LatLngBounds(point1, point2);
                            break;
                        }
                    }
                }

                /**
                 *
                 * END Distance Evaluation
                 */


                $(".leaflet-marker-icon").remove(); $(".leaflet-popup").remove();
                   $.each(JSON.parse(data), function (idx, obj) {
                    var marker = L.marker([obj['lat'], obj['lng']], {icon: greenIcon})
                        .addTo(map)
                        .bindPopup(obj['name']);
                    });
                if (bounds) {
                    map.fitBounds(bounds, {padding: [50, 50]});
                }
                 else  {
                    var latLngs = [ locations[0] ];
                    var markerBounds = L.latLngBounds(latLngs);
                    map.fitBounds(markerBounds);
                }
                // map.dragging.disable();
                },
                error: function(){ },
                complete: function(){ }
            });

    }).triggerHandler("rightnow");
    // END MAP FILTER

});

function distanceto(lat1, lon1, lat2, lon2, latLongPair) {
    var radlat1 = Math.PI * lat1/180
    var radlat2 = Math.PI * lat2/180
    var theta = lon1-lon2
    var radtheta = Math.PI * theta/180
    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
    dist = Math.acos(dist)
    dist = dist * 180/Math.PI
    dist = dist * 60 * 1.1515
    return [dist, latLongPair];
}
