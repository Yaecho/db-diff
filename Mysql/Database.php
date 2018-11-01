<?php
namespace Yaecho\Mysql;

use Yaecho\Mysql\Common\Common;
use Yaecho\Mysql\Driver\Mysqli;

class Database extends Common
{
    use \Yaecho\Mysql\Common\Extend;

    private $dbName = '';

    private $host = 'localhost';

    private $port = 3306;

    private $charset = 'utf8';

    private $username = '';

    private $password = '';

    /**
     * 数据库操作类
     *
     * @var \Yaecho\Mysql\Driver\Mysqli
     * @author Yaecho 
     */
    private $db = null;

    private $tables = [];

    private $tableNum = 0;

    public function __construct($config = [])
    {
        $this->init($config);
        $this->load();
    }

    public function init($config = [])
    {
        $option = [];
        $rel = ['db' => 'dbName', 'host', 'username', 'password', 'charset', 'port'];

        foreach ($rel as $key => $value) {
            if (is_int($key)) {
                $key = $value;
            }
            if (isset($config[$value])) {
                $option[$key] = $this->$value = $config[$value];
            } else {
                $option[$key] = $this->$value;
            }
        }
        
        $this->db = Mysqli::getInstance($option);
    }

    public function load()
    {
        $data = $this->db->query("show table status;");

        $this->tableNum = count($data);

        foreach ($data as $row) {
            $this->tables[$row['Name']] = new Table($row, $this->db);
        }
    }

    /**
     * 对比
     *
     * @param Database $target
     * @return array
     * @author Yaecho 
     */
    public function diff(Database $target): array
    {
        $res = [];
        foreach ($this->tables as $key => $table)
        {
            if (!isset($target->tables[$key])) {
                //目标数据库表不存在
                $res[$key] = $table;   
            } else {
                $temp = $table->diff($target->tables[$key]);
                if (!empty($temp)) {
                    $res[$key] = $temp;
                }
            }
        }
        return $res;
    }
}