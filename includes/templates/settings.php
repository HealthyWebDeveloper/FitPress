<div class="wrap">
    <form method="post" action="options.php"> 
        <?php @settings_fields('fitpress_settings-group'); ?>
        <?php @do_settings_fields('fitpress_settings-group'); ?>
    <?php do_settings_sections('fitpress_settings'); ?>
        <?php @submit_button(); ?>
    </form>
</div>