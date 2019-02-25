<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 2019/2/23
 * Time: 2:44 PM
 */

namespace Tien\ThinkSwagger;


trait Extra
{
    /**
     * :大括号处理
     *
     * @param $info
     * @return string
     */
    protected function braces($info)
    {
        return '{' . $info . '}';
    }


}