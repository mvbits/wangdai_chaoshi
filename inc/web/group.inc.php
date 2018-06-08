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
	    $count = pdo_fetchcolumn("select count(id) from ".tablename('d1sj_card_group')." where uniacid=".$_W['uniacid']);
		$list = pdo_fetchall("select * from ".tablename('d1sj_card_group')." where uniacid=".$_W['uniacid']." order by id desc  limit ". ($pindex - 1) * $psize . ',' . $psize);
		$pager = pagination($count, $pindex, $psize);

	}
	if($op=='post'){
		$id=$_GPC['id'];
		$item=pdo_fetch('select * from '.tablename('d1sj_card_group')."where id=:id and uniacid=:uniacid ",array(':id'=>$id,':uniacid'=>$_W['uniacid']));
		if (checksubmit()) {
			$group_date=array(
				'name'   => $_GPC['name'],
				'price'  => $_GPC['price'],
				'mout'   => $_GPC['mout'],
				'mlevel' =>$_GPC['mlevel'],
				// 'desc'   =>$_GPC['desc'],
				'type'  =>$_GPC['type'],
				'weight' =>$_GPC['weight'],
				'orderby'=>$_GPC['orderby'],
				'orderby'=>$_GPC['orderby'],
				'uniacid'=>$_W['uniacid'],
				'mout_tow'=>$_GPC['mout_tow'],
			);
			if(!$id){
				$group_res=pdo_insert('d1sj_card_group',$group_date);
				if($group_res){
					message('添加成功',$this->createWebUrl('group',array('op'=>'index')),'success');
				}
			}else{
				$group_res=pdo_update('d1sj_card_group',$group_date,array('id'=>$id));
				if($group_res){
					message('修改成功',$this->createWebUrl('group',array('op'=>'index')),'success');
				}
			}
		}
	}
	if($op=='delete'){
		$id=$_GPC['id'];
		$group_res=pdo_delete('d1sj_card_group',array('id'=>$id));
		if($group_res){
			message('删除成功',$this->createWebUrl('group',array('op'=>'index')),'success');
		}

	}


	include $this->template('web/group');

?>

