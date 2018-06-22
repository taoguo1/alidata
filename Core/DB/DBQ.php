<?php
namespace Core\DB;

use Core\Base\Model;

class DBQ
{
    public static $db;
    public static function connect(){
        if(is_null(self::$db)){
            self::$db = new Model();
        }
        return self::$db;
    }

    /**
     *兼容原来的用法
     */
    public static function getAll($table, $join = null, $columns = null, $where = null)
    {
        return self::connect()->select($table, $join, $columns, $where);
    }

    public static function add($table, $datas)
    {
        return self::connect()->insert($table, $datas);
    }

    public static function isHas($table, $join, $where = null)
    {
        return self::connect()->has($table, $join, $where);
    }

    public static function del($table, $where)
    {
        return self::connect()->delete($table, $where);
    }

    public static function upd($table, $data, $where = null)
    {
        return self::connect()->update($table, $data, $where);
    }

    public static function getRow($table, $join = null, $columns = null, $where = null)
    {
        return self::connect()->get($table, $join, $columns, $where);
    }

    public static function getOne($table, $join = null, $columns = null, $where = null)
    {
        return self::connect()->get($table, $join, $columns, $where);
    }

    public static function getCount($table, $join = null, $column = null, $where = null)
    {
        return self::connect()->count($table, $join, $column, $where);
    }

    public static function replaces($table, $columns, $search = null, $replace = null, $where = null)
    {
        return self::connect()->replace($table, $columns, $search, $replace, $where);
    }

    public static function getMax($table, $join, $column = null, $where = null)
    {
        return self::connect()->max($table, $join, $column, $where);
    }

    public static function getMin($table, $join, $column = null, $where = null)
    {
        return self::connect()->min($table, $join, $column, $where);
    }

    public static function getAvg($table, $join, $column = null, $where = null)
    {
        return self::connect()->avg($table, $join, $column, $where);
    }

    public static function getSum($table, $join, $column = null, $where = null)
    {
        return self::connect()->sum($table, $join, $column, $where);
    }

    public static function actions($actions)
    {
        return self::connect()->action($actions);
    }

    public static function pages($pageArr, $table, $join, $columns = null, $where = null)
    {
        return self::connect()->page($pageArr, $table, $join, $columns, $where);
    }

    /**
     *兼容原来的用法
     ***********************************************************************************
     */

    //自动调用Model中的方法
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::connect(), $name], $arguments);
    }

}