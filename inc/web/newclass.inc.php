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
	    $count = pdo_fetchcolumn("select count(id) from ".tablename('d1sj_card_newclass')." where uniacid = ".$_W['uniacid']);
	    $list = pdo_fetchall("select * from ".tablename('d1sj_card_newclass')." where uniacid = ".$_W['uniacid']." order by id desc  limit ". ($pindex - 1) * $psize . ',' . $psize);
	    $pager = pagination($count, $pindex, $psize);
	}
	if($op=='post'){
		$id=$_GPC['id'];
		$item=pdo_fetch('select * from '.tablename('d1sj_card_newclass')." where id=:id and uniacid =:uniacid",array(':id'=>$id,':uniacid'=>$_W['uniacid']));
		if (checksubmit()) {
			$new_data=array(
				'name'=>$_GPC['name'],
				// 'img'=>$_GPC['img'],
				'uniacid'=>$_W['uniacid'],
			);
			if(!$id){
				$new_result=pdo_insert('d1sj_card_newclass',$new_data);
				if($new_result){
					message('添加成功', $this->createWebUrl('newclass', array('op' => 'index')), 'success');
				}
			}else{
				$new_result=pdo_update('d1sj_card_newclass',$new_data,array('id'=>$id));
				if($new_result){
					message('修改成功', $this->createWebUrl('newclass', array('op' => 'index')), 'success');
				}
			}
		}
	}
	#删除
	if($op=='delete'){
		$id=$_GPC['id'];
		$new_delete=pdo_delete('d1sj_card_newclass',array('id'=>$id));
		if($new_delete){
			message('删除成功', $this->createWebUrl('newclass', array('op' => 'index')), 'success');
		}
	}


	include $this->template('web/newclass');



?>