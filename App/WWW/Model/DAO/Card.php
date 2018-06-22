<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/5/6
 * Time: 9:27
 */

namespace App\WWW\Model\DAO;

use Core\Base\Model;
use Core\DB\DBQ;

class Card extends Model
{
    public function add (array $data)
    {
       return DBQ::add('yjf_card', $data);
    }

    public function updCard(array $data, array $where)
    {
        return DBQ::upd('yjf_card', $data, $where);
    }

    public function getRow(array $condition, array $fields = [])
    {
        if(empty($fields)) {
            $fields = '*';
        }
        $tmp = [];
        foreach($condition as $key => $value) {
            $tmp['A.'.$key] = $value;
        }
        $condition = $tmp;
        return DBQ::getRow('yjf_card (A)', [
            '[>]yjf_card_channel (B)' => [
                'A.card_no' => 'card_no'
            ]
        ],$fields, $condition);
    }

    public function getList(array $condition, array $fields = [])
    {
        if(empty($fields)) {
            $fields = '*';
        }
        $tmp = [];
        foreach($condition as $key => $value) {
            $tmp['A.'.$key] = $value;
        }

        return DBQ::getAll('yjf_card (A)', [
            '[>]yjf_card_channel (B)' => [
                'A.card_no' => 'card_no'
            ]
        ],$fields, $tmp);
    }
}