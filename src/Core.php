<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 2019/2/24
 * Time: 8:37 PM
 */

namespace Tien\ThinkSwagger;


use Tien\ThinkSwagger\exceptions\Exception;
use Tien\ThinkSwagger\exceptions\TypeStringException;

trait Core
{

    /**
     * tien_max_3   //最多 3 个参数
     * tien_min_2   //最小 2 个必须参数
     * tien_strict  //严格检验，不在列表中的不能存在
     * @var array
     */
    protected $tienMax          = 'tien_max_';

    protected $tienMaxNum       = 0;

    protected $tienMinNum       = 0;

    protected $tienStrictBool   = false;

    protected $tienMin          = 'tien_min_';

    protected $tienStrict       = 'tien_strict';

    /**
     * 翻译
     *
     * @var array
     */
    protected $translate = [];

    /**
     * 场景值，也就是规则参数
     *
     * @var array
     */
    protected $ruleKey = [];

    /**
     * @var array
     */
    protected $checkRule = [];

    /**
     * :获取规则
     *
     * @return mixed
     */
    public function getRule()
    {
        return $this->rule;
    }


    /**
     * :获取场景值
     *
     * @param string $action
     * @return mixed
     */
    public function getTienScene($action = '')
    {
        if ($action) {
            return isset($this->scene[$action]) ? $this->scene[$action] : [];
        } else {
            return $this->scene;
        }
    }

    /**
     * :通过请求方法获取验证规则
     *
     * @param $action
     * @return array
     * @throws Exception
     */
    public function getRuleByAction($action)
    {
        //获取rule
        $rule = $this->getRule();
        $this->setUpRule($rule);

        //获取场景的参数
        $sceneKeys = $this->getTienScene($action);
        if (empty($sceneKeys)) {
            return [];
        }
        $searchRule = [];
        foreach ($sceneKeys as $key) {
            //明面上存在该规则
            if (in_array($key, $this->ruleKey)) {
                $splitKey = $this->splitRuleKey($key, $this->translate[$key]);
                $this->checkRule[] = $key;
                $searchRule[$splitKey] = $rule[$splitKey];
                continue;
            }

            //不存在该规则，去掉可能存在的后缀
            $realKey = $this->removeSupply($key);
            if (in_array($realKey, $this->ruleKey)) {
                $splitKey = $this->splitRuleKey($realKey, $this->translate[$realKey]);
                $this->checkRule[] = $realKey;
                $searchRule[$splitKey] = $this->removeRequire($rule[$splitKey]);
            } elseif(!$this->handleInSpecialKeys($realKey)) {
                //还是不存在规则中，再排除可能的严格规则等
                throw new Exception('不存在该变量的验证规则：' . $key);
            }
        }
        return $searchRule;
    }

    public function getCheckRule()
    {
        return $this->checkRule;
    }

    /**
     * :
     *
     * @param $str
     * @throws TypeStringException
     */
    public function setTienMax($str)
    {
        if (!is_string($str)) {
            throw new TypeStringException('$str 应该是字符串');
        }
        $this->tienMax = $str;
    }

    /**
     * :
     *
     * @param $str
     * @throws TypeStringException
     */
    public function setTienMin($str)
    {
        if (!is_string($str)) {
            throw new TypeStringException('$str 应该是字符串');
        }
        $this->tienMin = $str;
    }

    public function getTienMaxNum()
    {
        return $this->tienMaxNum;
    }


    public function getTienMinNum()
    {
        return $this->tienMinNum;
    }

    public function getTiemStrictBool()
    {
        return $this->tienStrictBool;
    }

    /**
     * :
     *
     * @param $strict
     * @throws TypeStringException
     */
    public function setTienStrict($strict)
    {
        if (!is_string($strict)) {
            throw new TypeStringException('$strict 应该是字符串');
        }
        $this->tienStrict = $strict;
    }

    /**
     * :处理特殊字符串
     *
     * @param $key
     * @return bool
     */
    protected function handleInSpecialKeys($key)
    {
        //是否是否是严格字符串
        if ($this->tienStrict == $key) {
            $this->tienStrictBool = true;
            return true;
        }

        //验证是否是限制数量最大的
        if (strpos($key, $this->tienMax) === 0) {
            //获取数量
            $num = (int)$this->getTienNum($key, $this->tienMax);
            if (!$num) {
                return false;
            }
            $this->tienMaxNum = $num;
            return true;
        }

        //验证是否是限制数量最小的
        if (strpos($key, $this->tienMin) === 0) {
            //获取数量
            $num = (int)$this->getTienNum($key, $this->tienMin);
            $this->tienMinNum = $num;
            return true;
        }
        return false;
    }

    /**
     * :
     *
     * @param $str
     * @param $tienStr
     * @return bool|string
     */
    protected function getTienNum($str, $tienStr)
    {
        return substr($str, strlen($tienStr));
    }



    /**
     * :
     *
     * @param $rule
     */
    protected function setUpRule($rule)
    {
        $ruleKeys = array_keys($rule);
        $translate = [];
        $ruleKey = [];
        foreach ($ruleKeys as $key) {
            $value = explode('|', $key);
            $translate[$value[0]] = isset($value[1]) ? $value[1] : '';
            $ruleKey[] = $value[0];
        }
        $this->ruleKey = $ruleKey;
        $this->translate = $translate;
    }


    /**
     * :
     *
     * @param $key
     * @param string $translate
     * @return string
     */
    protected function splitRuleKey($key, $translate = '')
    {
        return $translate ? $key . '|' . $translate : $key;
    }

    /**
     * :去掉字符串的补充格式
     *
     * @param $key
     * @return mixed
     */
    protected function removeSupply($key)
    {
        $suffix = isset($this->tienSupplySuffix) ? $this->tienSupplySuffix : '_no';
        return str_replace($suffix, '', $key);
    }

    /**
     * :去掉必须字段
     *
     * @param $rule
     * @return mixed
     */
    protected function removeRequire($rule)
    {
        //先去掉 require|
        if (strpos($rule, 'require|') !== false) {
            return str_replace('require|', '', $rule);
        } elseif (strpos($rule, '|require') !== false) {
            return str_replace('|require', '', $rule);
        } else {
            return str_replace('require', '', $rule);
        }
    }
}