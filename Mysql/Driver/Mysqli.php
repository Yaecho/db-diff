<?php
/**
 * Mysqli驱动
 */
namespace Yaecho\Mysql\Driver;

class Mysqli
{
    // 声明$instance为私有静态类型，用于保存当前类实例化后的对象
    private static $instance = [];
    // 数据库连接
    private $mysqli = null;
    // 数据库配置文件
    private $config = array(
        'db' => '',
        'host' => 'localhost',
        'port' => '3306',
        'username' => '',
        'password' => '',
        'charset' => 'utf8',
    );

    //错误信息
    public static $error_info = '';
    /**
     * 构造方法声明为私有方法，禁止外部程序使用new实例化，只能在内部new
     *
     * @param array $config 数据库配置文件
     * @return void
     * @author Yaecho 2017-10-02 20:46:02
     */
    private function __construct($config = array())
    {
        // 写入配置
        $this->config = array_merge($this->config, $config);
        // 创建实例
        $this->mysqli = new \mysqli(
            $this->config['host'],
            $this->config['username'],
            $this->config['password'],
            $this->config['db'],
            $this->config['port']
        );
        //错误处理
        if ($this->mysqli->connect_error) {
            self::$error_info = 'Connect Error (' . self::$instance->mysqli->connect_errno . ') ' . self::$instance->mysqli->connect_error;
        }
        //设置字符编码
        if (!$this->mysqli->set_charset($this->config['charset'])) {
            self::$error_info = "Error loading character ". $mysqli->error;
        }
    }
    /**
     * 单例获取方法
     *
     * @param array $config 配置
     * @return object
     * @author Yaecho 2017-10-03 14:39:33
     */
    public static function getInstance($config = array())
    {
        // 检查对象是否已经存在，不存在则实例化后保存到$instance属性
        $key = md5(implode(',', $config));
        if(!isset(self::$instance[$key])) {
            self::$instance[$key] = new self($config);
            //错误处理
            if (!empty(self::$error_info)) {
                return false;
            }
        }
        return self::$instance[$key];
    }
    /**
     * 查询语句
     *
     * @param string $sql sql语句
     * @return array 数据
     * @author Yaecho 2017-10-03 20:50:47 
     */
    public function query(string $sql) {
        //检查是否存在
        if (!$this->mysqli) {
            return false;
        }
        $data = array();
        if ($result = $this->mysqli->query($sql)) {
            // 获取关联数组
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            // free result set
            $result->free();
        } else {
            self::$error_info = $this->mysqli->error;
            return false;
        }
        return $data;
    }
    /**
     * 执行语句
     *
     * @param string $sql sql语句
     * @return array 影响行数，id
     * @author Yaecho 2017-10-03 20:51:47
     */
    public function execute(string $sql) {
        //检查是否存在
        if (!$this->mysqli) {
            return false;
        }
        $data = array();
        if ($result = $this->mysqli->query($sql)) {
            // 影响行数
            $data['affected_rows'] = $this->mysqli->affected_rows;
            // 插入id
            $data['last_id'] = $this->mysqli->insert_id;
        } else {
            self::$error_info = $this->mysqli->error;
            return false;
        }
        return $data;
    }
    /**
     * 获取mysqli
     *
     * @return object
     * @author Yaecho 2017-10-07 16:33:12
     */
    public function getMysqli()
    {
        return $this->mysqli;
    }
    // 声明成私有方法，禁止克隆对象
    private function __clone(){}
    // 声明成私有方法，禁止重建对象
    private function __wakeup(){}
    /** 
     * 构析函数
     *
     * @author Yaecho 2017-10-03 14:46:01
     */
    public function __destruct()
    {   
        if (null !== $this->mysqli) {
            $this->mysqli->close();
        }
    }
}