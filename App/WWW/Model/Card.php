<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/5/5
 * Time: 14:34
 */

namespace App\WWW\Model;


use Core\Lib;
use Core\DB\DBQ;

class Card
{
    protected $dao = null;
    public function __construct ()
    {
        $this->dao = new DAO\Card();
    }

    public function getCard($cardNo)
    {
        return $this->dao->getRow(['card_no' => $cardNo]);
    }

    public function addCard(array $creditCardInfo, array $channelInfo)
    {
        $cardChannelDao = new DAO\CardChannel();
        $creditCardInfo['create_time'] = Lib::getMs();
        $creditCardInfo['is_auth'] = -1;
        $db = DBQ::connect();
        $db->db->pdo->beginTransaction();
        $this->dao->add($creditCardInfo);
        $cardChannelDao->add($channelInfo);
        return $db->db->pdo->commit();
    }

    public function getVerifiedCards($openid)
    {
       $allCards = $this->getAllCards($openid);
       $cards = \array_filter($allCards, function($item){
           return $item['is_auth'] == 1;
       });
       return $cards;
    }

    public function getUnVerifiedCards($openid)
    {
        $allCards = $this->getAllCards($openid);
        $cards = \array_filter($allCards, function($item){
            return $item['is_auth'] == -1;
        });
        return $cards;
    }

    protected function getAllCards($openid)
    {
        static $userAllCards = [];
        if(empty($userAllCards)) {
            $userAllCards = $this->dao->getList(['open_id' => $openid]);
        }
        return $userAllCards;
    }
}