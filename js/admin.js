(function($){
  $(function() {
    var jstab = $('#rollbar-logging-js'),
      tabs = $('.rollbar-logging-tab-panel'),
      tabnav = $('.rollbar-logging-tabs');
    
    tabs.not(jstab).addClass('hide');
    
    tabnav.on('click', 'a', function(evt) {
      var href = $(this).attr('href');
      
      tabs.addClass('hide');
      $(href).removeClass('hide');
      
      evt.preventDefault();
    });
  });
  
})(jQuery);
