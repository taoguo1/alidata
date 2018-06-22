<?php
namespace Core\Base;

use Core\Core;
use Core\DB\DB;
use PDO;

class Model
{

    public $model;

    public $modelName;

    protected $table = null;

    public $db;

    protected static $dbConfig = [];

    public function __construct()
    {
        
        // 获取数据库表名
        if (! $this->table) {
            // 获取模型类名称
            $this->model = get_class($this);
            $modelArr = explode("\\", $this->model);
            $this->modelName = $modelArr[count($modelArr) - 1];
            // 删除类名最后的 Model 字符
            // $this->model = substr($this->model, 0, - 5);
            // 数据库表名与类名一致
            $this->table = strtolower($this->model);
        }
        $this->db = new DB([
            // 必须配置项
            'databaseType' => self::$dbConfig['databaseType'],
            'databaseName' => self::$dbConfig['dbName'],
            'server' => self::$dbConfig['host'],
            'userName' => self::$dbConfig['userName'],
            'password' => self::$dbConfig['password'],
            'charSet' => self::$dbConfig['charSet'],
            'debugMode' => self::$dbConfig['debugMode'],
            'logging' => true,
            'port' => self::$dbConfig['port'],
            'prefix' => self::$dbConfig['prefix'],
            'option' => [
                PDO::ATTR_CASE => PDO::CASE_NATURAL
            ],
            'command' => [
                'SET SQL_MODE=ANSI_QUOTES'
            ]
        ]);
        
    }
    
 
    public static function setDbConfig($config)
    {
        self::$dbConfig = $config;
    }

   

    public function getUrl($c, $m = 'index', $pars = '')
    {
        Core::getUrl($c, $m, $pars);
    }

    public function select($table, $join = null, $columns = null, $where = null)
    {
        $list = $this->db->select($table, $join, $columns, $where);
        $this->debug();
        return $list;
    }

    public function insert($table, $datas)
    {
        $insert = $this->db->insert($table, $datas);
        $this->debug();
        return $insert;
    }

    public function insertID()
    {
        $insertid = $this->db->id();
        $this->debug();
        return $insertid;
    }

    public function has($table, $join, $where = null)
    {
        return $this->db->has($table, $join, $where);
    }

    public function delete($table, $where)
    {
        $delete = $this->db->delete($table, $where);
        $this->debug();
        return $delete;
    }

    public function update($table, $data, $where = null)
    {
        $update = $this->db->update($table, $data, $where);
        $this->debug();
        return $update->rowCount();
    }

    public function get($table, $join = null, $columns = null, $where = null)
    {
        return $this->db->get($table, $join, $columns, $where);
    }

    public function count($table, $join = null, $column = null, $where = null)
    {
        $count = $this->db->count($table, $join, $column, $where);
        $this->debug();
        return $count;
    }

    public function replace($table, $columns, $search = null, $replace = null, $where = null)
    {
        return $this->db->replace($table, $columns, $search, $replace, $where);
    }

    public function max($table, $join, $column = null, $where = null)
    {
        return $this->db->max($table, $join, $column, $where);
    }

    public function min($table, $join, $column = null, $where = null)
    {
        return $this->db->min($table, $join, $column, $where);
    }

    public function avg($table, $join, $column = null, $where = null)
    {
        return $this->db->avg($table, $join, $column, $where);
    }

    public function sum($table, $join, $column = null, $where = null)
    {
        return $this->db->sum($table, $join, $column, $where);
    }

    public function action($actions)
    {
        return $this->db->action($actions);
    }
    public function error(){
        return $this->db->error();
    }
    public function info(){
        return $this->db->info();
    }


    public function origPage($pageArr, $sql)
    {
        //需要替换
        //$pattern = "/SELECT (.*) FROM (.*)/U";
        //$sqlCount = preg_replace($pattern,'SELECT count(1) FROM ${2}',$sql);
        $pageNum = $pageArr['pageNum'];
        $numPerPage = $pageArr['numPerPage'];
        $orderField = $pageArr['orderField'];
        $orderDirection = $pageArr['orderDirection'];
        //$totalCount = $this->db->pdo->query($sqlCount)->fetchColumn();
        $totalCount = $this->db->pdo->query($sql)->rowCount();
        
        $pageCount = ceil($totalCount / $numPerPage);
        $pageNum = ($pageNum <= 1) ? 1 : $pageNum;
        $pageNum = ($pageNum >= $pageCount) ? $pageCount : $pageNum;
        $isFirstPage = ($pageNum <= 1) ? 1 : 0;
        $isLastPage = ($pageNum >= $pageCount) ? 1 : 0;
        $start = ($pageNum - 1) * $numPerPage;
        $isOnePage = 0;
        if ($isFirstPage == 1 && $isLastPage == 1) {
            $isOnePage = 1;
        }
        if($start<=0){$start=0;}

        //add by hqf 2018-03-07 添加排序
        if (!empty($orderField) && !empty($orderDirection))$sql .= " ORDER BY ".$orderField." ".$orderDirection;

        $sql .= " LIMIT $start,$numPerPage";
        $res = $this->db->pdo->prepare($sql);
        $res->execute();
        $list = $res->fetchAll(PDO::FETCH_ASSOC);
        if (empty($list)) {
            $list = [];
        }
        $array = [
            'pageNum' => $pageNum,
            'numPerPage' => $numPerPage,
            'totalCount' => $totalCount,
            'pageCount' => $pageCount,
            'orderDirection' => $orderDirection,
            'orderField' => $orderField,
            'isFirstPage' => $isFirstPage,
            'isLastPage' => $isLastPage,
            'isOnePage' => $isOnePage,
            'list' => $list
        ];
        
        return $array;

    }

    public function page($pageArr, $table, $join, $columns = null, $where = null)
    {
        $pageNum = $pageArr['pageNum'];
        $numPerPage = $pageArr['numPerPage'];
        $orderField = $pageArr['orderField'];
        $orderDirection = $pageArr['orderDirection'];
        if ($orderField) {
            if ($where) {
                $where['ORDER'] = [
                    $orderField => strtoupper($orderDirection)
                ];
            } else {
                $columns['ORDER'] = [
                    $orderField => strtoupper($orderDirection)
                ];
            }
        }

        if($join){
            $totalCount = $this->count($table, $join,'*',$where);
        }else{
            $totalCount = $this->count($table, '*','*',$where);
        }

        // $totalCount = $this->count($table, $join, $columns, $where);
        $pageCount = ceil($totalCount / $numPerPage);
        $pageNum = ($pageNum <= 1) ? 1 : $pageNum;
        $pageNum = ($pageNum >= $pageCount) ? $pageCount : $pageNum;
        $isFirstPage = ($pageNum <= 1) ? 1 : 0;
        $isLastPage = ($pageNum >= $pageCount) ? 1 : 0;
        $start = ($pageNum - 1) * $numPerPage;
        $isOnePage = 0;
        if ($isFirstPage == 1 && $isLastPage == 1) {
            $isOnePage = 1;
        }
        if($start<=0){$start=0;}
        if ($where) {
            $where['LIMIT'] = [
                $start,
                $numPerPage
            ];
        } else {
            $columns['LIMIT'] = [
                $start,
                $numPerPage
            ];
        }

        $list = $this->select($table, $join, $columns, $where);
        if (empty($list)) {
            $list = [];
        }
        $array = [
            'pageNum' => $pageNum,
            'numPerPage' => $numPerPage,
            'totalCount' => $totalCount,
            'pageCount' => $pageCount,
            'orderDirection' => $orderDirection,
            'orderField' => $orderField,
            'isFirstPage' => $isFirstPage,
            'isLastPage' => $isLastPage,
            'isOnePage' => $isOnePage,
            'list' => $list
        ];

        return $array;
    }

    public function last(){
        return 'SQL:'.$this->db->last();
    }

    public function debug()
    {
        if(self::$dbConfig['debugMode'])
        {
            $err = $this->db->error();
            if($err[1]) {
                echo "<hr style='margin:0;height:1px;background:#ff0000;color:#ff0000;'>";
                echo '<p style="color:#ffffff;background:#4682B4;font-size:18px;padding:5px 10px;"><strong>errCode：</strong>'.$err[1].'&nbsp;&nbsp;<strong>errMsg：</strong>'.$err[2].'</p>';
                echo "<hr style='margin:0;height:1px;background:#ff0000;color:#ff0000;'>";
                echo '<p style="color:#ffffff;background:#4682B4;font-size:14px;padding:5px 10px;"><strong>SQL：</strong>'.$this->db->last().'</p>';
                echo "<hr style='margin:0;height:1px;background:#ff0000;color:#ff0000;'>";
            }

        }
    }

}