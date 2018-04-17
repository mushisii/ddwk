<?php
namespace Home\Behaviors;
class testBehavior extends \Think\Behavior{
	public function run(&$param){
		echo '我是一条'.$param['name'].'广告,'.$param['value'].'代言';
    }
}