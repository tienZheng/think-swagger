<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 2019/2/23
 * Time: 2:39 PM
 */

namespace Tien\ThinkSwagger\config;


class Common
{

    public static $version = '2.0.13';

    /**
     * 注释开头
     *
     * @var string
     */
    public static $start = PHP_EOL . '/**' . PHP_EOL;

    /**
     * 注释结尾
     *
     * @var string
     */
    public static $end = ' */';


    /**
     * 链接符
     *
     * @var string
     */
    public static $glue = ' * ';



    /**
     * 注释第二行，标题
     *
     * @var string
     */
    public static $title;


    /**
     * 制表符
     *
     * @var string
     */
    public static $tab = "\t";


    /**
     * 二级制表符
     *
     * @var string
     */
    public static $secTab = "\t\t";


    /**
     * 逗号结束
     *
     * @var string
     */
    public static $commaEnd = ',' . PHP_EOL;


    /**
     * 双引号 + 逗号结束
     *
     * @var string
     */
    public static $dQCommaEnd = '",' . PHP_EOL;


    /**
     * 左括号结束
     *
     * @var string
     */
    public static $leftPTEnd = '(' . PHP_EOL;


    /**
     * 右括号结束
     *
     * @var string
     */
    public static $rightPTEnd = ')' . PHP_EOL;


    /**
     * 右括号 + 逗号结束
     *
     * @var string
     */
    public static $rightPTCommaEnd = '),' . PHP_EOL;

    /**
     * @var string
     */
    public static $space = ' ';

    /**
     * $glue + tab
     *
     * @var string
     */
    public static $glueTab = " * \t";

    /**
     *
     * @var string
     */
    public static $glueSpace = ' *  ';

    /**
     *
     *
     * @var string
     */
    public static $glueTwoTabs = " * \t\t";

}