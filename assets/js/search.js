jQuery(document).ready(function($) {

  $("#dataTable").tablesorter();
  // $("#dataTable").tablesorter({
  //     headers: {
  //         1: {
  //             sorter: false
  //         }
  //     }
  // });

  var map = L.map('mapid').setView([39.82, -98.58], 4);
  var token = 'pk.eyJ1IjoibWljaGFsc2VuIiwiYSI6ImNqNW1pbXBtdDJvdG0yd2pqbjgyb2lyaGwifQ.j6eFIwG1x2DxwhyLrTLyjg';
  L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/outdoors-v9/tiles/{z}/{x}/{y}?access_token='+token, {
      attribution: 'Map data by <a href="https://www.straightnorth.com">Straight North</a> w/Mapbox',
      maxZoom: 18,
      id: 'examples.map-i875mjb7',
      accessToken: 'your.mapbox.access.token',
      zoomControl: false,
  }).addTo(map);

  var pinsFilter = new L.FeatureGroup();
  var allpins = new L.FeatureGroup();

  map.dragging.disable();
  map.scrollWheelZoom.disable();

  var data = $(".hidden_markers").html();
  var markers = $.parseJSON(data);


  for (var i = 0; i < markers.length; i++) {
    if (markers[i]!=null) {
      var data = markers[i].split(',');
      var marker = L.marker([data[0],data[1]]);
      allpins.addLayer(marker);
    }
  }

  map.addLayer(allpins);

  $('input').keydown(function() {
   map.removeLayer(allpins);
   pinsFilter.clearLayers();
  });

  // ROW DETAILS - CLICK
  $('.clickrow').click(function(event) {
    var Id = jQuery(this).attr("id");
    var row = Id.split('_');
    var trueRow = row[1] - 2;
    window.location.replace('http://wp.dev/'+window.location.pathname+'?row='+trueRow);
  });


  // TABLE
  var row_count = document.getElementById('dataTable');
  var l = row_count.rows.length;

  // KEY UP
  $('.filter_search').keyup(function(e) {
    var formData = $('form').serialize();
    var Data = formData.split("&");
    var json_text = JSON.stringify(Data, null, 2);
    var your_object = JSON.parse(json_text);
    var filters = [];
    for (var key in your_object) {
     var eachColumn = your_object[key].split("=");
       if (eachColumn[0] == 'Job+Title') {
          filters.push(eachColumn[1]);
       }
       if (eachColumn[0] == 'City') {
          filters.push(eachColumn[1]);
       }
       if (eachColumn[0] == 'State') {
          filters.push(eachColumn[1]);
       }
       if (eachColumn[0] == 'Start+Date') {
          filters.push(eachColumn[1]);
       }
       if (eachColumn[0] == 'Pay+Rate') {
          filters.push(eachColumn[1]);
       }
    }

    var id = $(this).attr('id');
    var val = $(this).val();

    var cols = [0, 1, 2, 3, 4];

    // COLUMN VARIABLE
    if (id == 'Job Title') {
      var column = 0;
    }
    if (id == 'City') {
      var column = 1;
    }
    if (id == 'State') {
      var column = 2;
    }
    if (id == 'Start Date') {
      var column = 3;
    }
    if (id == 'Pay Rate') {
      var column = 4;
    }

        for ( var i = 0; i < l; i++ ) {
          var tr = row_count.rows[i];
          if (tr != null) {

            var resultArray = [];
            for (f in filters) {
              var cll = tr.cells[f];
              var content = $(cll).html();
              var clean = content.toLowerCase();

              // Index of filter[f]
              //    - all lowercase
              //    - space "+" replaced with " "
              check = clean.indexOf(filters[f].toLowerCase().split('+').join(' '));

              resultArray.push(check);
            }

            if (resultArray[0] < 0 ||
                resultArray[1] < 0 ||
                resultArray[2] < 0 ||
                resultArray[3] < 0 ||
                resultArray[4] < 0) {
                  if (i > 1) {
                    hideRow('row_'+i);
                  }
            }
             else {
                  showRow('row_'+i);
             }
          }
        }



  });

function showRow(rowId) {
  if (document.getElementById(rowId) != null) {
      document.getElementById(rowId).style.display = "table-row";
    if (document.getElementById(rowId).dataset.points) {
      var data = document.getElementById(rowId).dataset.points.split(',');
      var setmarker = L.marker([data[0],data[1]]);
      pinsFilter.addLayer(setmarker);
      map.addLayer(pinsFilter);
    }
  }
}

function hideRow(rowId) {
  if (document.getElementById(rowId) != null) {
      document.getElementById(rowId).style.display = "none";
  }
}





});
