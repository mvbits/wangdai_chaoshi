<?php

 	global $_GPC, $_W;
	$ops = array('index', 'delete','info','index2','usergroup'); // 只支持此 3 种操作.
	$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'index';
	if($op=='index'){
		#查询用户所有信息
		$condition='uniacid ='.$_W['uniacid'];
		if($_GPC['username']){
	    	$username = $_GPC['username'];
	    	$condition .=" and name like '%{$_GPC['username']}%' "; 
	    }
		if($_GPC['mobile']){
	    	$mobile = $_GPC['mobile'];
	    	$condition .=" and mobile like '%{$_GPC['mobile']}%' "; 
	    }
		//显示第几页的数据
		$pindex = max(1, intval($_GPC['page']));
		//每页显示的条数
	    $psize = 10;
		//总条数  
	    $count = pdo_fetchcolumn("select count(id) from ".tablename('d1sj_card_user')." where ".$condition);
	    $list = pdo_fetchall("select * from ".tablename('d1sj_card_user')." where ".$condition." order by id desc  limit ". ($pindex - 1) * $psize . ',' . $psize);
	    $pager = pagination($count, $pindex, $psize);
		foreach ($list as $key => &$value) {
			$users=pdo_fetch('select * from '.tablename('mc_members')."where uniacid=:uniacid and uid=:uid",array(':uid'=>$value['uid'],':uniacid'=>$_W['uniacid']));
			$value['nickname']=$users['nickname'];
			$sql = 'SELECT * FROM ' . tablename('d1sj_card_group') . ' WHERE id = :id';
   			$info = pdo_fetch($sql, [':id' => $value['group']]);
			$value['groutname']=$info['name'];
		}
	}
	#删除
	if($op=='delete'){
		$id=$_GPC['id'];
		$user_delete=pdo_delete('d1sj_card_user',array('id'=>$id));
		if($user_delete){
			message('删除成功', $this->createWebUrl('user', array('op' => 'index')), 'success');
		}
	}
	if($op=='info'){
		$uid=$_GPC['uid'];
		$item=pdo_fetch('select * from '.tablename('d1sj_card_user')." where uid =:uid and uniacid=:uniacid",array(':uid'=>$uid,':uniacid'=>$_W['uniacid']));

	}
	if($op=='index2'){
		$uid=$_GPC['uid'];
		//显示第几页的数据
		$pindex = max(1, intval($_GPC['page']));
		//每页显示的条数
	    $psize = 10;
		//总条数  
	    $count = pdo_fetchcolumn("select count(id) from ".tablename('d1sj_card_cash')." where uniacid=:uniacid and uid=:uid",array(':uid'=>$uid,'uniacid'=>$_W['uniacid']));
		$list = pdo_fetchall("select * from ".tablename('d1sj_card_cash')." where uid=".$uid." order by id desc  limit ". ($pindex - 1) * $psize . ',' . $psize);
		$pager = pagination($count, $pindex, $psize);
	}
	if($op=='usergroup'){
		if (checksubmit()) {
			$group_id=$_GPC['group_id'];
			$user_id=$_GPC['user_id'];
			if($group_id!=0){
				$up_data=array(
					'group'=>$group_id,
				);		
				$up_list=pdo_update('d1sj_card_user',$up_data,array('id'=>$user_id));
				if($up_list){
					message('修改成功', $this->createWebUrl('user', array('op' => 'index')), 'success');
				}

			}else{
				message('修改成功', $this->createWebUrl('user', array('op' => 'index')), 'success');
			}
		}
		//查询所有的用户组别
		$uid=$_GPC['uid'];
		$user_lsit=pdo_fetch('select * from '.tablename('d1sj_card_user')." where uid=:uid and uniacid=:uniacid",array(':uid'=>$uid,':uniacid'=>$_W['uniacid']));

		$user_group=pdo_fetch('select * from '.tablename('d1sj_card_group')." where id=:id and uniacid=:uniacid",array(':id'=>$user_lsit['group'],':uniacid'=>$_W['uniacid']));
		
		$group=pdo_fetchall('select * from '.tablename('d1sj_card_group')." where uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));


	}
	include $this->template('web/user');



?>