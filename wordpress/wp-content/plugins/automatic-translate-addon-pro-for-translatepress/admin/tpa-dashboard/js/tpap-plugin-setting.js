jQuery(document).ready(function($) {
    $('tr.active[data-plugin*="automatic-translate-addon-for-translatepress-pro"]').each(function() {
      var $currentRow = $(this);
      var $nextUpdateRow = $currentRow.nextAll('tr.plugin-update-tr.active.tpap-pro').first();
  
      if ($nextUpdateRow.length > 0) {
        $currentRow.addClass('update');
      }
    });
  });