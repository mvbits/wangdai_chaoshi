<?php
    global $_GPC, $_W;
    $ops = array('index', 'info','delete','confirm','reject'); // 只支持此 3 种操作.
    $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'index';
    if($op == 'index'){
        //显示第几页的数据
        $pindex = max(1, intval($_GPC['page']));
        //每页显示的条数
        $psize = 10;
        //总条数  
        $count = pdo_fetchcolumn("select count(id) from ".tablename('d1sj_card_loan')." where uniacid = ".$_W['uniacid']);
        $list = pdo_fetchall("select * from ".tablename('d1sj_card_loan')." where uniacid = ".$_W['uniacid']." order by id desc  limit ". ($pindex - 1) * $psize . ',' . $psize);
        $pager = pagination($count, $pindex, $psize);
    }

    if($op=='delete'){
        $id=$_GPC['id'];
        $group_res=pdo_delete('d1sj_card_loan',array('id'=>$id));
        if($group_res){
            message('删除成功',$this->createWebUrl('loan',array('op'=>'index')),'success');
        }

    }
    if($op=='confirm'){
        $id=$_GPC['id'];
        $loan_data=array(
            'status'=>2,
        );
        $loan_result=pdo_update('d1sj_card_loan',$loan_data,array('id'=>$id));
        if($loan_result){
            //发送模板消息
            $loan_list=pdo_fetch('select uid from '.tablename('d1sj_card_loan')." where id=:id",array(':id'=>$id));
            $usre_list=pdo_fetch('select uid,openid,name from '.tablename('d1sj_card_user')." where uid=:uid and uniacid=:uniacid ",array(':uid'=>$loan_list['uid'],':uniacid'=>$_W['uniacid']));
            //线下申请通过模板消息
            $config=$this->config();
            $resutl=$this->web_loan_muban($usre_list['name'],$usre_list['openid'],$config['loan_template_id'],date('Y-m-d H:i:s',time()));
            message('修改成功', $this->createWebUrl('loan', array('op' => 'index')), 'success');
        }
    }
    if($op=='reject'){
        $id=$_GPC['id'];
        $loan_data=array(
            'status'=>3,
        );
        $loan_result=pdo_update('d1sj_card_loan',$loan_data,array('id'=>$id));
        if($loan_result){
             message('修改成功', $this->createWebUrl('loan', array('op' => 'index')), 'success');
        }
    }
    if($op=='info'){
        $id=$_GPC['id'];
        $list=pdo_fetch('select l.*,u.name as username from '.tablename('d1sj_card_loan')." as l left join ".tablename('d1sj_card_user')." as u on l.recommend_id=u.uid where l.id=:id ",array(':id'=>$id));
    }


    include $this->template('web/loan');
?>