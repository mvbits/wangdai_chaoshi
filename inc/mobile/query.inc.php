<?php
require MODULE_ROOT . '/helpers/functions.php';

global $_GPC, $_W;
$op = $_GPC['op'] ?: 'index';
$config = config();

check_auth();
if($op == 'index') {
    $banners = get_banner_by_type('card');
    $banks = pdo_fetchall('select * from ' . tablename('d1sj_card_bank') . ' where uniacid = :id', [':id' => $_W['uniacid']]);
    include $this->template('query');
}