<?php

/**
*序列化
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

$per = new Person();
$per -> name ='Amber';
$per -> age = 18;

echo serialize($per)

?>