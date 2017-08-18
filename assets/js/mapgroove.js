
jQuery(document).ready(function($) {

 // $('#setbutton').click(function() {
 //    console.log($('.fieldAdd').val());
 //  });

  $('#setbutton').click(function() {
    var returnData = [[]];
    $("#fieldSets :input").each(function(){
     var input = $(this).val(); // This is the jquery object of the input, do what you will
       if (input.length > 0) {
         returnData.push(this.id);
         returnData.push(input);
       }
    });
   saveMap(returnData);
  });


  $('#button').click(function(e) {
    var pressed = $('#url').val();
    xml(pressed);
  });

  // TODO: Change hard links
  var saveMap = function(e) {
    var save_map = (e);
    console.log(save_map);
    var sendData = $.ajax({
               type: 'POST',
               url: '/wp/wp-content/plugins/mapgroove/assets/ajax/map_data.php',
               data: {save_map},
                 success: function(resultData) {
                     $('.datamapping').html(resultData);
                 }
             }); // end ajax call
  }

  var xml = function(e) {
     var xml_url = (e);
     var sendData = $.ajax({
               type: 'POST',
               url: '/wp/wp-content/plugins/mapgroove/assets/ajax/ajax.php',
               data: {xml_url},
                 success: function(resultData) {
                     $('.content_result').html(resultData);
                 }
             }); // end ajax call
  }



});
