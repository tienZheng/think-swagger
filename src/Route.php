<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 2019/2/24
 * Time: 1:13 PM
 */

namespace Tien\ThinkSwagger;


trait Route
{

    /**
     * 路由数组格式
     *
     * @var array
     */
    protected $ruleArr = [];


    /**
     * 是否存在路由参数
     *
     * @var bool
     */
    protected $isPathParam = false;


    /**
     * 路由参数
     *
     * @var string
     */
    protected $rulePathParam = '';

    /**
     * :配置路由数组格式
     *
     * @param $rule
     */
    protected function setUpRuleArr($rule)
    {
        $this->ruleArr = explode('/', $rule);
    }

    /**
     * :验证路由中是否是参数
     *
     */
    protected function verifyIsRuleParam()
    {
        $pattern = '/^\<(.+)\>$/';
        $endPath = end($this->ruleArr);
        if (preg_match($pattern, $endPath, $matches)) {
            $this->isPathParam = true;
            $this->rulePathParam = $matches[1];
            $this->routeRule = substr($this->routeRule, 0, strrpos($this->routeRule, '/')) . '/{' . $this->rulePathParam . '}';
        }
    }






}