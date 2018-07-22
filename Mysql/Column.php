<?php
namespace Yaecho\Mysql;

use Yaecho\Mysql\Common\Common;

/**
 * Mysql 行
 *
 * @author Yaecho 
 */
class Column extends Common
{
    use \Yaecho\Mysql\Common\Extend;

    /**
     * 字段名
     *
     * @var string
     * @author Yaecho 
     */
    private $field = '';

    /**
     * 类型
     *
     * @var string
     * @author Yaecho 
     */
    private $type = '';

    /**
     * 字符集
     *
     * @var string
     * @author Yaecho 
     */
    private $collation = '';

    /**
     * 是否允许空值
     *
     * @var boolean
     * @author Yaecho 
     */
    private $isNull = false;

    /**
     * 索引
     *
     * @var string
     * @author Yaecho 
     */
    private $key = '';

    /**
     * 默认值
     * 
     * @var mixed
     * @author Yaecho
     */
    private $default;

    /**
     * 额外配置
     *
     * @var string
     * @author Yaecho 
     */
    private $extra = '';

    /**
     * 备注
     *
     * @var string
     * @author Yaecho 
     */
    private $comment = '';

    /**
     * Undocumented function
     *
     * @return string
     * @author Yaecho 
     * @todo
     */
    public function __toString() : string
    {

    }

    public function __construct(array $data) {
        $this->load($data);
    }

    /**
     * 初始化
     *
     * @param array $data
     * @return bool
     * @author Yaecho 
     */
    public function load(array $data)
    {
        if (empty($data)) {
            return;
        }

        $props   = $this->getProps();

        foreach ($props as $prop) {
            $name = $prop->name;
            if ($name === 'isNull') {
                $this->$name = $data['Null'] === 'YES';
            } else {
                $trueName = ucfirst($name);
                $this->$name = $data[$trueName];
            }
        }
    }

    /**
     * 对比
     *
     * @param Column $target
     * @return array
     * @author Yaecho 
     */
    public function diff(?Column $target): array
    {
        $res = [];
        $props   = $this->getProps();

        foreach ($props as $prop)
        {
            $name = $prop->name;
            if ((!isset($target->$name) && !is_null($target->$name)) || $this->$name !== $target->$name) {
                $res[$name] = $this->$name;
            }
        }
        return $res;
    }

    protected function getProps()
    {
        $reflect = new \ReflectionClass($this);
        $props   = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE);
        return $props;
    }
}