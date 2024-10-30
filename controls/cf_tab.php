<?php

if(!class_exists('phptab')){
class phptab{

var $tabs;  // each tab has  num,title,content,parameter.
var $parameter = 'tab';


	function phptab($parameter='tab')
	{
		$this->$parameter = $parameter;
	}
	
//----------------------------------------------------------------------------	
	
	function add_file_tab($num, $title, $content, $type )
	{
	  $index=$this->tabs_count();
	  	$this->tabs[$index]['num']=$num;
		$this->tabs[$index]['title']=$title;
		$this->tabs[$index]['content']=$content;
		$this->tabs[$index]['type']=$type;

	}	

	
//----------------------------------------------------------------------------

	function tabs_count()
	{
	if(is_array($this->tabs))
	return count($this->tabs);
	else
	return 0;
	}	
	
//----------------------------------------------------------------------------	
	function run()
	{
		
		
		$tab_index= $_GET[$this->parameter];
		
		if($_GET[$this->parameter]=='')
		$tab_index=$this->tabs[0]['num'];
		
		
		$options_path= c_get_current_parameters($this->parameter);
		$num_index=-1;
		echo '<ul class="tabs">';
				
		for($i=0;$i<$this->tabs_count();$i++)
		{
			if($this->tabs[$i]['num']==$tab_index){
			echo '<li class="active"><a href="' . $options_path . '&' . $this->parameter .'=' . $this->tabs[$i]['num'] . '">' .  $this->tabs[$i]['title'] . '</a></li>';
			$num_index=$i;
			}
			else
			{
			echo '<li><a href="' . $options_path . '&' . $this->parameter .'=' . $this->tabs[$i]['num'] . '">' .  $this->tabs[$i]['title'] . '</a></li>';
			}
		}
		
		echo '</ul>';
		
		
		
	    if($num_index>=0)
	    {
	     	echo '<div class="tabContainer"><div id="tab1" class="tabContent">';
	     		//if(file_exists(c_get_abs_path() . 'options/' . $this->tabs[$num_index]['content']))
	     		include c_get_abs_path() . 'options/' . $this->tabs[$num_index]['content'];
	     	echo '</div></div>';
	    }
		
		
		
	}


}}

?>