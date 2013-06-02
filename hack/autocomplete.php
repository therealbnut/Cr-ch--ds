<?php
    
    
    
?>
<html>
    <head>
    <script type="text/javascript" src="scripts/jquery_1_10_1.js" ></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script>
  $(function() {
 
    $( "#location" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: "http://115.146.86.126/hack/services.php",
          type: "POST",
          dataType: "json",
          data: { method : "LocationAutocomplete", location : request.term},
          success: function( data ) {
            response( $.map( data, function( item ) {
              return {
                label: item.abs_location_string,
                value: item.abs_id
              }
            }));
          }
        });
      },
      minLength: 2,
      select: function( event, ui ) {
        var locID = ui.item.value;
         $.ajax({
          url: "http://115.146.86.126/hack/services.php",
          type: "POST",
          dataType: "json",
          data: { method : "GetDataForSpecifiedABSLocID", ABSLocID : locID},
          success: function( data ) {
            var d = data;
          }
        });
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });
  });
  </script>
    </head>
    <body>
    <center>
        <div style='width:200px;' >
            Enter your location: <input type="text" id="location" name="location"  />
        </div>
    </center>
    </body>
</html>