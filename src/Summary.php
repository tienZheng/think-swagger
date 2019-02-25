<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 2019/2/23
 * Time: 2:44 PM
 */

namespace Tien\ThinkSwagger;


use Tien\ThinkSwagger\config\Summary as CS;
use Tien\ThinkSwagger\exceptions\InvalidArgumentException;

class Summary extends Tien
{
    use Extra;

    protected $schemes = '';


    protected $host = '';


    protected $consumes = '';


    protected $produces = '';


    protected $version = '';


    protected $title = '';


    protected $description = '';

    /**
     * :设置文件名
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
            $filename = 'Summary.php';
        }
        $this->filename = $this->path . $filename;
        return $this;
    }

    /**
     * :设置 schemes
     *
     * @param array $schemes
     * @return $this
     */
    public function setSchemes(array $schemes)
    {
        $schemesArr = [];

        foreach ($schemes as $scheme) {
            $schemesArr[] = $this->doubleQuotation($scheme);
        }

        $this->schemes = implode(',' , $schemesArr);

        //大括号处理
        $this->schemes = $this->braces($this->schemes);
        return $this;
    }


    /**
     * :
     *
     * @param $host
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setHost($host)
    {
        if (!is_string($host)) {
            throw new InvalidArgumentException('$host 应该是字符串');
        }

        $this->host = $this->doubleQuotation($host);
        return $this;
    }


    /**
     * :
     *
     * @param $consumes
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setConsumes($consumes)
    {
        if (!is_string($consumes)) {
            throw new InvalidArgumentException('$consumes 应该是字符串');
        }

        $this->consumes = $this->braces($this->doubleQuotation($consumes));
        return $this;
    }

    /**
     * :
     *
     * @param $produces
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setProduces($produces)
    {
        if (!is_string($produces)) {
            throw new InvalidArgumentException('$produce 应该是字符串');
        }
        $this->produces = $this->braces($this->doubleQuotation($produces));
        return $this;
    }


    /**
     * :
     *
     * @param $version
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setVersion($version)
    {
        if (!is_string($version)) {
            throw new InvalidArgumentException('$version 应该是字符串');
        }
        $this->version = $this->doubleQuotation($version);
        return $this;
    }


    /**
     * :
     *
     * @param $title
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setTitle($title)
    {
        if (!is_string($title)) {
            throw new InvalidArgumentException('$title 应该是字符串');
        }
        $this->title = $this->doubleQuotation($title);
        return $this;
    }


    /**
     * :
     *
     * @param $description
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setDescription($description)
    {
        if (!is_string($description)) {
            throw new InvalidArgumentException('$description 应该是字符串');
        }
        $this->description = $this->doubleQuotation($description);
        return $this;
    }


    /**
     * :格式化内容
     *
     * @return $this
     */
    public function formatContent()
    {
        $content = CS::$start . CS::$glue;

        //@SWG\Swagger
        $content .= CS::$title . CS::$leftPTEnd;

        $keys = ['schemes', 'host', 'consumes', 'produces'];
        $prev = '';
        foreach ($keys as $key) {
            $prev .= CS::$glueTab;
            $prev .= $this->glueEqual($key, $this->$key);
            $prev .= CS::$commaEnd;
        }
        $content .= $prev;

        //@SWG\Info
        $content .= CS::$glueTab;
        $content .= CS::$info . CS::$leftPTEnd;

        $secKeys = ['version', 'title', 'description'];
        $secNext = '';
        foreach ($secKeys as $secKey) {
            $secNext .= CS::$glueTwoTabs;
            $secNext .= $this->glueEqual($secKey, $this->$secKey);
            $secNext .= CS::$commaEnd;
        }
        $content .= $secNext;

        //end
        $content .= CS::$glueSpace . CS::$rightPTCommaEnd;
        $content .= CS::$glue . CS::$rightPTEnd . CS::$end;

        $this->content = $content;
        return $this;
    }



}