<?php
	require MODULE_ROOT . '/helpers/functions.php';
	$config = config();
 	global $_GPC, $_W;
	$ops = array('index', 'delete','confirm','cancel','remote','reject','fencheng_confirm'); // 只支持此 3 种操作.
	$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'index';

	if($op=='index'){
		#查询办卡信息
		$condition='uniacid ='.$_W['uniacid'];
		if($_GPC['lend']){
	    	$bank = $_GPC['lend'];
	    	$condition .=" and bank like '%{$_GPC['lend']}%' "; 
	    }
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

		$count = pdo_fetchcolumn("select count(id) from ".tablename('d1sj_card_record')." where ".$condition." and type =2");

		$list = pdo_fetchall("select * from ".tablename('d1sj_card_record')." where ".$condition." and type =2 order by id desc  limit ". ($pindex - 1) * $psize . ',' . $psize);

		$pager = pagination($count, $pindex, $psize);
		$lend=pdo_fetchall('select title,id from  '.tablename('d1sj_card_lend')." where uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
		
	}


	if($_GPC['id']){
		$id=$_GPC['id'];
		$record_res=pdo_fetch('select * from'.tablename('d1sj_card_record')." where id=:id",array(':id'=>$id));
		if(!$record_res){
			message('没有对应的数据', $this->createWebUrl('netloan', array('op' => 'index')), 'error');
		}
		if($op=='confirm'){
			#确认返佣给用户对应的上级返佣
			$usre_uid1=pdo_fetch('select * from '.tablename('d1sj_card_user')." where uid=:uid and uniacid=:uniacid",array(':uid'=>$record_res['fan_uid1'],':uniacid'=>$_W['uniacid']));
			$usre_uid2=pdo_fetch('select * from '.tablename('d1sj_card_user')." where uid=:uid and uniacid=:uniacid",array(':uid'=>$record_res['fan_uid2'],':uniacid'=>$_W['uniacid']));
			$usre_uid3=pdo_fetch('select * from '.tablename('d1sj_card_user')." where uid=:uid and uniacid=:uniacid",array(':uid'=>$record_res['fan_uid3'],':uniacid'=>$_W['uniacid']));
			if($usre_uid1){
				$usre_data1=array(
					'money'=>$usre_uid1['money']+$record_res['fan_level1'],
				);
				pdo_update('d1sj_card_user',$usre_data1,array('id'=>$usre_uid1['id']));
			}
			if($usre_uid2){
				$usre_data2=array(
					'money'=>$usre_uid2['money']+$record_res['fan_level2'],
				);
				pdo_update('d1sj_card_user',$usre_data2,array('id'=>$usre_uid2['id']));
			}
			if($usre_uid3){
				$usre_data3=array(
					'money'=>$usre_uid3['money']+$record_res['fan_level3'],
				);
				pdo_update('d1sj_card_user',$usre_data3,array('id'=>$usre_uid3['id']));
			}
			$record_data=array(
				'status'=>1,
				'fan_total'=>$record_res['fan_level1']+$record_res['fan_level2']+$record_res['fan_level3'],
			);
			$record_result=pdo_update('d1sj_card_record',$record_data,array('id'=>$id));
			#发送模板消息
			$config=$this->config();
			$record_res_list=pdo_fetch('select * from '.tablename('d1sj_card_record')."where id=:id",array(':id'=>$id));
			$user_list_openid=pdo_fetch('select openid,name from '.tablename('d1sj_card_user')." where uid=:uid and uniacid=:uniacid",array(':uid'=>$record_res_list['uid'],':uniacid'=>$_W['uniacid']));

			$lend_resd=pdo_fetch('select title from '.tablename('d1sj_card_lend')."where uniacid=:uniacid and id =:id ",array(':uniacid'=>$_W['uniacid'],':id'=>$record_res_list['bank']));
			//给自己发送模板消息
			$this->self_examine_muban($lend_resd['title'],$user_list_openid['name'],$user_list_openid['openid'],$config['loan_template_id'],date('Y-m-d H:i:s',time()));
			//给上级发送模板消息
			if($usre_uid1){
				$message="您的直推用户".$user_list_openid['name']."已完成".$lend_resd['title']."产品的申请恭喜您获得".$record_res['fan_level1']."元奖励";
				$res=$this->to_examine_muban($message,$lend_resd['title'],$record_res['fan_level1'],$usre_uid1['openid'],$config['sj_member_template_id']);		
			}
		

			if($record_result){
				message('修改成功', $this->createWebUrl('netloan', array('op' => 'index')), 'success');
			}
		}

		if($op=='reject'){
			$record_data=array(
				'status'=>2,
			);
			$record_result=pdo_update('d1sj_card_record',$record_data,array('id'=>$id));
			if($record_result){
				message('修改成功', $this->createWebUrl('netloan', array('op' => 'index')), 'success');
			}
		}
		#删除
		if($op=='delete'){
			$id=$_GPC['id'];
			$record_delete=pdo_delete('d1sj_card_record',array('id'=>$id));
			if($record_delete){
				message('删除成功', $this->createWebUrl('netloan', array('op' => 'index')), 'success');
			}
		}
	}
	#如果分成金额是百分百添加下款金额按比例分成
	if($op=='fencheng_confirm'){
			$id=$_GPC['id'];
			$card_record=pdo_fetch('select * from '.tablename('d1sj_card_record')." where id=:id and uniacid=:uniacid",array(':id'=>$id,':uniacid'=>$_W['uniacid']));
				#提交修改数据
		if(checksubmit()){
			$id=$_GPC['id'];
			$application_amount=$_GPC['application_amount'];
			$list_record=pdo_fetch('select * from '.tablename('d1sj_card_record')." where id=:id and uniacid=:uniacid",array(':id'=>$id,':uniacid'=>$_W['uniacid']));
			if($list_record['loan_state']==2){
				//按百分比分成
				if($list_record['percentage_noe']!=0){
					$fencheng_noe=($application_amount*$list_record['percentage_noe'])/100;
					$datas['fan_level1']=$list_record['fan_level1']+$fencheng_noe;
				}else{
					$datas['fan_level1']=$list_record['fan_level1'];
				}
				if($list_record['percentage_tow']!=0){
					$fencheng_tow=($application_amount*$list_record['percentage_tow'])/100;
					$datas['fan_level2']=$list_record['fan_level2']+$fencheng_tow;
				}else{
					$datas['fan_level2']=$list_record['fan_level2'];
				}
				$datas['application_amount']=$application_amount;
				$datas['fan_total']=$datas['fan_level1']+$datas['fan_level2'];
				$datas['status']=1;
				$record_result=pdo_update('d1sj_card_record',$datas,array('id'=>$id));
				if($record_result){
					$list_record_result_res=pdo_fetch('select * from '.tablename('d1sj_card_record')." where id=:id and uniacid=:uniacid",array(':id'=>$id,':uniacid'=>$_W['uniacid']));
					#确认返佣给用户对应的上级返佣
					$usre_uid1=pdo_fetch('select * from '.tablename('d1sj_card_user')." where uid=:uid and uniacid=:uniacid",array(':uid'=>$list_record_result_res['fan_uid1'],':uniacid'=>$_W['uniacid']));
					$usre_uid2=pdo_fetch('select * from '.tablename('d1sj_card_user')." where uid=:uid and uniacid=:uniacid",array(':uid'=>$list_record_result_res['fan_uid2'],':uniacid'=>$_W['uniacid']));
			
					if($usre_uid1){
						$usre_data1=array(
							'money'=>$usre_uid1['money']+$list_record_result_res['fan_level1'],
						);
						pdo_update('d1sj_card_user',$usre_data1,array('id'=>$usre_uid1['id']));
					}
					if($usre_uid2){
						$usre_data2=array(
							'money'=>$usre_uid2['money']+$list_record_result_res['fan_level2'],
						);
						pdo_update('d1sj_card_user',$usre_data2,array('id'=>$usre_uid2['id']));
					}
					#发送模板消息
					$config=$this->config();
					$user_list_openid=pdo_fetch('select openid,name,leader1 from '.tablename('d1sj_card_user')." where uid=:uid and uniacid=:uniacid",array(':uid'=>$list_record_result_res['uid'],':uniacid'=>$_W['uniacid']));

					$lend_resd=pdo_fetch('select title from '.tablename('d1sj_card_lend')."where uniacid=:uniacid and id =:id ",array(':uniacid'=>$_W['uniacid'],':id'=>$list_record_result_res['bank']));
						//给自己发送模板消息
					$this->self_examine_muban($lend_resd['title'],$user_list_openid['name'],$user_list_openid['openid'],$config['loan_template_id'],date('Y-m-d H:i:s',time()));
					if($user_list_openid['leader1']){
						$message="您的直推用户".$user_list_openid['name']."已完成".$lend_resd['title']."产品的申请，下款额度".$application_amount."元恭喜您获得".$list_record_result_res['fan_level1']."元奖励";
						$res=$this->to_examine_muban($message,$lend_resd['title'],$list_record_result_res['fan_level1'],$usre_uid1['openid'],$config['sj_member_template_id']);	
					}
		
					message('修改成功', $this->createWebUrl('netloan', array('op' => 'index')), 'success');
				}

			}else{
				message('数据错误', $this->createWebUrl('netloan', array('op' => 'index')), 'error');
			}
		}
	}


	include $this->template('web/netloan');


?>