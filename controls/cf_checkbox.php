<?php

////  checkbox class to create a list of checks.

/*

A class to create a custom group of check boxes to use in any report

------------------------------------------------------------------
example
------------------------------------------------------------------

	<?php

		$ch = new checkbox('check33');

		$ch->chbox_init();

		?>

		<? $ch->chbox_all(); ?>  Check all <br>

		<p><? $ch->chbox_add(1,12) ?>i urtiurtowertowet</p>

		<p><? $ch->chbox_add(2,13) ?> oiutoieru twoieurtoe</p>

		<p><? $ch->chbox_add(3,14) ?> wiu riow uerowqeu r</p>

		<p><? $ch->chbox_finish()?>

*/

if(!class_exists('checkbox')){
class checkbox{


var $chname="check";
var $count=0;


//----------------------------------------------------------------------

function set_sellect_all_functions($fun)

{

$this->sellect_all_functions=$fun;

}



//----------------------------------------------------------------------

function checkbox($chname)

{

$this->chname=$chname;

}


//----------------------------------------------------------------------

function chbox_init()

{


$js="



<script>

function " . $this->chname . "_update()

{

var check_name='" . $this->chname . "';

document.getElementById(check_name).value='';

count=document.getElementById(check_name + '_count').value;

if(count>0){

	for(i=1;i<=count;i++)

	{	

		if(document.getElementById(check_name + '_box' + i ).checked)

			{

			if(document.getElementById(check_name).value =='')

					document.getElementById(check_name).value=document.getElementById(check_name + '_box' + i ).value;

			else

					document.getElementById(check_name).value= document.getElementById(check_name).value + ',' +  document.getElementById(check_name + '_box' + i ).value;

			}

	}

}

}

</script>


<script>

function " . $this->chname . "_checkall()

{

count=document.getElementById('" . $this->chname . "_count').value;

if(count>0){

	

	initval=document.getElementById('" . $this->chname . "_allboxes' ).checked;

	for(i=1;i<=count;i++)

	{

	document.getElementById('" . $this->chname . "_box' + i ).checked=initval;



	}

}

" . $this->chname . "_update();

}

</script>

";



echo $js;

}



//----------------------------------------------------------------------

function chbox_all($fun="")

{

echo "<input type='checkbox' name='" . $this->chname . "_allboxes'  id='" . $this->chname . "_allboxes' onclick=\" " . $this->chname . "_checkall();$fun\" value='ON'>";

}


//----------------------------------------------------------------------

function chbox_add($num,$val,$ext='',$onclick='')

{

$this->count=$this->count+1;

echo "<input onclick=\" " . $this->chname . "_update();document.getElementById('" . $this->chname . "_allboxes' ).checked=false;$onclick\" type='checkbox' name='" . $this->chname . "_box". $num ."' id='" . $this->chname . "_box". $num ."' value='" . $val . "'  $ext >";


}


//----------------------------------------------------------------------

function get_chbox_add($num,$val,$ext='',$onclick='')

{

$this->count=$this->count+1;

return "<input onclick=\" " . $this->chname . "_update();document.getElementById('" . $this->chname . "_allboxes' ).checked=false;$onclick\" type='checkbox' name='" . $this->chname . "_box". $num ."' id='" . $this->chname . "_box". $num ."' value='" . $val . "'  $ext >";

}


//----------------------------------------------------------------------

function getcheck($num)

{

return $this->chname . "_box". $num;

}

//----------------------------------------------------------------------

function getcount()

{

return $this->count;

}



//----------------------------------------------------------------------

function chbox_finish()

{

 echo "<input type='hidden' name='" . $this->chname . "'  id='" . $this->chname . "' size='21'><input type='hidden' name='" . $this->chname . "_count' id='" . $this->chname . "_count' value='" . $this->count . "' >";

}


}}











?>