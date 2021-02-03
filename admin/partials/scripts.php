<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "wrapper" div and all content after.
 *
 * 
 * @since   1.0.0
 */

?>
</main><!--/.main-->
</div>
</div>

<footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        &copy; 2021 by App-Forge.net. All Rigts reserved.
    </div>
    
</footer>

    <script type='text/javascript' src="<?= plugin_dir_url( __FILE__ ).'/../../../../../../wp-includes/js/jquery/jquery.js'?>"></script>    
    <script type='text/javascript' src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type='text/javascript' src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script type='text/javascript'>

(function( $ ) {
        $('.navbar-toggler').click(function(){
            if($('#sidebarMenu').is(':visible'))
                $('#sidebarMenu').hide();
            else
                $('#sidebarMenu').show();
        });
    })( jQuery );

    </script>