<?php
namespace Yaecho\Mysql;

use Yaecho\Mysql\Common\Common;
use Yaecho\Mysql\Database;

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
        $res = $this->src->diff($this->target);
        return $res;
    }
}