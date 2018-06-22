<?php
namespace App\ADMIN\Model;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;

class OverseasBill  extends Model
{
    public function getList($pageArr = null, $condition = null) {
        $data = DBQ::pages ( $pageArr, 'jwpay_no', '*', $condition );
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