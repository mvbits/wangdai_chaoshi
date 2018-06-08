<?php


require MODULE_ROOT . '/helpers/functions.php';

global $_GPC, $_W;
$op = $_GPC['op'] ?: 'index1';
$op = $op == 'index' ? 'index1' : $op;

$user = user();
$config = config();

check_auth();
// 引入模板
if($op == 'index') {
   // $uniacid = uniacid();
   // $bankSql = 'SELECT * FROM ' . tablename('d1sj_card_bank') . 'WHERE uniacid = :uniacid';
   // $banks = pdo_fetchall($bankSql, [':uniacid' => $uniacid]);
   // $follow = $_W['fans']['follow'];
   // include $this->template('index');
} else if($op == 'index1') {
    $uniacid = uniacid();
    $bankSql = 'SELECT * FROM ' . tablename('d1sj_card_bank') . 'WHERE uniacid = :uniacid';
    $banks = pdo_fetchall($bankSql, [':uniacid' => $uniacid]);
    $follow = $_W['fans']['follow'];

    $where = [
        "uniacid = {$uniacid}",
        "recommend = 2"
    ];
    $sql = 'SELECT `photo`,`title`,`desc`,`id` FROM ' . tablename('d1sj_card_card') . ' WHERE ' . implode(' AND ', $where);
    $cards = pdo_fetchall($sql);

    $banners = get_banner_by_type('card');

    include $this->template('index2');
}
