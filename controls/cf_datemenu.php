<?php

////  class datemenu

/*

A simple class to create Date menu easily using PHP

----------------------------------------------------------
example:
----------------------------------------------------------
	$datemenu = new datemenu();

	$datemenu->date_print('nama','1-1-2009');
												
	Note it must beside a form!
*/




if(!class_exists('datemenu')){
class datemenu{

function datemenu()

	{

	echo "<script type='text/javascript' src='" . RPATH . "cframework/cf_javascript/calendarDateInput.js'></script>";



	}

		

function date_print($name,$value="")

	{

	if($value=="")

    echo "<script>DateInput('$name', true, 'YYYY-MM-DD')</script>";

    else

    echo "<script>DateInput('$name', true, 'YYYY-MM-DD','$value')</script>";

	}	

				



}}













?>