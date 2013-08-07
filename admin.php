

<?php
add_action('admin_menu', 'dg_automatic_nbsp_menu');

function dg_automatic_nbsp_menu() {
    add_options_page('Automatic NBSP options', 'Automatic NBSP', 'manage_options', 'dg_automatic_nbsp_id', 'dg_automatic_nbsp_options');
}

function dg_automatic_nbsp_options() {

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $conjunctions = 'dg_automatic_nbsp';
    $hidden_field_name = 'dg_submit_hidden';
    $data_field_name = 'dg_conjunction';

    // Read in existing option value from database
    $opt_val = get_option($conjunctions);

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if (isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y') {
        // Read their posted value
        $opt_val = $_POST[$data_field_name];

        // Save the posted value in the database
        update_option($conjunctions, $opt_val);
        ?>


        <?php
    }

    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __('Automatic NBSP settings') . "</h2>";

    // settings form
    ?>

    <form name="form1" method="post" action="">
        <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

        <p><?php _e("List of words that you want to move to the next line. Separate by comma."); ?> 
            <input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="80">
        </p><hr />

        <p class="submit">
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
        </p>

    </form>
    </div>

    <?php
}

