<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 2019/2/23
 * Time: 2:40 PM
 */

namespace Tien\ThinkSwagger\config;


class Method extends Common
{

    /**
     * get 方法
     *
     * @var string
     */
    public static $get = '@SWG\Get';


    /**
     * post 方法
     *
     * @var string
     */
    public static $post = '@SWG\Post';

    /**
     * @var string
     */
    public static $put = '@SWG\Put';


    /**
     * @var string
     */
    public static $delete = '@SWG\Delete';

    /**
     * 成员变量
     *
     * @var string
     */
    public static $parameter = '@SWG\Parameter';



    /**
     * 返回.
     *
     * @var string
     */
    public static $response = '@SWG\Response';






}