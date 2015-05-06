(function ($, Drupal, window, document, undefined) {
  $(document).ready(function () {

    var alterAttach = function() {
      // If the admin menu is present, add our toggler link to it
      var $adminMenuWrapper = $('#admin-menu-wrapper > ul');
      if($adminMenuWrapper.length) {
        // Add a menu item in with javascript
        $adminMenuWrapper.last().append('<li class="admin-menu-action context-list-active-toggle"><a href="">Show active contexts</a></li>');
      }

      // Cause the context browser to appear when the toggler is clicked
      $('.context-list-active-toggle').click(function(e){
        $('#context_list_active-overlay').toggle();

        e.preventDefault();
        return false;
      });
    }

    // It seems to be difficult to get these adjustments to run after admin menu - using this hacky timeout for now
    setTimeout(alterAttach, 750);
  }); //END - document.ready
})(jQuery, Drupal, this, this.document); //END - Closure