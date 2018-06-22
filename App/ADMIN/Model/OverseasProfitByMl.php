<?php
namespace App\ADMIN\Model;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;

class OverseasProfitByMl  extends Model
{
    public function getList($pageArr = null, $condition = null) {
        $sql = "SELECT A.* FROM dzz_myr_profit A" . $condition;
        $data = DBQ::origPage($pageArr, $sql);
        return $data;
    }

    /**
     * 获取所有商户
     */
    public function  oemList($pageArr = null,$condition=null)
    {
        $sql = "SELECT A.* FROM dzz_merc A " . $condition;
        $data = DBQ::origPage($pageArr, $sql);
        return $data;
    }
}