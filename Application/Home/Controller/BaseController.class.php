<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends AutoController {
	public function aaa(){//加载
		parent::autoLoding();
	}
}