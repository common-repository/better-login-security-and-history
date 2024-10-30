<?php
	include_once "controls.php";
	global $wpdb,$table_prefix,$table_name; 
	$table_name = $table_prefix . 'better_login_security_history';
	if($_POST['clear_login_history']!='')
	{
		$sql=" TRUNCATE TABLE $table_name";
		$wpdb->query($sql);
	}
?>
<form method="POST">
<?php
	$options= WPTV__my_options();
	$images_path  = WPTV__get_url_path() . "images/";
	$page_current = intval($_GET['page_num']);
	$page_row = intval($options['history_rows_per_page']);
	if($page_row<1)
		$page_row=20;
	$table_row = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");	
	
	$page_count=intval($table_row/$page_row);
	if($table_row % $page_row > 0)
		$page_count=$page_count+1;
	if($page_count ==0)
		$page_count=1;
	
	if($page_current<1)
		$page_current=1;
	else if($page_current > $page_count)
		$page_current=$page_count;	
	
	$rows_start = (($page_current - 1) * $page_row);
	$rows_end=$page_row;
	/////////////////////////
	$history_html='';
	$query="SELECT * FROM {$table_name} ORDER BY Sinon_time DESC LIMIT {$rows_start},{$rows_end}";
	$rows = $wpdb->get_results($query);
	foreach($rows as $row){
		$history_html=$history_html."<tr>
		<td>"."<img width='18px' height='18px' src='".$images_path .$row->Status. ".gif' /></td>
		<td>".$row->Username. "</td>
		<td>".$row->Name. "</td>
		<td>".$row->Usertype. "</td>
		<td>".$row->Sinon_time. "</td>
		<td>".$row->logged_out_time. "</td>
		<td>".$row->Country. "</td>
		<td>".$row->IP. "</td>
		<td>".$row->OS. "</td>
		<td>".$row->Browser. "</td>
		<td><span class='wps_password'>". $row->Password. "</span>??????????</td>
		</tr>";
	}
?>
<table class="grid" style="width:99%; margin:0 auto;"><thead>
	<tr>
		<td>Status</td><td>Username</td>
		<td>Name</td><td>User type</td>
		<td>Date &amp; Time</td>
		<td>Logged out</td>
		<td>Country</td><td>IP</td>
		<td>OS</td><td>Browser</td>
		<td>Password: <span class="wps_action_password">Show</span></td>
	</tr>
</thead>
<tbody>
<?php echo $history_html;?>
</tbody>
</table>
<div class="pagination" style="width:99%; margin:0 auto;">
<span style="background:#EEE;"><?php echo $page_current;?> of pages <?php echo $page_count;?></span><span class="spaninput"><input onkeydown="return keypress_gopage(event)" type="text" name="txt_gopage" id="txt_gopage" size="3" value="<?php echo $page_current;?>"></span><a onclick="fun_gopage()" href="javascript:void(0);">GO</a> <a id="btn_page_prev" onclick="fun_prev_page()" href="javascript:void(0);">PREV</a> <a  id="btn_page_next"onclick="fun_next_page()" href="javascript:void(0);">NEXT</a></div>
<div style="float:right;"><input class="button-primary" type="submit" value="  Clear History  " name="clear_login_history"></div>
<script>
	var page_curr=parseInt(<?php echo $page_current;?>);
	var page_total=parseInt(<?php echo $page_count;?>);
	function fun_gopage(page_number)
	{
		var page=page_number || parseInt(document.getElementById('txt_gopage').value);
		var url = "?page=better-login-security-history.php&tab=1&page_num={pagination}";
		if(page>0)
		{
			window.location= url.replace(/{pagination}/gi, page).replace(/{pagerow}/gi, <?php echo $page_row;?>);
		}else
		{
			alert('invalid page number!');
		}
	}
	function fun_next_page(){
		var page_curr=parseInt(<?php echo $page_current;?>);
		if(page_curr==page_total) return;
		fun_gopage(page_curr+1);
	}
	function fun_prev_page(){	
		var page_curr=parseInt(<?php echo $page_current;?>);
		if(page_curr==1) return;
		fun_gopage(page_curr-1);
	}
	function keypress_gopage(event)
	{
		var key=event.keyCode;
		if(key == 13)
		{
			fun_gopage();
			return false; 
		}
	}
	
	if(page_curr==page_total){
		jQuery('#btn_page_next').addClass('disabled');
	}
	else if(page_curr==1){
		jQuery('#btn_page_prev').addClass('disabled');
	}
	
	
	jQuery('.wps_action_password').click(function(){
	jQuery('.grid tbody tr .wps_password').toggle();
	jQuery(this).text(jQuery(this).text()=='Show'?'Hide':'Show');
});
</script>
</form>