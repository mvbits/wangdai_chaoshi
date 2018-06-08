<?php

 	global $_GPC, $_W;
	$ops = array('index', 'post','delete','status'); // 只支持此 3 种操作.
	$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'index';
	if($op=='index'){
		//显示第几页的数据
		$pindex = max(1, intval($_GPC['page']));
		//每页显示的条数
	    $psize = 10;
		//总条数  
	    $count = pdo_fetchcolumn("select count(id) from ".tablename('d1sj_card_category')." where uniacid=".$_W['uniacid']);
		$list = pdo_fetchall("select * from ".tablename('d1sj_card_category')." where uniacid=".$_W['uniacid']." order by id desc  limit ". ($pindex - 1) * $psize . ',' . $psize);
		$pager = pagination($count, $pindex, $psize);

	}
	if($op == 'post'){
        $id = $_GPC['id'];
        if(checksubmit()){
            $data = $_GPC['data'];
            if(!$id){
                $data['uniacid'] = $_W['uniacid'];
                if(pdo_insert('d1sj_card_category', $data)){
                    message('添加成功', $this->createWebUrl('category'));
                }
            }else{
   
                if(pdo_update('d1sj_card_category', $data, ['id' => $id])){
                    message('修改成功', $this->createWebUrl('category'));
                }
            }

            message('操作失败', $this->createWebUrl('category'));
        }
        if($id){
            $item = pdo_fetch('select * from ' . tablename('d1sj_card_category') . ' where id = :id', [':id' => $id]); 
        }
       if($op=='delete'){
			$category_res=pdo_delete('d1sj_card_category',array('id'=>$id));
			if($category_res){
				message('删除成功',$this->createWebUrl('category',array('op'=>'index')),'success');
			}

		}
    }

	if($op=='status'){
			if($_W['isajax']&&$_GPC['status']){
				$id=$_GPC['id'];
				$status=$_GPC['status'];
				$list=pdo_fetch('select * from '.tablename('d1sj_card_category')." where id=:id",array(':id'=>$id));
				if($list){
					if($status==1){
						$status=0;
						$data=array(
							'status'=>$status,
						);
						pdo_update('d1sj_card_category',$data,array('id'=>$id));
					}else{
						$status=1;
						$data=array(
							'status'=>$status,
						);
						pdo_update('d1sj_card_category',$data,array('id'=>$id));
					}
					die(json_encode(array('info'=>1,'status'=>$status)));
				}else{
					die(json_encode(array('info'=>2,'msg'=>'数据错误')));
				}

			}
	}
	#删除
	if($op=='delete'){
		$id=$_GPC['id'];
		$record_delete=pdo_delete('d1sj_card_category',array('id'=>$id));
		if($record_delete){
			message('删除成功', $this->createWebUrl('category', array('op' => 'index')), 'success');
		}
	}







include $this->template('web/category');
?>