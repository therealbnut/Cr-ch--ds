$(function() {
  $( "#location" ).autocomplete({
    source: function( request, response ) {
      $.ajax({
        url: "./services.php",
        type: "POST",
        dataType: "json",
        data: { method : "LocationAutocomplete", location : request.term},
        success: function( data ) {
          response( $.map( data, function( item ) {
			$('#location_id').val(item.abs_id);
            return {
              label: item.abs_location_string,
              value: item.abs_location_string,
              abs_id: item.abs_id
            }
          }));
        }
      });
    },
    minLength: 2,
    select: function( event, ui ) {
      var locID = ui.item.abs_id;//ui.item.value;
       $.ajax({
        url: "./services.php",
        type: "POST",
        dataType: "json",
        data: { method : "GetDataForSpecifiedABSLocID", ABSLocID : locID},
        success: function( data ) {
            renderCharts(data);
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

