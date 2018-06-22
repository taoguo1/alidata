<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/5/7
 * Time: 17:01
 */

namespace App\WWW\Model\DAO;


use Core\Base\Model;
use Core\DB\DBQ;

class CardChannel extends Model
{
    public function add($data)
    {
        return DBQ::add('yjf_card_channel',$data);
    }
}