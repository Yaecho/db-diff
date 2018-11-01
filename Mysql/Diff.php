<?php
namespace Yaecho\Mysql;

use Yaecho\Mysql\Common\Common;
use Yaecho\Mysql\Database;
use Yaecho\Mysql\Table;
use Yaecho\Mysql\Column;

class Diff extends Common
{
    use \Yaecho\Mysql\Common\Extend;

    /**
     * 源数据库 (权威)
     *
     * @var Database
     * @author Yaecho 
     */
    private $src = null;

    /**
     * 目标数据库 (遗缺)
     *
     * @var Database
     * @author Yaecho 
     */
    private $target = null;

    private $originRes = [];

    public function __construct(array $srcConfig, array $targetConfig)
    {
        $this->init($srcConfig, $targetConfig);
    }

    public function init(array $srcConfig, array $targetConfig)
    {
        $this->src = new Database($srcConfig);
        $this->target = new Database($targetConfig);
    }

    public function compare()
    {
        $this->originRes = $this->src->diff($this->target);
        return $this;
    }

    public function report()
    {
        $str = '';
        foreach ($this->originRes as $tableName => $table) {
            $str .= "--表$tableName" . PHP_EOL;
            if ($table instanceof Table) {
                $str .= "--缺少表$tableName" . PHP_EOL;
                $str .= $table->getCreateSql() . PHP_EOL;
            } elseif (is_array($table)) {
                foreach ($table as $columnName => $column) {
                    $str .= "--字段$columnName" . PHP_EOL;
                    if ($column instanceof Column) {
                        $str .= "--缺少字段$columnName" . PHP_EOL;
                        $str .= $column->getCreateSql($tableName) . PHP_EOL;
                    } elseif (is_array($column)) {
                        foreach($column as $propName => $prop) {
                            if ($propName == '__column') {
                                continue;
                            }
                            $str .= "--属性{$propName}应该是$prop" . PHP_EOL;
                        }
                        $str .= $column['__column']->getUpdateSql($tableName) . PHP_EOL;
                    }
                }
            }
            $str .= PHP_EOL;
        }
        return $str;
    }
}