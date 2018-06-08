<?php
    global $_W, $_GPC;
    if(!$operation = $_GPC['op']){
        $operation = 'index';
    }
    if($operation == 'index'){
        $params = [
            ':uniacid' => $_W['uniacid']
        ];
        $total = pdo_fetchcolumn('select count(id) from '.tablename('d1sj_card_bank').' where uniacid = :uniacid', $params);
        $page = max($_GPC['page'], 1);
        $psize = 10;
        $pager = pagination($total, $page, $psize);

        $limit = ($page - 1)*$psize . ',' . $psize;
        $list = pdo_fetchall('select * from '.tablename('d1sj_card_bank').' where uniacid = :uniacid order by orderby limit ' . $limit, $params);
    }
    if($operation == 'post'){
        $id = $_GPC['id'];
        if(checksubmit()){
            $data = $_GPC['data'];
            if(!$id){
                $data['uniacid'] = $_W['uniacid'];
                if(pdo_insert('d1sj_card_bank', $data)){
                    message('添加成功', $this->createWebUrl('bank'));
                }
            }else{
                if(pdo_update('d1sj_card_bank', $data, ['id' => $id])){
                    message('修改成功', $this->createWebUrl('bank'));
                }
            }

            message('操作失败', $this->createWebUrl('bank'));
        }
        if($id){
            $item = pdo_fetch('select * from ' . tablename('d1sj_card_bank') . ' where id = :id', [':id' => $id]);
        }

    }
    if($operation=='delete'){
        $id=$_GPC['id'];
        $group_res=pdo_delete('d1sj_card_bank',array('id'=>$id));
        if($group_res){
            message('删除成功',$this->createWebUrl('bank',array('op'=>'index')),'success');
        }

    }

    include $this->template('web/bank');
?>