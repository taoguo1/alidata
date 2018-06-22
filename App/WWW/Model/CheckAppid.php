<?php
namespace App\WWW\Model;
use Core\Base\Model;
use Core\Lib;
use Core\DB\DBQ;

class CheckAppid extends Model
{
    public function checkappid($appid){

        return DBQ::getRow('merc',['appid','app_name'],['appid'=>$appid]);

    }

}