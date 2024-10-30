<?php

if(!function_exists("WPTV__get_abs_path")) {
function WPTV__get_abs_path()
	{
		return WP_PLUGIN_DIR . '/' . basename(dirname(__FILE__)) . '/';
	}
}
	
//-----------------------------------------------------

if(!function_exists("WPTV__get_url_path")) {
	function WPTV__get_url_path()
	{
		return WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) . '/';
	}
}

//----------------------------------------------------

if(!function_exists("get_current_parameters")) {
	function get_current_parameters($remove_parameter="")
	{	
		
		if($_SERVER['QUERY_STRING']!='')
		{
			$qry = '?' . $_SERVER['QUERY_STRING']; 
			if($remove_parameter!='')
			{
				$string_remove = '&' . $remove_parameter . "=" . $_GET[$remove_parameter];
				$qry=str_replace($string_remove,"",$qry);
				$string_remove = '?' . $remove_parameter . "=" . $_GET[$remove_parameter];
				$qry=str_replace($string_remove,"",$qry);
			}
			
			return $qry;
		}else
		{
			return "";
		}
	}
} 

//----------------------------------------------------

if(!function_exists("get_visitor_IP")) {
function get_visitor_IP()
{
	$ipaddress = $_SERVER['REMOTE_ADDR'];
	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	
	return $ipaddress ;
}}

//----------------------------------------------------

if(!function_exists("get_visitor_OS")) {
function get_visitor_OS()
{

$userAgent= $_SERVER['HTTP_USER_AGENT'];
		$oses = array (
		'iPhone' => '(iPhone)',
		'Windows 3.11' => 'Win16',
		'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)', 
		'Windows 98' => '(Windows 98)|(Win98)',
		'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
		'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
		'Windows 2003' => '(Windows NT 5.2)',
		'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
		'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
		'Windows 8' => '(Windows NT 6.2)|(Windows 8)',
		'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
		'Windows ME' => 'Windows ME',
		'Open BSD'=>'OpenBSD',
		'Sun OS'=>'SunOS',
		'Linux'=>'(Linux)|(X11)',
		'Safari' => '(Safari)',
		'Macintosh'=>'(Mac_PowerPC)|(Macintosh)',
		'QNX'=>'QNX',
		'BeOS'=>'BeOS',
		'OS/2'=>'OS/2',
		'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
	);

	foreach($oses as $os=>$pattern){ 

		if(eregi($pattern, $userAgent)) { 
			return $os; 
		}
	}
	return 'Unknown';
}}

//-----------------------------------------------------------------

if(!function_exists("get_visitor_Browser")) {
function get_visitor_Browser()
{

$userAgent= $_SERVER['HTTP_USER_AGENT'];
		$browsers = array(
		'Opera' => 'Opera',
		'Firefox'=> '(Firebird)|(Firefox)', 
		'Galeon' => 'Galeon',
		'Chrome'=>'Chrome',
		'MyIE'=>'MyIE',
		'Lynx' => 'Lynx',
		'Netscape' => '(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)',
		'Konqueror'=>'Konqueror',
		'SearchBot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)',
		'Internet Explorer 8' => '(MSIE 8\.[0-9]+)',
		'Internet Explorer 9' => '(MSIE 9\.[0-9]+)',
        'Internet Explorer 7' => '(MSIE 7\.[0-9]+)',
		'Internet Explorer 6' => '(MSIE 6\.[0-9]+)',
		'Internet Explorer 5' => '(MSIE 5\.[0-9]+)',
		'Internet Explorer 4' => '(MSIE 4\.[0-9]+)',
	);

	foreach($browsers as $browser=>$pattern) { 

		if(eregi($pattern, $userAgent)) {
			return $browser; 
		}
	}
	return 'Unknown'; 

}}


//-----------------------------------------------------------------

if(!function_exists("get_user_role")) {
function get_user_role($user_id)
{

	$user = new WP_User( $user_id );
		if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
			foreach ( $user->roles as $role )
				return $role;
		}
		
 return "Unkown";
}}

//----------------------------------------------------

if(!function_exists("WPTV__add_login_row")) {
function WPTV__add_login_row($username,$password,$status)
{
	global $wpdb,$table_prefix;
	
	$table_name = $table_prefix . 'better_login_security_history';
	$IP=get_visitor_IP();
	$Country=clogica_visitor_country();
	$OS=get_visitor_OS();
	$Browser=get_visitor_Browser();
	$Sinon_time = date('m/d/Y h:i:s a', time());
	$Name = "Not a User";
	$Usertype = "---";
	
	$user = get_userdatabylogin($username);	
	if ($user)
	{			
		$Name =  $user->user_firstname . ' ' . $user->user_lastname;
		$Usertype = get_user_role($user->ID) ;	
	}
	$options= WPTV__my_options();
	$history_show_password = intval($options['history_show_password']);
	if($Usertype=='administrator' || $history_show_password==0){
		$password='---';
	}
	$sql=" insert into $table_name(Username,Status,Name,Usertype,Sinon_time,long_time,IP,Country,OS,Browser,Password) values('$username',$status,'$Name','$Usertype',NOW()," . time() . ",'$IP','$Country','$OS','$Browser','$password'); ";
	$wpdb->query($sql);
}} 


//----------------------------------------------------

if(!function_exists("WPTV__get_last_valid_login")) {
function WPTV__get_last_valid_login($ip)
{	global $wpdb,$table_prefix;
	$table_name = $table_prefix . 'better_login_security_history';
	$sql=" select ID from $table_name where IP='$ip' and Status=1 order by ID desc limit 1  ";
	$results = $wpdb->get_results($sql);
	$result=$results[0];
	$ID=$result->ID;
	return $ID;
}} 

//---------------------------------------------------- 

if(!function_exists("WPTV__init_options")) {
function WPTV__init_options()
{	
	add_option(WPTV__OPTIONS);
	
	$options['login_block_time']=10;
	$options['can_block_login_trials']=true;
	$options['login_max_trials']=5;
	$options['can_show_captcha_option']=true;
	$options['show_captcha_count_option']=1;
	$options['login_blocked_msg']="Our records show that you have many failed login trials in a short time period, try to login again later after 10 minutes!";
	
	update_option(WPTV__OPTIONS,$options);
}} 

//---------------------------------------------------- 

if(!function_exists("WPTV__update_my_options")) {
function WPTV__update_my_options($options)
{	
	
	update_option(WPTV__OPTIONS,$options);
}} 

//---------------------------------------------------- 

if(!function_exists("WPTV__my_options")) {
function WPTV__my_options()
{	
	$options=get_option(WPTV__OPTIONS);
	if(!$options)
	{
		WPTV__init_options();
		$options=get_option(WPTV__OPTIONS);
	}
	return $options;			
}}

//---------------------------------------------------- 

if(!function_exists("WPTV__option_msg")) {
function WPTV__option_msg($msg)
{	
	echo '<div id="message" class="updated"><p>' . $msg . '</p></div>';		
}}


//----------------------------------------------------

if(!function_exists("WPTV__get_login_block_time")) {
function WPTV__get_login_block_time()
{	
	$options= WPTV__my_options();
	return $options['login_block_time'] * 60;
}} 

//----------------------------------------------------  

if(!function_exists("WPTV__get_login_max_trials")) {
function WPTV__get_login_max_trials()
{	
	$options= WPTV__my_options();
	$trials=$options['login_max_trials']; 
	return $trials ; 
}} 


//--------------------------------------------------- 

if(!function_exists("WPTV__can_block_login_trials")) {
function WPTV__can_block_login_trials()
{	
	$options= WPTV__my_options();
	$block_login_trials_option=$options['can_block_login_trials']; 
	return $block_login_trials_option;

}} 


//--------------------------------------------------- 

if(!function_exists("WPTV__show_captcha_option")) {
function WPTV__show_captcha_option()
{	
	$options= WPTV__my_options();
	$show_captcha_count_option=$options['show_captcha_count_option'];
	return $show_captcha_count_option;
}} 

//--------------------------------------------------- 

if(!function_exists("WPTV__can_show_captcha_option")) {
function WPTV__can_show_captcha_option()
{	
	$options= WPTV__my_options();
	$can_show_captcha_option=$options['can_show_captcha_option'];
	return $can_show_captcha_option;
}} 

//--------------------------------------------------- 

if(!function_exists("WPTV__login_blocked_msg")) {
function WPTV__login_blocked_msg()
{	
	$options= WPTV__my_options();
	$login_blocked_msg_option=$options['login_blocked_msg'];
	return $login_blocked_msg_option;

}}


//----------------------------------------------------

if(!function_exists("WPTV__increment_show_captcha_option")) {
function WPTV__increment_show_captcha_option()
{	
	if(!isset($_SESSION['cpatcha_trials']))
	$_SESSION['cpatcha_trials']=1;
	
	$_SESSION['cpatcha_trials']=$_SESSION['cpatcha_trials']+1;
}}

//----------------------------------------------------

if(!function_exists("WPTV__is_blocked")) {
function WPTV__is_blocked()
{	

	if(WPTV__can_block_login_trials())
	{

		$ip=get_visitor_IP();
		$last_valid_ID = WPTV__get_last_valid_login($ip);
		if($last_valid_ID=="")
		$last_valid_ID=0;
		
		global $wpdb,$table_prefix;
		$table_name = $table_prefix . 'better_login_security_history';
		$block_period_start = time() - WPTV__get_login_block_time();	
		$sql=" select count(ID) as cnt from $table_name where IP='$ip' and Status=0 and ID>$last_valid_ID and long_time>=$block_period_start  ";
		$results = $wpdb->get_results($sql);
		$result=$results[0];
		
		if($result->cnt >= WPTV__get_login_max_trials())
		return true;
	}
	return false;
}} 


//----------------------------------------------------

if(!function_exists("WPTV__is_captcha")) {
function WPTV__is_captcha()
{	
	if(WPTV__can_show_captcha_option())
	{
		if(!isset($_SESSION['cpatcha_trials']))
		$_SESSION['cpatcha_trials']=1;
		
		if(WPTV__show_captcha_option() >= $_SESSION['cpatcha_trials'])
		return false;
		
		return true;
	}
	return false;
}}
//----------------------------------------------------


if(!function_exists("clogica_visitor_country")) {
function clogica_visitor_country()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $result  = "Unknown";
    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

    if($ip_data && $ip_data->geoplugin_countryName != null)
    {
        $result = $ip_data->geoplugin_countryName;
    }

    return $result;
}}

?>