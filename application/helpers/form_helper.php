<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	Class: FormHelper
	File: form_helper.php
	About: 
		Author: Tauni Daub
		Use: To create form elements easily
			with direct function calls
*/
if ( ! function_exists('textbox'))
{
/*
Function: textbox
	Creates and displays a textbox with the given specs
	Parameters: 
		string - name and id
		int - size [optional, default = 20]
		string - value [optional, null]
		string - change = onChange event [optional, null]
*/

	function textbox($name,$size=20,$value=NULL, $change=NULL){
		if ($change==NULL)
			echo "<input type='text' name='".$name."' id='".$name." 'size='".$size."' value='".$value."'>";
		else 
			echo "<input type='text' name='".$name."' id='".$name." 'size='".$size."' value='".$value."' onChange='javascript:".$change."'>";
	}
}

?>