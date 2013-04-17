<?php
//mimic the actuall admin-ajax
define('DOING_AJAX', true);

if (!isset( $_POST['action']))
    die('-1');

//make sure you update this line 
//to the relative location of the wp-load.php
require_once('./../../../../wp-load.php'); 

//Typical headers
header('Content-Type: text/html');
send_nosniff_header();

//Disable caching
header('Cache-Control: no-cache');
header('Pragma: no-cache');


$action = esc_attr(trim($_POST['action']));

//A bit of security
$allowed_actions = array(
    'lp_pause_lander',
    'lp_play_lander',
    'lp_clear_stats',
    'lp_st_clone',
    'lp_record_conversion',
    'lp_record_impression',
    'lp_store_lead'
);

if(in_array($action, $allowed_actions)){
    if(is_user_logged_in())
	{	
        do_action('lp_ajax_handler_'.$action);
	}
    else
	{
        do_action('lp_ajax_handler_nopriv_'.$action);
	}
}
else{
    die('-1');
} 