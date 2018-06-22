<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/5/4
 * Time: 08:44
 */
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;

class ConsumeCount extends Controller
{
    public function index()
    {

        $type = Lib::request ( 'type' );
        $year = Lib::request ( 'year' );
        $month = Lib::request ( 'month' );
        $condition = '1=1 AND `bill_type` = 2';

        $type = ($type) ? $type : "day";
        ($year) ? $year= $year : $year= date("Y");
        if(!empty($month)){
            $daystart=strtotime($year."-".$month."-1"." 00:00:00")."000";
            $dayend=strtotime($year."-".$month."-".Lib::getMonthLastDay($year,$month)." 23:59:59")."999";
            $condition .= " AND (
                            `create_time` BETWEEN ".$daystart."
                            AND ".$dayend."
                        )";
        }else{
            $daystart=strtotime($year."-1-1"." 00:00:00")."000";
            $dayend=strtotime($year."-12-31"." 23:59:59")."999";
            $condition .= " AND (
                                `create_time` BETWEEN ".$daystart."
                                AND ".$dayend."
                            )";
        }

        $data = $this->M()->getList ($type,$condition,$year,$month);
        $this->assign ( "data", $data );
        $this->assign ( "year", $year );
        $this->view();
    }
}
