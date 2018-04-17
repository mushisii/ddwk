<?php
namespace Home\Controller;
use Think\Controller;
class AutoController extends Controller {
	public function autoLoding(){//加载
		self::catchConfig();
	}
	static private function catchConfig(){
		$config=C('CACHE_OPTION');
		S($config);
	}
}