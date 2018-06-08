<?php
require MODULE_ROOT . '/helpers/functions.php';

global $_GPC, $_W;
$op = $_GPC['op'] ?: 'index';

$user = user();
$config = config();
$uniacid = uniacid();
$uid = uid();
$group = $user['group'] ? get_group_info_by_id($user['group']) : false;

check_auth();

if($op == 'index') {
    $id = input('id');
    $sql = 'SELECT * FROM ' . tablename('d1sj_card_bank') . ' WHERE id = :id';
    $bank = pdo_fetch($sql, [':id' => $id]);
    include $this->template('apply');
} else if($op == 'submit') {
    $id = input('id');
    $sql = 'SELECT * FROM ' . tablename('d1sj_card_bank') . ' WHERE id = :id';
    $bank = pdo_fetch($sql, [':id' => $id]);

    $input = input('data');
    $name = $_GPC['data']['name'];
    $mobile = $_GPC['data']['mobile'];
    $data = [
        'uniacid'    => $uniacid,
        'uid'        => $uid,
        'bank'       => $bank['id'],
        'name'       => $input['name'],
        'mobile'     => $input['mobile'],
        'createtime' => time(),

    //  `uniacid` int(10) NOT NULL,
    //  `uid` int(10) NOT NULL COMMENT '申请人ID',
    //  `bank` int(10) NOT NULL COMMENT '银行ID',
    //  `name` varchar(24) DEFAULT NULL,
    //  `certify` varchar(24) DEFAULT NULL,
    //  `mobile` varchar(16) DEFAULT NULL,
    //  `fan_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总佣金',
    //  `fan_uid1` int(10) NOT NULL DEFAULT '0' COMMENT '上一级领导',
    //  `fan_level1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '上一级佣金',
    //  `fan_uid2` int(10) NOT NULL DEFAULT '0' COMMENT '第二级领导',
    //  `fan_level2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '第二级佣金',
    //  `fan_level3` decimal(10,2) NOT NULL DEFAULT '0.00',
    //  `fan_uid3` int(10) NOT NULL DEFAULT '0',
    //  `remote` varchar(16) DEFAULT NULL,
    //  `createtime` int(10) NOT NULL COMMENT '创建时间',
    //  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未返佣 1已返佣',
    ];
    if($user['leader1']) {
        $group1 = get_group_info_by_id($user['leader1'], true);
        $groupLevel1 = $group1 ? $group1['mlevel'] : 0;
    }
    if($user['leader2'] && $groupLevel2 >= 2) {
        $group2 = get_group_info_by_id($user['leader2'], true);
        $groupLevel2 = $group2 ? $group2['mlevel'] : 0;
    }
    $groupSql = 'SELECT * FROM ' . tablename('d1sj_card_group') . ' WHERE uniacid = :uniacid AND id = :id';
    $group = pdo_fetch($groupSql, [':uniacid' => $uniacid, ':id' => $user['group']]);
    //判断当前用户是否存一级推荐人
    if($user['leader1'] && $groupLevel1 >= 1){
        //判断他的一级推荐是什么级别 type 1渠道经理，2团队经理，3城市经理
        //先查询上级用户信息
        $leader1_userinfo=pdo_fetch('select u.id,u.group,g.id,g.type,g.mout as groupmout  from'.tablename('d1sj_card_user')." as u left join ".tablename('d1sj_card_group')." as g on u.group=g.id where u.uniacid=:uniacid and u.uid=:uid ",array(':uniacid'=>$_W['uniacid'],':uid'=>$user['leader1']));
        //判断当前用户是否是会员
        if($group){
            //判断上级用户是渠道经理
            if($leader1_userinfo['type']==1){
                $price = explode('|', $bank['price']);
                $groupMoney_noe=$price[1];
                $data['fan_uid1'] = $user['leader1'];
                $data['fan_level1'] = $groupMoney_noe;
            }else if($leader1_userinfo['type']==2){
                //团队经理
                $price = explode('|', $bank['price']);
                $groupMoney_noe=$price[2];
                $data['fan_uid1'] = $user['leader1'];
                $data['fan_level1'] = $groupMoney_noe;
            }else if($leader1_userinfo['type']==3){
                //团队经理
                $price = explode('|', $bank['price']);
                $groupMoney_noe=$price[3];
                $data['fan_uid1'] = $user['leader1'];
                $data['fan_level1'] = $groupMoney_noe;
            }

        }else{
            //非会员
            $price = explode('|', $bank['price']);
            $groupMoney_noe=$price[0];
            $data['fan_uid1'] = $user['leader1'];
            $data['fan_level1'] = $groupMoney_noe;
        }
    }
    //判断当前用户是否存二级推荐人
    if($user['leader2'] && $groupLevel2 >= 2){
        //判断他的一级推荐是什么级别 type 1渠道经理，2团队经理，3城市经理
        //先查询上级用户信息
        $leader1_userinfo=pdo_fetch('select u.id,u.group,g.id,g.type,g.mout as groupmout  from'.tablename('d1sj_card_user')." as u left join ".tablename('d1sj_card_group')." as g on u.group=g.id where u.uniacid=:uniacid and u.uid=:uid ",array(':uniacid'=>$_W['uniacid'],':uid'=>$user['leader2']));
           //判断当前用户是否是会员
        if($group){
            //判断上级用户是渠道经理
            if($group['type']==1){
                $price = explode('|', $bank['price_tow']);
                $groupMoney_noe=$price[1];
                $data['fan_uid2'] = $user['leader2'];
                $data['fan_level2'] = $groupMoney_noe;
            }else if($group['type']==2){
                //团队经理
                $price = explode('|', $bank['price_tow']);
                $groupMoney_noe=$price[2];
                $data['fan_uid2'] = $user['leader2'];
                $data['fan_level2'] = $groupMoney_noe;
            }else if($group['type']==3){
                //团队经理
                $price = explode('|', $bank['price_tow']);
                $groupMoney_noe=$price[3];
                $data['fan_uid2'] = $user['leader2'];
                $data['fan_level2'] = $groupMoney_noe;
            }

        }else{
            //非会员
            $price = explode('|', $bank['price_tow']);
            $groupMoney_noe=$price[0];
            $data['fan_uid2'] = $user['leader2'];
            $data['fan_level2'] = $groupMoney_noe;
        }
    }
    $data['fan_total']=$data['fan_level1']+$data['fan_level2'];
    $res = pdo_insert('d1sj_card_record', $data);

    // 增加银行卡申请数
    pdo_update('d1sj_card_bank', [
        'num' => $bank['num'] + 1
    ], [
        'id' => $bank['id']
    ]);
    header('Location: ' . $bank['api']);
}
