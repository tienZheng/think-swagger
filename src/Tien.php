<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 2019/2/23
 * Time: 2:43 PM
 */

namespace Tien\ThinkSwagger;

use Tien\ThinkSwagger\exceptions\FileException;

abstract class Tien
{
    /**
     * 生成文件名
     *
     * @var string
     */
    protected $filename;

    /**
     * 文件生成的路径
     *
     * @var string
     */
    protected $path;

    /**
     * 内容
     *
     * @var string
     */
    protected $content;

    /**
     * 文件内容
     *
     * @var string
     */
    protected $fileContent = '';

    /**
     * Tien constructor.
     * @param $path
     * @throws FileException
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->handlePath();
    }

    /**
     * :处理路径
     *
     * @throws FileException
     */
    protected function handlePath()
    {
        if (is_file($this->path)) {
            throw new FileException('路径错误，与文件同名：' . $this->path);
        }
        if (!is_dir($this->path)) {
            if (!mkdir($this->path, 0700, true)) {
                throw new FileException('创建路径失败：' . $this->path);
            }
        }
    }


    abstract public function setFilename($filename = '');


    /**
     * :双引号处理
     *
     * @param $info
     * @return string
     */
    protected function doubleQuotation($info)
    {
        return '"' . $info . '"';
    }

    /**
     * :等号拼接
     *
     * @param $left
     * @param $right
     * @param string $glue
     * @return string
     */
    protected function glueEqual($left, $right, $glue = '')
    {
        $glue = $glue ? : '=';
        return $left . $glue . $right;
    }

    /**
     * :格式化内容
     *
     * @return mixed
     */
    abstract public function formatContent();


    /**
     * :初始化文件内容
     *
     */
    public function initFileContent()
    {
        //如果文件不存在，新建文件内容
        if (!is_file($this->filename)) {
            $this->fileContent = '<?php' . PHP_EOL;
        }
        return $this;
    }

    /**
     * :获取文件内容
     *
     */
    protected function getFileContent()
    {
        if (!is_file($this->filename)) {
            $this->fileContent = '';
        } else {
            $this->fileContent = file_get_contents($this->filename);
        }
    }


    /**
     * :写入文件
     *
     * @param int $flags
     * @return bool|int
     */
    public function writeFile($flags = 0)
    {
        return file_put_contents($this->filename, $this->fileContent, $flags);
    }


    /**
     * :创建，直接在最后追加内容
     *
     */
    public function create()
    {
        //初始化文件
        $this->initFileContent();

        //追加内容
        $this->fileContent .= PHP_EOL . $this->content;

        //写文件
        return $this->writeFile(FILE_APPEND);
    }







}