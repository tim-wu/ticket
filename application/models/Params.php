<?php

/**
	The class for Params
	@package No1
	@author Mark
 **/

class Params extends Zend_Db_Table
{
	protected $_name = 'params';
		
	/**
		The function for Params
		@package No1
		@author Mark
	 **/
	function GetVal($vv)
	{
		$a = $this->fetchRow("ckey = '".$vv."'");
		
		
		return $a['cval'];
	}
	
	function StringFormat($string, $length=50)
	{
		if(strlen($string) > $length)
		{
			$string = substr($string, 0, $length)."...";
		}
		
		return $string;
	}
}
