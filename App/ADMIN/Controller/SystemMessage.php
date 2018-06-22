<?php
namespace App\ADMIN\Controller;
use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;
use Core\Extend\Session;
class SystemMessage extends Controller {
    public function index()
    {
    	$dictionaryData     = Lib::loadFile('Config/Dictionary.php');
        $title = Lib::request('title'); 
//      $user_type = Lib::request('user_type');   
        $type = Lib::request('type');  
        $read_unread = Lib::request('read_unread');      
        $condition = null;
        ($title) ? $condition['AND']['title'] = $title : null;   
//      ($user_type) ? $condition['AND']['user_type'] = $user_type : null;  
        ($type) ? $condition['AND']['type'] = $type : null;  
        ($read_unread) ? $condition['AND']['read_unread'] = $read_unread : null;  
        $condition ['ORDER'] = [
            'id' => 'ASC'
        ];
        $pageArr = Lib::setPagePars();
        if ($pageArr['orderField']){
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }
        $data = $this->M()->getList($pageArr, $condition);       
        $this->assign('user_type',$dictionaryData['user_type']);      
        $this->assign('type',$dictionaryData['type']);
        $this->assign('read_unread',$dictionaryData['read_unread']);
        $this->assign("data", $data);
        $this->view();
    }

    public function add($act=null){    
        if ($act == 'add') {
			$insertId=$this->M()->add();
			if ($insertId) {
				Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
			} else {
				Dwz::err ();
			}
		}
        $this->view ();
    }

    public function del($id = 0) {
        if ($this->M ()->del( $id )) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName), $this->M()->modelName );
        } else {
            Dwz::err ();
        }
    }

    public function delAll() {
        $ids = explode ( ',', Lib::post ( 'ids' ) );
        if ($this->M ()->delAll ( $ids )) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
        } else {
            Dwz::err ();
        }
    }

    public function edit($id = 0, $act = null) {    	 
        if ($act == 'edit' && ! empty ( $id )) {
            if ($this->M ()->edit ($id)) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }
        $list = DBQ::getRow('system_message (A)', [
            '[>]user (B)' => [
                'A.uid' => 'id'
            ]
        ], [
            'A.id',
            'A.uid',
            'A.user_type',
            'A.status',
            'A.type',
            'A.read_unread',
            'A.title',
            'A.describe',
            'A.content',
            'A.create_time',
            'B.real_name',
        ],[
            'A.id' => $id
        ]);
        $this->assign('list', $list);
        $this->view();

    }
    //选择推送

    public function oemContact() {
        $content_id = Lib::request('id');//要推送文章id
        $appid = Lib::request('appid');
        $app_name = Lib::request('app_name');
        $status = Lib::request('status');
        $start_date = Lib::request('start_date');
        $end_date = Lib::request('end_date');

        $condition = " WHERE 1";
        if ($appid) {
            $condition .= " and appid like '%" . $appid . "%'";
        }
        if ($app_name) {
            $condition .= " and app_name like '%" . $app_name . "%'";
        }
        if ($status) {
            $condition .= " and status = '" . $status . "'";
        }

        if ($start_date || $end_date) {
            $condition .= " and A.create_time between " . (strtotime($start_date)) * 1000 . " and " . (strtotime($end_date)) * 1000;
        }
        //推送过的不查询
        $del=DBQ::getAll('push','*',['content_id'=>$content_id ]);
        if(!empty($del)){
            $str='';

            foreach($del as $k=>$v){
                if(count($del)==1){
                    $str="'".$v['appid']."'";
                }else{
                   $str.="'".$v['appid']."',";  
                }
               
            }
            $condition .= " and A.appid not in (".$str.')' ;
        }
        

        $pageArr = Lib::setPagePars();
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }

        $data = $this->M()->oemList($pageArr, $condition);
        $this->assign('id',$content_id);
        $this->assign("data", $data);
        $this->view();
    }
    
    public function addagent($act=null){
        //获取addagent
        $addagent= substr($act, 0, -1); 
        //获取文章id
        $content_id=substr($act, -1, 1);
        //要推送的商户appid 
        $appid = explode (',',Lib::post ( 'ids' ) );
        $data=[];
        for($i=0;$i<count($appid);$i++){
            $data[]=['appid'=>$appid[$i],'content_id'=>$content_id,'create_time'=>lib::getMs()];
        }
        if ($addagent == 'addagent') {
			$insertId=$this->M()->addagent($data);
			if ($insertId) {
				Dwz::successDialog ($this->M ()->modelName, '', 'closeCurrent' );
			} else {
				Dwz::err ();
			}
		}
        $this->view();
    }
}