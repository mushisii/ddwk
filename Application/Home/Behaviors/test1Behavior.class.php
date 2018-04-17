<?php
namespace Home\Behaviors;
class test1Behavior extends \Think\Behavior{
	public function run(&$param){
		echo 123;
    }
}