<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/7
 * Time: 10:14
 */
namespace App\ADMIN\Model;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Lib;

class CreditHkAmount extends Model
{
    public function getList($type=null, $condition = null,$year=null,$month=null)
    {
        if(empty($month)) {
            if($type == "day") {
                $sql = "SELECT
                    sum(amount) AS totalmoney,
                    FROM_UNIXTIME(
                        `create_time` / 1000,
                        '%m-%d'
                    ) AS shijian
                FROM
                    dzz_bill
                WHERE
                   $condition
                GROUP BY
                    FROM_UNIXTIME(
                        `create_time` / 1000,
                        '%Y-%m-%d'
                    )";
            } else {
                $sql = "SELECT
                    sum(amount) AS totalmoney,
                    FROM_UNIXTIME(
                        `create_time` / 1000,
                        '%m-%w'
                    ) AS shijian
                FROM
                    dzz_bill
                WHERE
                   $condition
                GROUP BY
                    FROM_UNIXTIME(
                        `create_time` / 1000,
                        '%Y-%m-%d %w'
                    )";
            }

        } else {
            $sql = "SELECT
                    sum(amount) AS totalmoney,
                    FROM_UNIXTIME(
                        `create_time` / 1000,
                        '%d-%H'
                    ) AS shijian
                FROM
                    dzz_bill
                WHERE
                   $condition
                GROUP BY
                    FROM_UNIXTIME(
                        `create_time` / 1000,
                        '%Y-%m-%d %H'
                    )";
        }
        $result = $this->db->query($sql);
        $result->setFetchMode(\PDO::FETCH_ASSOC);
        $data =  $result->fetchAll();

        if(empty($month)) {
            if($type == "day") {
                for($i = 1;$i <= 12;$i++) {
                    for($j = 1;$j <= 31;$j++) {
                        $result1['day'][$i][$j] = 0;
                    }
                    $result1['day'][$i]['total'] = 0;
                }

                //算一年的数据 按日算
                for($i  = 1;$i <= 12;$i++ ) {

                    for ($j = 1; $j <= 31; $j++) {
                        $result1['fday'][$j][$i] = 0;
                        foreach ($data as $k=>$v) {
                            $time = explode("-",$v['shijian']);
                            if( $i == $time[0] && $j == $time[1]) {
                                $result1['day'][$i][$j] = $v['totalmoney'];
                                $result1['day'][$i]['total'] += $v['totalmoney'];
                                $result1['fday'][$j][$i] += $v['totalmoney'];
                            }
                        }
                    }
                }

            } else {
                for($i = 1;$i <= 12;$i++) {
                    for($j = 0;$j <= 6;$j++) {
                        $result1['day'][$i][$j] = 0;
                    }
                    $result1['day'][$i]['total'] = 0;
                }

                //算一年的数据 按周算
                for($i  = 1;$i <= 12;$i++ ) {

                    for ($j = 0; $j <= 6; $j++) {

                        $result1['fday'][$j][$i] = 0;
                        foreach ($data as $k=>$v) {
                            $time = explode("-",$v['shijian']);
                            if( $i == $time[0] && $j == $time[1]) {
                                $result1['day'][$i][$j] += $v['totalmoney'];
                                $result1['day'][$i]['total'] += $v['totalmoney'];
                                $result1['fday'][$j][$i] += $v['totalmoney'];

                            }
                        }
                    }
                }
            }

        } else {
            for($i = 1;$i <=Lib::getMonthLastDay($year,$month);$i++) {
                for($j = 0;$j <= 23;$j++) {
                    $result1['day'][$i][$j] = 0;
                }
                $result1['day'][$i]['total'] = 0;
            }

            //算某一月每小时的数据
            for($i  = 1;$i <= Lib::getMonthLastDay($year,$month);$i++ ) {

                for ($j = 0; $j <= 23; $j++) {
                    $result1['fday'][$j][$i] = 0;
                    foreach ($data as $k=>$v) {
                        $time = explode("-",$v['shijian']);
                        if( $i == $time[0] && $j == $time[1]) {
                            $result1['day'][$i][$j] = $v['totalmoney'];
                            $result1['day'][$i]['total'] += $v['totalmoney'];
                            $result1['fday'][$j][$i] += $v['totalmoney'];
                        }
                    }
                }
            }
//            var_dump($result1);
        }


        return $result1;
    }
//    public function search($key)
//    {
//
//        $row = DBQ::getAll('test', [
//            'id',
//            'title'
//        ]);
//        return $row;
//    }


}
