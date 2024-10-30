<?php
	
	$options= WPTV__my_options();
	
	if($_POST['can_show_captcha_option']!='')
	{
		$saved_options = "Options Saved! ";
		$newoptions['can_show_captcha_option']= intval($_POST['can_show_captcha_option']);
		$newoptions['can_block_login_trials']= intval($_POST['can_block_login_trials']);
		$newoptions['login_blocked_msg']= $_POST['login_blocked_msg'];
		$newoptions['show_captcha_count_option']= intval($_POST['show_captcha_count_option']);
		
		if( intval($_POST['login_max_trials']) >= 3)
		{
		 $newoptions['login_max_trials']= intval($_POST['login_max_trials']);
		}else
		{
		 $newoptions['login_max_trials']=$options['login_max_trials'];
		 $saved_options = $saved_options . "<br/>Note that 'Login Blocker Trials' does not changed because it must be 3 or greater!";
		}
		

		if( intval($_POST['login_block_time']) >= 3)
		{
		 $newoptions['login_block_time']= intval($_POST['login_block_time']);
		}else
		{
		 $newoptions['login_block_time']=$options['login_block_time'];
		 $saved_options = $saved_options . "<br/>Note that 'Login Blocker Time' does not changed because it must be 3 or greater!";
		}	
			
		if( intval($_POST['history_rows_per_page']) >= 1)
		{
		 $newoptions['history_rows_per_page']= intval($_POST['history_rows_per_page']);
		}else
		{
		 $newoptions['history_rows_per_page']=$options['history_rows_per_page'];
		 $saved_options = $saved_options . "<br/>Note that 'Rows per page' does not changed because it must be 1 or greater!";
		}
		$newoptions['history_show_password']= intval($_POST['history_show_password']);

		WPTV__update_my_options($newoptions);
		WPTV__option_msg($saved_options);
	}
	
	
	$options= WPTV__my_options();
	
	
?>

<form method="POST">
	<div id="better_login_security_history_option">
		<h3>Login Captcha Options<hr/></h3>		
		<div>
			<label>Login Captcha:</label>
			<select size="1" name="can_show_captcha_option" id="can_show_captcha_option">
			<option value="1">Enabled</option>
			<option value="0">Disabled</option>
			</select>
		</div>
		<div>
		<label>Show Captcha After:</label>
		<input type="text" name="show_captcha_count_option" id="show_captcha_count_option" size="10" value="<?=$options['show_captcha_count_option']?>"> 
		<span>Login Trials:</span> <small><font color="#999999">(ex 1,2,3,.. put 0 to show captcha every time)</font></small><br/>
		</div>

		<h3>Login Blocker Options<hr/></h3>
		<div>
			<label>Login Blocker:</label>
			<select size="1" name="can_block_login_trials" id="can_block_login_trials">
			<option value="1">Enabled</option>
			<option value="0">Disabled</option>
			</select>
		</div>
		<div>
			<label>Block Login After:</label>
			<input type="text" name="login_max_trials" id="login_max_trials" size="10" value="<?=$options['login_max_trials']?>"> Login Trials <small> 
			<font color="#999999">(3 or greater)</font></small> 
		</div>
		<div>
			<label>Login Blocker Time:</label>
			<input type="text" name="login_block_time" id="login_block_time" size="10" value="<?=$options['login_block_time']?>"> 
			<span>Minutes</span> <font color="#999999"> <small> (3 or greater)</small></font>
		</div>
		<div>
			<label>Blocker page Message</label>
			
			<textarea name="login_blocked_msg" id="login_blocked_msg"><?=$options['login_blocked_msg']?></textarea>
		</div>
		<h3>History Options<hr/></h3>
		<div>
			<label>Rows per page:</label>
			<input type="text" name="history_rows_per_page" id="history_rows_per_page" size="3" value="<?=$options['history_rows_per_page']?>">
		</div>
		<div>
			<label>Display password:</label>
			<select size="1" name="history_show_password" id="history_show_password">
				<option value="1">Enabled</option>
				<option value="0" selected="selected">Disabled</option>
			</select>
		</div>
		<div>
			<input  class="button-primary" type="submit" value="  Update Options  " name="Save_Options">
		</div>
	</div>
	<script>
		document.getElementById('can_block_login_trials').value = "<?=$options['can_block_login_trials']?>";
		document.getElementById('can_show_captcha_option').value = "<?=$options['can_show_captcha_option']?>";
		document.getElementById('history_show_password').value = "<?=$options['history_show_password']?>";
	</script>
</form>