 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" ></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" ></script>


<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
<link href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" rel="stylesheet"/>
<div class="container">
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Address</h3>
  </div>
  <div class="panel-body" id="address">
     <form action="geoLocationFinder.php" name="geolocateForm" id="goe-form">
   <input id="autocomplete" placeholder="Enter your address"
      type="text" class="form-control" name="geolocate">
      <br>
     
      <div class="row">

         <div class="col-md-12 ">
            <input class="btn btn-primary pull-right" id="save" type="button" value="search" style="margin-top: 20px">
         </div>
      </div>
 </form>

 <div id="result" style="display: none;margin-left: 20px">
      <div class="row">
        <label class="control-label">Latitude : </label>
        <label class="control-label result-lat"></label><br>
        <label class="control-label">Longitude : </label>
        <label class="control-label result-long"></label>
      </div>
    </div>
</div>

<div class="osm-map-div" style="margin-top: 15%;">
  <div id="osm-map"></div>
</div>
</div>
</div>  


<script>
    $(document).ready(function () {
       $('#result').hide();
      $("#save").click(function (e) {
         $("#goe-form").submit();

      });

      $('#goe-form').on('submit', function (e) {
        e.preventDefault();
        var method = 'post',
        fd = new FormData(),
        action = $(this).attr('action'),
        datas = $(this).serializeArray();
         $.each(datas, function (key, input) {
                fd.append(input.name, input.value);
            });
        $.ajax({
          url: action,
          type: method,
          data: fd,
          processData: false,
          contentType: false,
          dataType: "json",
          success: function(data) {
            if (data.status) {
              $('#result').show();
              $('.result-lat').html(data.latitude);
              $('.result-long').html(data.longitude);
              setMap(data.latitude,data.longitude);

            } else {
              $('#result').hide();
              $('.osm-map-div').html('<div id="osm-map"></div>');
              alert(data.message);
            }
          },
        });
      });
    });

    function setMap(lat,long){

      var element = document.getElementById('osm-map');

      element.style = 'height:300px;';

      var map = L.map(element);

      L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        }).addTo(map);

      var target = L.latLng(lat, long);

      map.setView(target, 14);

      L.marker(target).addTo(map);
    }


</script>