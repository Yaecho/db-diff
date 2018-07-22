<?php
namespace Yaecho\Mysql;

use Yaecho\Mysql\Common\Common;

/**
 * Mysql 表
 *
 * @author Yaecho 
 */
class Table extends Common
{
    use \Yaecho\Mysql\Common\Extend;
    
    /**
     * 名称
     *
     * @var string
     * @author Yaecho 
     */
    private $name = '';

    private $engine = '';

    private $collation = '';

    private $comment = '';

    /**
     * 数据库操作类
     *
     * @var \Medoo\Medoo
     * @author Yaecho 
     */
    private $db = null;

    /**
     * 字段数
     *
     * @var integer
     * @author Yaecho 
     */
    private $columnNum = 0;

    /**
     * 字段信息
     *
     * @var array
     * @author Yaecho 
     */
    private $columns = [];


    public function __construct(array $data, $db)
    {
        $this->init($db);
        $this->load($data);
    }

    public function init($db)
    {
        $this->db = $db;
    }

    public function load(array $data)
    {
        $props = ['name', 'engine', 'collation', 'comment'];

        foreach ($props as $prop) {
            $this->$prop = $data[ucfirst($prop)];
        }

        $data = $this->db->query("show full columns from `{$this->name}`;")->fetchAll();

        $this->columnNum = count($data);

        foreach($data as $row) {
            $this->columns[$row['Field']] = new Column($row);
        }
        
    }

    /**
     * 获取构建表SQL
     *
     * @return string
     * @author Yaecho 
     */
    public function getCreateSql()
    {
        $data = $this->db->query("show create table `{$this->name}`;")->fetchAll();
        return reset($data)['Create Table'];
    }

    /**
     * 对比
     *
     * @param Table $target
     * @return array
     * @author Yaecho 
     */
    public function diff(Table $target): array
    {
        $res = [];
        foreach ($this->columns as $key => $column)
        {
            if (!isset($target->columns[$key])) {
                //目标数据库表不存在
                $res[$key] = $column;
            } else {
                $temp = $column->diff($target->columns[$key]);
                if (!empty($temp)) {
                    $res[$key] = $temp;
                }
            }
        }
        return $res;
    }
}
