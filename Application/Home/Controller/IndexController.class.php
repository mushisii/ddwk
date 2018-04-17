<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {
	public function _initialize(){//加载
		parent::aaa();
	}
	public function aaa(){	
		session('a',5);
	}
	public function bbb(){
		echo session('a');
	}
	public function ccc(){
		session('[destroy]');
	}
	public function ddd(){
		S('b',3);
	}
	public function eee(){
		echo S('b');
	}
	public function fff(){//收集量结转
		try{
		    $pdate=date('Y-m',strtotime('-1 month'));//获得上个月时间
			$ppdate=strtotime($pdate);
			$Model=M('CollectOver');
			$M=M('CustomerOver');
			M()->startTrans();
			$res=$Model->query("select id from ddwk_collect_over where pdate='$ppdate' limit 0,1");//一个月只允许结转一次
			if(!empty($res)){
				E('已结转',10003);
			}
			$customer=$this->hhh($pdate);//服务单位信息
			$all=explode(',',$customer['all']);
			$stop=explode(',',$customer['stop']);
			$res=array_diff($all,$stop);
			$message=array();
			foreach($res as $row){
				$hid=$row;
				$arr=$this->ggg($hid,$pdate);//量
				if(!empty($arr)){    
					$message[]=$arr;
				}
			}
			$res=$Model->addAll($message);
			$r=$M->add($customer);
			if($res!==false&&$r!==false){
				M()->commit();
			}
			else{
				E('插入失败',10002);
			}
		}
		catch(\Exception $e){
			M()->rollback();
			$this->ajaxReturn($e->getMessage());
		}
	}
	private function ggg($hid,$pdate/*月*/){//医院每月数据和
		try{
			$now=date('Y-m');
			if(empty($pdate)||empty($hid)){
				E('变量出错',10001);
			}
			$Model=M();
			$res=$Model->query("select a.id,sum(b.garbage) as garbage,sum(b.lwq) as lwq,sum(b.guil) as guil,sum(b.qt) as qt,sum(b.hx) as hx,b.kg,c.n as region from ddwk_customer as a left join ddwk_collect as b on a.id=b.custid and date_format(b.pdate,'%Y-%m')='$pdate' left join ddwk_customer_c1 as c on a.region=c.value and c.mid=5 where a.id=$hid group by b.kg");
			if(!empty($res)){//在collect表里找不到符合该医院的数据
				$garbage=0;
				$lwq=0;
				$guil=0;
				$qt=0;
				$hx=0;
				$weight=0;
				$pdate=strtotime($pdate);
				foreach($res as $row){
					$id=$row['id'];
					$region=$row['region'];
					if(empty($id)||empty($region)){//医院无效或非法
						E('参数为空',10001);
					}
					$garbage+=$row['garbage'];
					$lwq+=$row['lwq'];
					$guil+=$row['guil'];
					$qt+=$row['qt'];
					$hx+=$row['hx'];
					$kg=explode(',',$row['kg']);
					$weight+=$kg['0']*$row['garbage']+$kg['1']*$row['lwq']+$kg['2']*$row['guil']+$kg['3']*$row['qt']+$kg['4']*$row['hx'];
				}
				$arr=array("hid"=>$id,"region"=>$region,"pdate"=>$pdate,"garbage"=>$garbage,"lwq"=>$lwq,"guil"=>$guil,"qt"=>$qt,"hx"=>$hx,"weight"=>$weight);
			}
			else{
				$arr=array();
			}
			return $arr;
		}
		catch(\Exception $e){
			$this->ajaxReturn($e->getMessage());
		}
	}
	public function hhh($pdate){//该月服务单位信息
		try{
            $Model=M();
            $year=date('Y',strtotime($pdate));
            $ppdate=date('Y-m',strtotime("$pdate+1 month"));		
			$all=$Model->query("select id from ddwk_customer where newdate<='$pdate'");//该月为止所有单位
			$stopover=$Model->query("select id from ddwk_customer where (year(overdate)<'$year' or overdate='0000-00-00') and (status=4 or isdel=1)");//那一年度之前停止的单位
			$all=i_array_column($all,'id');
			$stopover=i_array_column($stopover,'id');
			$all=array_diff($all,$stopover);
			$stop=$Model->query("select id from ddwk_customer where year(overdate)='$year' and overdate<'$ppdate' and (status=4 or isdel=1)");//该月为止停止的医院
			$a='';
			$b='';
			foreach($all as $row1){
				$a.=$row1.',';
			}
			foreach($stop as $row2){
				$b.=$row2['id'].',';
			}
			$a=rtrim($a,',');
			$b=rtrim($b,',');
			$pdate=strtotime($pdate);
			$arr=array('all'=>$a,'stop'=>$b,'pdate'=>$pdate);
			return $arr;
		}
		catch(\Exception $e){
			$this->ajaxReturn($e->getMessage());
		}
	}
	public function iii(){
		$startdate=strtotime(I('startdate'));
		$enddate=strtotime(I('enddate'));//月
		$custid=I('custid');
		$region=I('region');
		$where='';
		if(isset($custid)){
			$where.=" and a.hid=$hid";
		}
		if(isset($region)){
			$where.=" and a.region=$region";
		}
		$Model=M();
		$res=$Model->query("select sum(a.garbage) as garbage,sum(a.lwq) as lwq,sum(a.guil) as guil,sum(a.qt) as qt,sum(a.hx) as hx,sum(a.weight) as weight from ddwk_collect_over as a left join ddwk_customer as b on a.hid=b.id left join ddwk_customer_c1 as c on b.region=c.value and c.mid=5 where a.pdate>='$startdate' and a.pdate<='$enddate' $where group by a.hid order by a.hid asc");//收集量
		$count=$Model->query("select all,stop from ddwk_customer_over where pdate='$enddate' limit 0,1");//已最后一个月为准
		foreach($count as $row){
			$all=explode(',',$row['all']);
			$all=count($all);//服务单位数
			$stop=explode(',',$row['stop']);
			$stop=count($stop);//停止单位数
		}
	}
	public function jjj(){
		\Think\Hook::add('test','Home\\Behaviors\\testBehavior');
		$a=array('name'=>'111','value'=>'222');
		tag('test',$a);
		echo 1;
	}
	public function kkk(){
		//\Think\Hook::add('test','Behaviors\\test');
		//tag('test');
		echo 1;
	}
	
}