
jQuery(document).ready(function($) {

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


  $('#url_button').click(function(e) {
    var pressed = $('#url').val();
    console.log(pressed);
    xml(pressed);
  });

  $('#api_button').click(function(e) {
    var pressed = $('#api').val();
    console.log(pressed);
    api(pressed);
  });


  // TODO: Change hard links
  var saveMap = function(e) {
    var save_map = (e);
    var sendData = $.ajax({
               type: 'POST',
               url: snVars.sn_path+'/mapgroove/assets/ajax/map_data.php',
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
               url: snVars.sn_path+'/mapgroove/assets/ajax/admin_ajax.php',
               data: {xml_url},
                 success: function(resultData) {
                     $('.content_result').html(resultData);
                 }
             }); // end ajax call
  }

  var api = function(e) {
     var api_key = (e);
     var sendData = $.ajax({
               type: 'POST',
               url: snVars.sn_path+'/mapgroove/assets/ajax/admin_ajax.php',
               data: {api_key},
                 success: function(resultData) {
                     $('.content_result').html(resultData);
                 }
             }); // end ajax call
  }

});
