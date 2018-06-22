<?php
namespace App\API\V100\Model;
use Core\Base\Model;

class SynBill extends Model
{

    public function add($data){
        $this->insert('bill',$data);
        $insertId = $this->insertID();
        return $insertId;
    }

    public function getList(){
        $data = $this->select ( 'bill', '*', [
            'ORDER' => [
                'id' => 'ASC'
            ]
        ] );
        return $data;
    }



}