<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 2019/2/24
 * Time: 8:37 PM
 */

namespace Tien\ThinkSwagger;


use Tien\ThinkSwagger\exceptions\Exception;

trait Core
{

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
                $searchRule[$splitKey] = $rule[$splitKey];
                continue;
            }

            //不存在该规则，去掉可能存在的后缀
            $realKey = $this->removeSupply($key);
            if (in_array($realKey, $this->ruleKey)) {
                $splitKey = $this->splitRuleKey($realKey, $this->translate[$realKey]);
                $searchRule[$splitKey] = $this->removeRequire($rule[$splitKey]);
            } else {
                throw new Exception('不存在该变量的验证规则：' . $key);
            }
        }
        return $searchRule;
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