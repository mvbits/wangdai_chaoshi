<?php

 	global $_GPC, $_W;
	$ops = array('index', 'post','delete','status','sort','recommend','is_new'); // 只支持此 3 种操作.
	$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'index';
	if($op=='index'){
		//显示第几页的数据
		$pindex = max(1, intval($_GPC['page']));
		//每页显示的条数
	    $psize = 10;
		//总条数  
	    $count = pdo_fetchcolumn("select count(id) from ".tablename('d1sj_card_lend')." where uniacid=".$_W['uniacid']);
		$list = pdo_fetchall("select * from ".tablename('d1sj_card_lend')." where uniacid=".$_W['uniacid']." order by sort  limit ". ($pindex - 1) * $psize . ',' . $psize);
		$pager = pagination($count, $pindex, $psize);

	}
	if($op=='post'){
		$id=$_GPC['id'];
		$item=pdo_fetch('select * from '.tablename('d1sj_card_lend')."where id=:id and uniacid=:uniacid ",array(':id'=>$id,':uniacid'=>$_W['uniacid']));
		$category=pdo_fetchall('select * from '.tablename('d1sj_card_category')." where status =1 and uniacid=".$_W['uniacid']);
		if (checksubmit()) {
			$category_id=$_GPC['category_id'];
			$category_id=implode(',',$category_id);
			$lilv_type=$_GPC['lilv_type'];
			if($lilv_type==1){
				$daily_interest_rate=$_GPC['interest_rate'];
			}else if($lilv_type==2){
				$annual_interest_rate=$_GPC['interest_rate'];
			}
			if($_GPC['lilv_price']==2){
				$price=$_GPC['fencheng_price'];
			}else if($_GPC['lilv_price']==1){
				$percent_price=$_GPC['fencheng_price'];
			}
			if($_GPC['lilv_price_tow']==1){
				$percent_price_tow=$_GPC['fencheng_price_tow'];
			}else if($_GPC['lilv_price_tow']==2){
				$price_tow=$_GPC['fencheng_price_tow'];
			}
			
			// var_dump($_GPC['lilv_price']);
			// var_dump($price);
			// var_dump($percent_price);die;
			$term_min_start=$_GPC['term_min_start'];
			if($term_min_start==1){
				$day_term_star=$_GPC['term_min'];

			}else if($term_min_start==2){
				$month_term_star=$_GPC['term_min'];
			}
			$term_max_end=$_GPC['term_max_end'];
			if($term_max_end==1){
				$day_term_end=$_GPC['term_max'];
			}else if($term_max_end==2){
				$month_term_end=$_GPC['term_max'];
			}
			$lend_date=array(
				'title' => $_GPC['title'],
				'icon'  => $_GPC['icon'],
				'url' => $_GPC['url'],
				'sort'  =>$_GPC['sort'],
				'uniacid'=>$_W['uniacid'],
				'category_id'=>$category_id,
				'success_rate'=>$_GPC['success_rate'],
				'brief_introduction'=>$_GPC['brief_introduction'],
				'daily_interest_rate'=>$daily_interest_rate,
				'annual_interest_rate'=>$annual_interest_rate,
				'quota_min'=>$_GPC['quota_min'],
				'quota_max'=>$_GPC['quota_max'],
				'month_term_star'=>$month_term_star,
				'month_term_end'=>$month_term_end,
				'day_term_star'=>$day_term_star,
				'day_term_end'=>$day_term_end,
				'audit_method'=>$_GPC['audit_method'],
				'audit_cycle'=>$_GPC['audit_cycle'],
				'lending_time'=>$_GPC['lending_time'],
				'repayment_method'=>$_GPC['repayment_method'],
				'application_process'=>$_GPC['application_process'],
				'application_number'=>$_GPC['application_number'],
				'application_conditions'=>$_GPC['application_conditions'],
				'detailed_introduction'=>$_GPC['detailed_introduction'],
				'trait'=>$_GPC['trait'],
				'recommend'=>$_GPC['recommend'],
				'is_new'=>$_GPC['is_new'],
				'price'=>$price,
				'price_tow'=>$price_tow,
				'percent_price'=>$percent_price,
				'percent_price_tow'=>$percent_price_tow,


			);
			if(!$id){
				$lend_res=pdo_insert('d1sj_card_lend',$lend_date);
				if($lend_res){
					message('添加成功',$this->createWebUrl('lend',array('op'=>'index')),'success');
				}
			}else{
				$lend_res=pdo_update('d1sj_card_lend',$lend_date,array('id'=>$id));
				if($lend_res){
					message('修改成功',$this->createWebUrl('lend',array('op'=>'index')),'success');
				}
			}
		}
	}
	if($op=='delete'){
		$id=$_GPC['id'];
		$lend_res=pdo_delete('d1sj_card_lend',array('id'=>$id));
		if($lend_res){
			message('删除成功',$this->createWebUrl('lend',array('op'=>'index')),'success');
		}

	}

	if($op=='sort'){
		if($_W['isajax']&&$_GPC['sort']){
			$id=$_GPC['id'];
			$sort=$_GPC['sort'];
			$list=pdo_fetch('select * from '.tablename('d1sj_card_lend')." where id=:id",array(':id'=>$id));
			if($list){
					$data=array(
						'sort'=>$sort,
					);
					pdo_update('d1sj_card_lend',$data,array('id'=>$id));
				die(json_encode(array('info'=>1,'sort'=>$sort)));
			}else{
				die(json_encode(array('info'=>2,'msg'=>'数据错误')));
			}

		}
	}
	if($op=='status'){
			if($_W['isajax']&&$_GPC['status']){
				$id=$_GPC['id'];
				$status=$_GPC['status'];
				$list=pdo_fetch('select * from '.tablename('d1sj_card_lend')." where id=:id",array(':id'=>$id));
				if($list){
					if($status==1){
						$status=0;
						$data=array(
							'status'=>$status,
						);
						pdo_update('d1sj_card_lend',$data,array('id'=>$id));
					}else{
						$status=1;
						$data=array(
							'status'=>$status,
						);
						pdo_update('d1sj_card_lend',$data,array('id'=>$id));
					}
					die(json_encode(array('info'=>1,'status'=>$status)));
				}else{
					die(json_encode(array('info'=>2,'msg'=>'数据错误')));
				}

			}
	}


	include $this->template('web/lend');

?>