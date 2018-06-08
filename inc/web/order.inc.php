<?php
global $_GPC, $_W;
$ops = array('index', 'post','delete'); // 只支持此 3 种操作.
$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'index';
if($op=='index'){
	#查询用户所有信息
	$condition='uniacid ='.$_W['uniacid'];
	if($_GPC['sn']){
		$sn = $_GPC['sn'];
		$condition .=" and sn like '%{$_GPC['sn']}%' "; 
	}
	if($_GPC['status']){
		$status = $_GPC['status'];
		$condition .=" and status like '%{$_GPC['status']}%' "; 
	}
	//显示第几页的数据
	$pindex = max(1, intval($_GPC['page']));
	//每页显示的条数
	$psize = 10;
	//总条数  
	$count = pdo_fetchcolumn("select count(id) from ".tablename('d1sj_card_order')." where ".$condition);
	$list = pdo_fetchall("select * from ".tablename('d1sj_card_order')." where ".$condition." order by id desc  limit ". ($pindex - 1) * $psize . ',' . $psize);
	$pager = pagination($count, $pindex, $psize);
	$pager = pagination($count, $pindex, $psize);
}
include $this->template('web/order');

?>