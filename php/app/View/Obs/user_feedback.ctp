<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<div style="text-align:	center" id="mapholder"></div>
<script>

    $(document).ready(getLocation);
    
    var x=document.getElementById("demo");
    function getLocation()
    {
        if (navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition(showPosition,showError);
        }
        else{x.innerHTML="Geolocation is not supported by this browser.";}
    }

    function showPosition(position)
    {
        var latlon=position.coords.latitude+","+position.coords.longitude;
        var input=document.getElementById("geocode")
        input.value=latlon

        var img_url="http://maps.googleapis.com/maps/api/staticmap?center="
            +latlon+"&zoom=14&size=400x300&markers=color:blue%7Clabel:S%7C"+latlon+"&sensor=false";
        document.getElementById("mapholder").innerHTML="<img src='"+img_url+"'>";
    }

    function showError(error)
    {
        switch(error.code)
        {
            case error.PERMISSION_DENIED:
                x.innerHTML="User denied the request for Geolocation."
                break;
            case error.POSITION_UNAVAILABLE:
                x.innerHTML="Location information is unavailable."
                break;
            case error.TIMEOUT:
                x.innerHTML="The request to get user location timed out."
                break;
            case error.UNKNOWN_ERROR:
                x.innerHTML="An unknown error occurred."
                break;
        }
    }

    // Temp Slider
    $(document).ready(
        function() {
            $( "#slider" ).slider({
                value:25,
                min: -10,
                max: 40,
                step: 1,
                slide: function( event, ui ) {
                    $( "#guessTemp" ).val( ui.value +"ºC" );
                }
            });
            $( "#guessTemp" ).val( $( "#slider" ).slider( "value" ) +"ºC" );
        }
    );

</script>
<div style="text-align:	center">

    <form action="../updateOb" method="post">
        How do you feel? </br>
        <label for="sad">&#9785;</label>
        <input type="radio" name="mood" value="-1"/><br/>

        <label for="natural">&#x1f610;</label>
        <input type="radio" name="mood" value="0" /><br/>

        <label for="happy">&#9786</label>
        <input type="radio" name="mood" value="1" /><br/>

        <p >
            <label for="guessTemp">Guess the Temperature:</label>
            <input type="text" id="guessTemp" style="border: 0; color: #f6931f; font-weight: bold;" />
        </p>

        <div id="slider"style="" align="center"></div>

        <input type="hidden" name="ob_id" value="<?php echo $ob_id;?>"/>
        <input type="hidden" id="geocode" name="geocode"/>
        </br>
        <input type="submit" value="Submit">
    </form>

</div>
