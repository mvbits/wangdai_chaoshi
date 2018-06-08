<?php

 	global $_GPC, $_W;
	$ops = array('index', 'post','delete'); // 只支持此 3 种操作.
	$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'index';
	if($op=='index'){
		//显示第几页的数据
		$pindex = max(1, intval($_GPC['page']));
		//每页显示的条数
	    $psize = 10;
		//总条数  
	    $count = pdo_fetchcolumn("select count(id) from ".tablename('d1sj_card_card')." where uniacid=".$_W['uniacid']);
		$list = pdo_fetchall("select * from ".tablename('d1sj_card_card')." where uniacid=".$_W['uniacid']." order by id desc  limit ". ($pindex - 1) * $psize . ',' . $psize);
		$pager = pagination($count, $pindex, $psize);

	}
	if($op=='post'){
		$id=$_GPC['id'];
		$item=pdo_fetch('select * from '.tablename('d1sj_card_card')."where id=:id and uniacid=:uniacid ",array(':id'=>$id,':uniacid'=>$_W['uniacid']));
		$banks=pdo_fetchall('select name,id from  '.tablename('d1sj_card_bank')." where uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
		if (checksubmit()) {
			$group_date=array(
				'title' => $_GPC['title'],
				'bank'  => $_GPC['bank'],
				'photo' => $_GPC['photo'],
				'desc'  =>$_GPC['desc'],
				'api'   =>$_GPC['api'],
				'uniacid'=>$_W['uniacid'],
				'recommend'=>$_GPC['recommend'],
				'detailed'=>$_GPC['detailed'],
			);
			if(!$id){
				$group_res=pdo_insert('d1sj_card_card',$group_date);
				if($group_res){
					message('添加成功',$this->createWebUrl('card',array('op'=>'index')),'success');
				}
			}else{
				$group_res=pdo_update('d1sj_card_card',$group_date,array('id'=>$id));
				if($group_res){
					message('修改成功',$this->createWebUrl('card',array('op'=>'index')),'success');
				}
			}
		}
	}
	if($op=='delete'){
		$id=$_GPC['id'];
		$group_res=pdo_delete('d1sj_card_card',array('id'=>$id));
		if($group_res){
			message('删除成功',$this->createWebUrl('card',array('op'=>'index')),'success');
		}

	}


	include $this->template('web/card');

?>