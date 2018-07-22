<?php
namespace Yaecho\Mysql\Common;

trait Extend
{
    /**
     * 获取属性
     *
     * @param string $name
     * @return mixed
     * @author Yaecho 
     */
    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __isset(string $name)
    {
        return isset($this->$name);
    }
}