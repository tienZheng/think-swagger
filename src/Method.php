<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 2019/2/23
 * Time: 4:20 PM
 */

namespace Tien\ThinkSwagger;


use Tien\ThinkSwagger\exceptions\Exception;
use Tien\ThinkSwagger\exceptions\InvalidArgumentException;
use Tien\ThinkSwagger\config\Method as CM;

class Method extends Tien
{
    use Extra, Route, Validate;

    protected $request;

    /**
     * 需要忽略的路由
     *
     * @var string
     */
    protected $ignoreRoute = '';


    /**
     * 是否是开发环境
     *
     * @var bool
     */
    protected $isDev = false;

    /**
     * 路由规则
     *
     * @var string
     */
    protected $routeRule = '';


    /**
     * 验证类对象
     *
     * @var null
     */
    protected $validateObj = null;

    /**
     * 验证类地址
     *
     * @var string
     */
    protected $validateClass = '';

    /**
     * 模块标签
     *
     * @var string
     */
    protected $tag = '';

    /**
     * 请求方式
     *
     * @var string
     */
    protected $method;

    /**
     * 请求方法
     *
     * @var string
     */
    protected $action;

    /**
     * 方法对应的 type 类型
     *
     * @var array
     */
    protected $methodParam = [
        'post'      => 'formData',
        'get'       => 'query',
        'put'       => 'formData',
        'delete'    => 'query',
        'route'     => 'path',
    ];

    /**
     * 解析类型
     *
     * @var array
     */
    protected $tienTypes = [
        'integer'   => 'integer',
        'image'     => 'file',
        'file'      => 'file',
    ];

    /**
     * :
     *
     * @param $request
     * @return $this|bool
     * @throws Exception
     */
    public function initRequest($request)
    {
        $this->request = $request;

        //获取路由信息
        $this->getRouteRule();

        //获取验证类
        $this->getValidateClass();

        //获取验证类对象
        $this->getValidateObj();

        //获取模块标签
        $this->getTag();

        //设置文件名
        $this->setFilename();

        //验证该方法的注释是否已存在
        if ($this->verifyIsExists()) {
            return false;
        }

        //获取路由数组格式
        $this->setUpRuleArr($this->routeRule);

        //验证路由
        $this->verifyIsRuleParam();

        //设置规则
        $attrArr = [
           'tienTypes',
        ];
        $this->setUpAttrArray($attrArr);

        //获取请求方式
        $this->getMethod();

        //执行方法
        $this->getAction();

        return $this;
    }

    /**
     * :
     *
     * @throws Exception
     */
    protected function getRouteRule()
    {
        $routeInfo = $this->request->routeInfo();
        $this->routeRule = isset($routeInfo['rule']) ? $routeInfo['rule'] : '';
        if (!$this->routeRule) {
            throw new Exception('不存在路由信息');
        }
    }

    /**
     * :
     *
     * @param $class
     */
    public function setValidateClass($class)
    {
        $this->validateClass = $class;
    }


    /**
     * :获取验证对象属性
     *
     * @param $name
     * @return array
     */
    private function getValidateObjAttr($name)
    {
        return isset($this->validateObj->$name) ? $this->validateObj->$name : [];
    }

    /**
     * :设置当前对象属性
     *
     * @param $nameAttr
     */
    protected function setUpAttrArray($nameAttr)
    {
        foreach ($nameAttr as $name) {
            $addRule = $this->getValidateObjAttr($name);
            if (is_array($addRule)) {
                $this->$name = array_merge($this->$name, $addRule);
            }
        }
    }

    /**
     * :
     *
     * @param $attrStr
     */
    protected function setUpAttrString($attrStr)
    {
        foreach ($attrStr as $name) {
            $addRule = $this->getValidateObjAttr($name);
            if ($addRule && is_string($addRule)) {
                $this->$name = $addRule;
            }
        }
    }

    /**
     * :获取模块标签
     *
     */
    protected function getTag()
    {
        $routeRule  = $this->routeRule;
        $ignoreRule = $this->ignoreRoute;
        $ignoreLen  = strlen($ignoreRule);
        $tag        = substr($routeRule, $ignoreLen, strpos($routeRule, '/', $ignoreLen) - $ignoreLen);
        $this->tag  = ucfirst($tag);
    }

    /**
     * :获得请求方式
     *
     */
    protected function getMethod()
    {
        $this->method = strtolower($this->request->method());
    }

    /**
     * :判断是否是开发环境
     *
     * @return bool
     */
    public function verifyIsDev()
    {
        $isDev = \think\facade\Env::get('app_debug');
        return !is_null($isDev) ? : $this->isDev;
    }

    /**
     * :验证该方法的是否已存在注释
     *
     * @return bool
     */
    protected function verifyIsExists()
    {
        //如果文件不存在，直接返回 false
        if (!is_file($this->filename)) {
            return false;
        }

        //获取文件内容
        $this->getFileContent();

        //确定是否存在该路由规则的方法注释
        return strpos($this->fileContent, $this->routeRule) !== false;
    }

    /**
     * :
     *
     * @param $ignoreRoute
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setIgnoreRoute($ignoreRoute)
    {
        if (!is_string($ignoreRoute)) {
            throw new InvalidArgumentException('$ignoreRoute 应该是字符串');
        }
        $this->ignoreRoute = $ignoreRoute;
        return $this;
    }


    /**
     * :
     *
     * @param array $types
     * @return $this
     */
    public function setTypes(array $types = [])
    {
        $this->tienTypes = array_merge($this->tienTypes, $types);
        return $this;
    }


    /**
     * :
     *
     * @param string $filename
     * @throws Exception
     */
    public function setFilename($filename = '')
    {
        if (!$this->tag) {
            throw new Exception('模块标签不存在');
        }
        $this->filename = $this->path . $this->tag . '.php';
    }

    /**
     * :
     *
     * @return $this|mixed
     * @throws Exception
     */
    public function formatContent()
    {
        $content = CM::$start . CM::$glue;

        //method
        $content .= CM::${$this->method} . CM::$leftPTEnd;

        //path
        $content .= CM::$glueSpace . $this->glueEqual('path', $this->doubleQuotation($this->routeRule)) . CM::$commaEnd;

        //tags
        $content .= CM::$glueSpace . $this->glueEqual('tags', $this->braces($this->doubleQuotation($this->tag))) . CM::$commaEnd;

        //operationId
        $content .= CM::$glueSpace . $this->glueEqual('operationId', $this->doubleQuotation($this->getOperationId())) . CM::$commaEnd;

        list($summary, $description) = $this->formatDescriptionText();
        //summary
        $content .= CM::$glueSpace . $this->glueEqual('summary', $this->doubleQuotation($summary)) . CM::$commaEnd;

        //description
        $content .= CM::$glueSpace . $this->glueEqual('description', $this->doubleQuotation($description)) . CM::$commaEnd;

        //consumes
        $content .= CM::$glueSpace . $this->glueEqual('consumes', $this->braces($this->doubleQuotation('multipart/form-data'))) . CM::$commaEnd;

        //produces
        $content .= CM::$glueSpace . $this->glueEqual('produces', $this->braces($this->doubleQuotation('multipart/form-data'))) . CM::$commaEnd;

        if ($parameters = $this->formatParameter()) {
            //@SWG\Parameter
            $content .= $parameters;
        }

        //@SWG\Response
        $content .= CM::$glueSpace . CM::$response . CM::$leftPTEnd;
        $content .= CM::$glueTwoTabs . $this->glueEqual('response', $this->doubleQuotation('default')) .CM::$commaEnd;
        $content .= CM::$glueTwoTabs . $this->glueEqual('description', $this->doubleQuotation('unexpected error')) . CM::$commaEnd;
        $content .= CM::$glueSpace . CM::$rightPTCommaEnd;

        //end
        $content .= CM::$glue . CM::$rightPTEnd . CM::$end;
        $this->content = $content;
        return $this;
    }

    /**
     * :
     *
     * @return mixed
     */
    protected function getOperationId()
    {
        return $this->rulePathParam ?: end($this->ruleArr);
    }

    /**
     * :
     *
     * @return array
     * @throws Exception
     */
    protected function formatDescriptionText()
    {
        $param = $this->validateObj->tienText;
        if (!isset($param) || empty($param[$this->action])) {
            throw new Exception('方法解析信息不存在：' . $this->action);
        }

        $text = $param[$this->action];
        if (($index = strpos($text, ',')) !== false) {
            $summary = substr($text, 0, $index);
        } else {
            $summary = $text;
        }

        return [
            $summary, $text,
        ];
    }

    /**
     * :获取参数的注释体
     *
     * @return string
     * @throws Exception
     */
    protected function formatParameter()
    {
        if (!method_exists($this->validateObj, 'getRuleByAction')) {
            throw new Exception('当前验证类没有引入 Tien\ThinkSwagger\Core; 类，方法 getRuleByAction 不存在');
        }
        $info = $this->validateObj->getRuleByAction($this->action);
        if (empty($info)) {
            return '';
        }

        $content = '';
        foreach ($info as $key => $value) {
            $content .= CM::$glueSpace . CM::$parameter . CM::$leftPTEnd;

            list($name, $translate) = explode('|', $key);

            //name
            $content .= CM::$glueTwoTabs . $this->glueEqual('name', $this->doubleQuotation($name)) . CM::$commaEnd;

            //in
            $content .= CM::$glueTwoTabs . $this->glueEqual('in', $this->doubleQuotation($this->getIn($name))) . CM::$commaEnd;

            //description
            $content .= CM::$glueTwoTabs . $this->glueEqual('description', $this->doubleQuotation($translate)) . CM::$commaEnd;

            //required
            $content .= CM::$glueTwoTabs . $this->glueEqual('required', $this->getRequired($value)) . CM::$commaEnd;

            //type
            $content .= CM::$glueTwoTabs . $this->glueEqual('type', $this->doubleQuotation($this->getType($value))) . CM::$commaEnd;

            //end
            $content .= CM::$glueSpace . CM::$rightPTCommaEnd;
        }
        return $content;
    }

    /**
     * :
     *
     * @param $key
     * @return mixed
     */
    protected function getIn($key)
    {
        //如果存在路由参数
        if ($this->isPathParam && $key == $this->rulePathParam) {
            return $this->methodParam['route'];
        } else {
            return $this->methodParam[$this->method];
        }
    }

    /**
     * :
     *
     * @param $value
     * @return string
     */
    protected function getRequired($value)
    {
        return strpos($value, 'require') !== false ? 'true' : 'false';
    }

    /**
     * :
     *
     * @param $validateRule
     * @return string
     */
    protected function getType($validateRule)
    {
        $type = '';
        foreach ($this->tienTypes as $key => $value) {
            if (strpos($validateRule, $key) !== false) {
                $type = $value;
                break;
            }
        }
        return $type ?: 'string';
    }


    /**
     * :
     *
     * @return bool|int
     */
    public function create()
    {
        //获取文件已存在的内容
        $this->getFileContent();

        //验证是否已存在
        if ($this->verifyRouteIsExistsFile()) {
            return true;
        }

        //如果存在文件内容
        if ($this->fileContent) {
            $this->fileContent = PHP_EOL . $this->content;
            return $this->writeFile(FILE_APPEND);
        } else {
            $this->fileContent = '<?php' . PHP_EOL . $this->content;
            return $this->writeFile();
        }
    }


    /**
     * :验证路由是否已存在
     *
     * @return bool
     */
    protected function verifyRouteIsExistsFile()
    {
        return strpos($this->fileContent, $this->routeRule) !== false ?: false;
    }
}