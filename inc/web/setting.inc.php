<?php
global $_W, $_GPC;
$setting=pdo_fetch('select * from '.tablename('d1sj_card_setting')." where uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
$config = unserialize($setting['config']);
if (checksubmit()) {
	$id=$_GPC['id'];
	$data=array(
		'invite'=>$_GPC['invite'],
		'contract_noe'=>htmlspecialchars_decode($_GPC['contract_noe']),
		'contract_tow'=>htmlspecialchars_decode($_GPC['contract_tow']),
		'contract_three'=>htmlspecialchars_decode($_GPC['contract_three']),
		'contract_four'=>htmlspecialchars_decode($_GPC['contract_four']),
		'config'=>serialize($_GPC['config']),
		'raiders'=>$_GPC['raiders'],
		'uniacid'=>$_W['uniacid'],
	);
	if(empty($id)){
		$res=pdo_insert('d1sj_card_setting',$data);
		if($res){
			message('数据添加成功', $this->createWebUrl('setting'), 'success');
		}
	}else{
		$res=pdo_update('d1sj_card_setting',$data,['id'=>$id]);

		if($_GPC['config']['bg_data']||$_GPC['config']['bg_qrcode']||$_GPC['config']['bg_avatar']){
			//删除文件
			$url='../addons/wangdai_chaoshi/attach/card/';
			$this->delFile($url);
		}
	
	
		if($res){
			message('数据更新成功', $this->createWebUrl('setting'), 'success');
		}
		

	}



}


include $this->template('web/setting');

?>