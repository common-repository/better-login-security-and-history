<?php

////  class ckeckboxlist
/*

A class to create a check boxes list using PHP

------------------------------------------------
example
------------------------------------------------

$check = new ckeckboxlist('chlist',200,100,$_POST['chlist'],'FFcaFF');
$check->checkalltext("تحديد الكل");

$check->additem('f1','الخيار الأول');
$check->additem('f2','الخيار 2');
$check->additem('f3','الخيار 3');
$check->additem('f4','الخيار 4');
$check->additem('f5','الخيار 5');
$check->additem('f6','الخيار 6');
$check->additem('f7','الخيار 7');
$check->endlist();


*/

if(!class_exists('ckeckboxlist')){
class ckeckboxlist{
      var $chname="check";
      var $check;
      var $script="";
      var $checkall="Check All";
      var $precheck="";
      var $width=100;
      var $height=100;
      var $selcolor="CCFFCC";


      //----------------------------------------------------------------------
      function ckeckboxlist($chname,$width=100,$height=100,$precheck,$selcolor="CCFFCC")
      {
        $this->chname=$chname;
        $this->check = new checkbox($chname);
        $this->check->chbox_init();
        $this->width=$width;
        $this->height=$height;
        $this->precheck=$precheck;
        $this->selcolor=$selcolor;
      }


      //----------------------------------------------------------------------

      function checkalltext($txt)
      {
      $this->checkall=$txt;

      }

      //----------------------------------------------------------------------
      function ischecked($val)
      {
      $chks=split(',',$this->precheck);
      for($i=0;$i< count($chks); $i++)
      	{ if($chks[$i]==$val)
      	  return'checked';
      	}

      return '';
      }


      //----------------------------------------------------------------------
      function additem($val,$txt)
      {
      $this->script=$this->script . "<div id='" . $this->check->getcheck($this->check->getcount()+1). "_div' >" . $this->check->get_chbox_add($this->check->getcount()+1,$val,$this->ischecked($val),$this->check->chname . "_div_list_color('" . $this->check->getcheck($this->check->getcount()+1). "')") . "<span style='cursor:hand'  onclick=\"". $this->check->chname ."_list_check('" . $this->check->getcheck($this->check->getcount()) . "') \"> $txt</span></div>";
      }

	  //------------------------------------------------------------------------
	  
	  function data_bind($tbl,$name="name",$id="id",$where="",$order="",$limit="")
	 	{
			global $mysql;
			$res=$mysql->sql(" select $name,$id from PREFIX_$tbl $where $order $limit ");
			while($ar=mysql_fetch_array($res)){
				$this->additem($ar[1],$ar[0]);
			}
	 	}
	
      //----------------------------------------------------------------------
      function endlist()
      {


      $js="<script>
      function " . $this->check->chname . "_list_check(check){
      if(document.getElementById(check).checked){
      document.getElementById(check).checked=false;
      }
      else
      {
      document.getElementById(check).checked=true;
      }

      " . $this->check->chname . "_div_list_color(check);
      " . $this->check->chname . "_update();
      }



      function " . $this->check->chname . "_div_list_color(check){
      if(document.getElementById(check).checked){
      document.getElementById(check + '_div').style.backgroundColor='#" . $this->selcolor . "';
      }
      else
      {
      document.getElementById(check + '_div').style.backgroundColor='';
      }
      }


      function " . $this->check->chname . "_all_div_list_color()
      {
      var count = document.getElementById('" . $this->check->chname . "_count').value;
      for(i=1;i<=count;i++)
      " . $this->check->chname . "_div_list_color('" . $this->check->chname . "_box' + i);

      }

      </script>";


      $htm="<div  style='background-color: #FFFFF6; width: " . $this->width . "px; height: " . $this->height . "px; overflow:auto;' >";

      echo $js;
      echo $htm;
      $this->check->chbox_all($this->check->chname . "_all_div_list_color()");
      echo $this->checkall . "<br>";
      echo $this->script;
      $this->check->chbox_finish();
      echo "</div>";
      echo "<script>" . $this->check->chname . "_all_div_list_color()</script>";
      echo "<script>" . $this->check->chname . "_update()</script>";
      }

}}





?>