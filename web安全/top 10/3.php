<?php

/**
*反序列化
*/

class person
{
	public $name = '';
	public $age = 0;
	
	public function informaton()
	{
	echo 'Person:'.$this->name.' is '.$this->age. 'years old. <br/>';
	}
}
$per = unserialize('O:6:"Person":2:{s:4:"name";s:5:"Amber";s:3:"age";i:18;}');

$per -> informaton()
?>