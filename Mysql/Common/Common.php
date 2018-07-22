<?php
namespace Yaecho\Mysql\Common;

/**
 * 基类
 *
 * @author Yaecho 
 */
abstract class Common
{
    /**
     * 缺少
     */
    const LACK = 'miss';

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
}