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
	    $count = pdo_fetchcolumn("select count(id) from ".tablename('d1sj_card_news')." where uniacid = ".$_W['uniacid']);
	    $list = pdo_fetchall("select * from ".tablename('d1sj_card_news')." where uniacid = ".$_W['uniacid']." order by id desc  limit ". ($pindex - 1) * $psize . ',' . $psize);
	    $pager = pagination($count, $pindex, $psize);
	}
	if($op=='post'){
		$id=$_GPC['id'];
		$lists=pdo_fetchall('select * from '.tablename('d1sj_card_newclass')." where uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
		$item=pdo_fetch('select * from '.tablename('d1sj_card_news')." where id=:id and uniacid =:uniacid",array(':id'=>$id,':uniacid'=>$_W['uniacid']));
		if (checksubmit()) {
			$new_data=array(
				'title'=>$_GPC['title'],
				'thumb'=>$_GPC['thumb'],
				'uniacid'=>$_W['uniacid'],
				'desc'=>$_GPC['desc'],
				'weight'=>$_GPC['weight'],
				'newclass_id'=>$_GPC['newclass_id'],
				'create_time'=>time(),
				'content'=>$_GPC['content'],
				'sort'=>$_GPC['sort'],

			);
		
			if(!$id){
				$new_result=pdo_insert('d1sj_card_news',$new_data);
				if($new_result){
					message('添加成功', $this->createWebUrl('news', array('op' => 'index')), 'success');
				}
			}else{
				$new_result=pdo_update('d1sj_card_news',$new_data,array('id'=>$id));
				if($new_result){
					message('修改成功', $this->createWebUrl('news', array('op' => 'index')), 'success');
				}
			}
		}
	}
	#删除
	if($op=='delete'){
		$id=$_GPC['id'];
		$new_delete=pdo_delete('d1sj_card_news',array('id'=>$id));
		if($new_delete){
			message('删除成功', $this->createWebUrl('news', array('op' => 'index')), 'success');
		}
	}


	include $this->template('web/news');



?>