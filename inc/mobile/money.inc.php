<?php
require MODULE_ROOT . '/helpers/functions.php';
global $_GPC, $_W;
$op = $_GPC['op'] ?: 'index';

$user = user();
$config = config();

check_auth();

if($op == 'index') {
    include $this->template('makeMoney');
}