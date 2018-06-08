<?php
require MODULE_ROOT . '/helpers/functions.php';

global $_GPC, $_W;
$op = $_GPC['op'] ?: 'index';

$uniacid = uniacid();

check_auth();

$user = user();
$config = config();
if($op == 'index') {
    $banks = pdo_fetchall('select * from ' . tablename('d1sj_card_bank') . ' where uniacid = :id', [':id' => $_W['uniacid']]);
    $where = [
        "uniacid = {$uniacid}",
    ];
    $sql = 'SELECT `photo`,`title`,`desc`,`id` FROM ' . tablename('d1sj_card_card') . ' WHERE ' . implode(' AND ', $where);
    $cards = pdo_fetchall($sql);
// 引入模板
    include $this->template('bank');
}
