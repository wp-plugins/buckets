<?php
    require_once('../../../../wp-load.php'); //load Wordpress

    if (is_user_logged_in()):

        wp_enqueue_style('buckets_popup', plugins_url() . '/buckets/css/popup.css');
        wp_enqueue_script('buckets-popup', plugins_url() . '/buckets/js/popup.js', array('jquery'));

        acf_form_head();
?>
<!doctype html>
<html>
<head>
    <?php wp_head(); ?>
</head>
<body>
    <div id="bucket-popup" class="wp-core-ui">
<?php

    $post_id = (isset($_GET['bucket_id'])) ? intval($_GET['bucket_id']) : 'new_post';
    acf_form(array(
        'post_id'       => $post_id,
        'new_post'      => array(
            'post_type'     => 'buckets',
            'post_status'   => 'publish'
        ),
        'post_title'    => true,
        'submit_value'  => 'Save'
    ));

    acf_enqueue_uploader();

?>
    </div>
    <?php wp_footer(); ?>
</body>
</html>

<?php
    else:
        wp_redirect(get_bloginfo('url'), 301);
    endif;
?>
