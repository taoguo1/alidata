<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/19
 * Time: 15:03
 */
namespace App\ADMIN\Model;
use Core\Lib;
use Core\DB\DBQ;
use Core\Base\Model;
class KcpOrder extends Model
{
	
    public function getList($pageArr = null, $condition = null) {
     $data = DBQ::pages($pageArr,'kcporder','*', $condition);

	return $data;
	}
    public function del($id = 0)
    {
        $rest = DBQ::getRow('kcporder','*',['id' => $id]);
        if(!empty($rest)){
            $NowTime=Lib::getMs();
            $LstTime=$rest['create_time']+300000;
            if( ($rest['status'] !=2) && $LstTime < $NowTime ){
                return $this->delete('kcporder', ['id' => $id]);
            }else{
                return false;
            }
        }else{
            return false;
        }

    }
	

}