<?php
/**
 * Created by PhpStorm.
 * User: jialin
 * Date: 30/11/2017
 * Time: 09:42
 */

trait Utils
{
    public function getClassReflection()
    {
        return new ReflectionClass(current(class_parents(get_called_class())));
    }

    public function dumpClass()
    {
        echo '<pre>';
        print_r($this->getClassReflection()->__toString());

    }

    public function get_group_info_by_id($id, $isUser = false)
	{
	    if($isUser){
	        $user = $this->user2($id);
	        if($user['group'])
	            $id = $user['group'];
	        else
	            return false;
	    }
	    $sql = 'SELECT * FROM ' . tablename('d1sj_card_group') . ' WHERE id = :id';
	    $info = pdo_fetch($sql, [':id' => $id]);

	    return $info;
	}

	public function user2($uid = null)
    {
        global $_W;
        $sql = 'SELECT * FROM ' . tablename('d1sj_card_user') . ' WHERE uid = :id';
        $user = pdo_fetch($sql, [':id' => $uid ?: uid()]);

        return $user;
    }
}