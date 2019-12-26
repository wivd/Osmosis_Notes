<?php
/**
*序列化
*/
class Person
{
	public $name = '';
	public $age = 0;
	
	public function informaton()
	{
	echo 'Person:'.$this->name.' is '.$this->age. 'years old. <br/>';
	}
	public function_tostring(){
		return 'I am_tostring <br />';
	}
	public function_construct(){
		echo 'I am_construct <br />';
	}
	public function_destruct(){
		echo 'I am_destruct <br />';
	}
}
$per = new Person();
$per -> name ='Amber';
$per -> age = 18;
echo $per1;

?>