<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 2019/2/23
 * Time: 3:33 PM
 */

namespace Tien\ThinkSwagger;

use Tien\ThinkSwagger\config\Tag as CT;
use Tien\ThinkSwagger\exceptions\InvalidArgumentException;

class Tags extends Tien
{
    /**
     * 标签数组
     *
     * @var array
     */
    protected $tags = [];

    /**
     * :
     *
     * @param string $filename
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setFilename($filename = '')
    {
        if (!is_string($filename)) {
            throw new InvalidArgumentException('$filename 应该是字符串');
        }
        if (!$filename) {
            $filename = 'Tags.php';
        }
        $this->filename = $this->path . $filename;
        return $this;
    }


    /**
     * :设置标签
     *
     * @param array $tags
     * @return $this
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * :格式化一个标签的内容
     *
     * @param $name
     * @param string $description
     * @return string
     * @throws InvalidArgumentException
     */
    protected function formatTagContent($name, $description = '')
    {
        if (!is_string($name) || !is_string($description)) {
            throw new InvalidArgumentException('$name 和 $description 必须是字符串');
        }
        // name 第一个字母大写
        $name = ucfirst($name);

        // start
        $string = CT::$start;

        //@SWG\Tag
        $string .= CT::$glue . CT::$title . CT::$leftPTEnd;

        // name
        $nameString = $this->glueEqual('name', $this->doubleQuotation($name));
        $string .= CT::$glueTab . $nameString . CT::$commaEnd;

        //description
        $descriptionString = $this->glueEqual('description', $this->doubleQuotation($description));
        $string .= CT::$glueTab . $descriptionString . CT::$commaEnd;

        //end
        $string .= CT::$glueSpace . CT::$rightPTCommaEnd . CT::$end . PHP_EOL;

        return $string;
    }


    /**
     * :格式化文件内容
     *
     * @return $this|mixed
     * @throws InvalidArgumentException
     */
    public function formatContent()
    {
        if (empty($this->tags)) {
            throw new InvalidArgumentException('Tags 数组不存在');
        }
        $content = '';
        foreach ($this->tags as $name => $description) {
            $content .= $this->formatTagContent($name, $description);
        }
        $this->content = $content;
        return $this;
    }



}