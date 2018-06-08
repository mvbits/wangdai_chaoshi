<?php
require MODULE_ROOT . '/helpers/functions.php';
global $_GPC, $_W;
$ops = array('index', 'export','confirm','refuse'); // 只支持此 3 种操作.
$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'index';
if($op=='index'){
	//显示第几页的数据
	$pindex = max(1, intval($_GPC['page']));
	//每页显示的条数
    $psize = 10;
	//总条数  
    $count = pdo_fetchcolumn("select count(id) from ".tablename('d1sj_card_cash')." where uniacid=".$_W['uniacid']);
	$list = pdo_fetchall("select * from ".tablename('d1sj_card_cash')." where uniacid=".$_W['uniacid']." order by id desc  limit ". ($pindex - 1) * $psize . ',' . $psize);
	$pager = pagination($count, $pindex, $psize);
}
if($op=='export'){
	if (checksubmit()) {
		$status=$_GPC['status'];
		$start_date=strtotime($_GPC['start_date']);
		$end_date  = strtotime($_GPC['end_date']);
		if($status==0){
			$cash_result=pdo_fetchall('select ca.*,me.nickname from '.tablename('d1sj_card_cash')
			." as ca left join ".tablename('mc_members')
			." as me on ca.uid=me.uid "
			." where ca.uniacid=:uniacid"
			,array(':uniacid'=>$_W['uniacid']));
		}else{
			$cash_result=pdo_fetchall('select ca.*,me.nickname from '.tablename('d1sj_card_cash')
			." as ca left join ".tablename('mc_members')
			." as me on ca.uid=me.uid "
			." where ca.status=:status and ca.uniacid=:uniacid and ca.createtime between :start_date and :end_date"
			,array(':status'=>$status,':uniacid'=>$_W['uniacid'],':start_date'=>$start_date,':end_date'=>$end_date));
		}
		if(!$cash_result){
			message('对不起找到相应的数据',$this->createWebUrl('cash',array('op'=>'index')),'error');
		}
		if($cash_result){
	        /* 输入到CSV文件 */
	        $html = "\xEF\xBB\xBF";
	        /* 输出表头 */
	        $filter = array(
	            'nickname' => '用户昵称',
	            'money' => '提现金额',
	            'status' => '状态',
	            // 'alipay' => '账号',
	            'mobile' => '电话',
	            'alipay' => '支付宝账号',
	            'name' => '真实姓名',
	            'createtime' => '创建时间',
            );
	        foreach ($filter as $key => $title) {
	            $html .= $title . "\t,";
	        }
	        $html .= "\n";
	        foreach ($cash_result as $k => $v) {
	            $shuju[$k]['nickname'] = $v['name'];
	            $shuju[$k]['money'] = $v['money']; 
	            if($v['status'] == 1){
	                $shuju[$k]['status'] = '未处理';
	            }else if($v['status'] == 2){
	                $shuju[$k]['status'] = '已经转款';
	            }else{
	            	$shuju[$k]['status'] = '被拒绝';
	            }
                // $shuju[$k]['alipay'] = $v['alipay'];
                $shuju[$k]['mobile'] = $v['mobile'];
                $shuju[$k]['alipay'] = $v['alipay'];
                $shuju[$k]['name'] = $v['name'];
                $shuju[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
	            foreach ($filter as $key => $title) {
	                $html .= $shuju[$k][$key] . "\t,";
	            }
	            $html .= "\n";
	        }
	        /* 输出CSV文件 */
	        header("Content-type:text/csv");
	        header("Content-Disposition:attachment; filename=提现记录.csv");
	        echo $html;
	        exit();
		}
	}
}

#修改记录状态
if($_GPC['id']){
	$id=$_GPC['id'];
	$cash_res=pdo_fetch('select id,status,uid,money,createtime from'.tablename('d1sj_card_cash')." where id=:id",array(':id'=>$id));
		


	if(!$cash_res){
		message('没有对应的数据', $this->createWebUrl('cash', array('op' => 'index')), 'error');
	}
	// #确认打款
	if($op=='confirm'){
		$cash_date=array(
			'status'=>2,
		);
		$user_res=pdo_fetch('select id,uid,money,openid,name from '.tablename('d1sj_card_user')." where uid=:uid and uniacid=:uniacid",array(':uid'=>$cash_res['uid'],':uniacid'=>$_W['uniacid']));
		$cash_result=pdo_update('d1sj_card_cash',$cash_date,array('id'=>$id));
		if($cash_result){
			$config = config();
			//确认打款发送模板消息
			$time=date('Y-m-d H:i:s',$cash_res['createtime']);
			$res=$this->tixian_muban($user_res['name'],date('Y-m-d H:i:s',time()),$user_res['openid'],$config['tx_template_id'],$cash_res['money']);
			message('修改成功',$this->createWebUrl('cash', array('op'=>'index')), 'success');
		}
	}
	// #拒绝申请
	if($op=='refuse'){
		$cash_date=array(
			'status'=>3,
		);
		//拒接申请给用户添加余额
		$user_res=pdo_fetch('select id,uid,money,openid,name from '.tablename('d1sj_card_user')." where uid=:uid and uniacid=:uniacid",array(':uid'=>$cash_res['uid'],':uniacid'=>$_W['uniacid']));
		$up_user_money=$user_res['money']+$cash_res['money'];
		$user_date=array(
			'money'=>$up_user_money,
		);
		$user_result=pdo_update('d1sj_card_user',$user_date,array('id'=>$user_res['id']));
		$cash_result=pdo_update('d1sj_card_cash',$cash_date,array('id'=>$id));
		if($cash_result&&$user_result){
			message('修改成功',$this->createWebUrl('cash',array('op'=>'index')),'success');
		}
	}
}







include $this->template('web/cash');


?>