<?php
namespace App\ADMIN\Controller;
use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;

class Bank extends Controller
{
	public function index(){
		
		$name = Lib::request('name');
		$code_hlb = Lib::request('code_hlb');
		$code_yb = Lib::request('code_yb');
		$ybskb = Lib::request('ybskb');
		$start_date = Lib::request('start_date');
		$end_date = Lib::request('end_date');
		$status = Lib::request('status');
		$bank_type = Lib::request('bank_type');
		
		$condition = null;
		($name) ? $condition ['AND'] ['name'] = $name : null;
		($code_hlb) ? $condition ['AND'] ['code_hlb'] = $code_hlb : null;
		($code_yb) ? $condition ['AND'] ['code_yb'] = $code_yb : null;
		($ybskb) ? $condition ['AND'] ['ybskb'] = $ybskb : null;
		($status) ? $condition ['AND'] ['status'] = $status : null;
		($bank_type) ? $condition ['AND'] ['bank_type'] = $bank_type : null;
		($start_date) ? $condition ['AND'] ['create_time[>=]'] =strtotime($start_date. " 00:00:00")*1000: null;
		($end_date) ? $condition ['AND'] ['create_time[<=]'] =strtotime($end_date. " 23:59:59")*1000 : null;

		
		$pageArr = Lib::setPagePars();
		if ($pageArr['orderField']) {
			$columns['ORDER'] = [
					$pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
			];
		}
	
		$data = $this->M()->getList($pageArr, $condition);
	
		$this->assign("data", $data);
		
		$this->view();
	}
	public function add($act = null)
	{
		if ($act == 'add') {
			$data = [
					'name' => Lib::post('name'),
					'code_hlb' => Lib::post('code_hlb'),
//					'code_yb' => Lib::post('code_yb'),
					'logo' => Lib::post('logo'),
					'back_image' => Lib::post('back_image'),
					'ybskb' => Lib::post('ybskb'),
					'status' => Lib::post('status'),
					'bank_type' => Lib::post('bank_type'),
					'create_time' => Lib::getMs(),
			]; 
			$insertId = $this->M()->add($data);
			if ($insertId) {
				Dwz::successDialog($this->M()->modelName, '', 'closeCurrent');
			}
		}
		$this->view();
	}
	
	
	
	/***
	 * 编辑
	 *
	 */
public function edit($id = 0, $act = null) {
        if ($act == 'edit' && ! empty ( $id )) {
            $data = [
              		'name' => Lib::post('name'),
					'code_hlb' => Lib::post('code_hlb'),
//					'code_yb' => Lib::post('code_yb'),
            		'logo' => Lib::post('logo'),
            		'back_image' => Lib::post('back_image'),
					'ybskb' => Lib::post('ybskb'),
					'status' => Lib::post('status'),
					'bank_type' => Lib::post('bank_type'),
					'create_time' => Lib::getMs(),
            ];
            if ($this->M ()->edit( $id, $data )) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }

        $list = $this->M ()->db->get ( "bank", "*", [
            'id' => $id
        ] );
        // $pid = $list['pid'];
        $this->assign ( "list", $list );
        $this->view ();
    }
	
	
	/****
	 *
	 * 删除
	 */
	public function del($id = 0)
	{
		$del = DBQ::del('bank', [
				'id' => $id
		]);
		if ($del) {
			Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
		} else {
			DWZ::err();
		}
	}
	    /**
     *
     * @name 更新配置文件
     */
    public function updateConfig()
    {
   
        $ret = $this->M()->updateConfig();
       
        if ($ret) {
            Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
        } else {
            Dwz::err();
        }
    }
	
}