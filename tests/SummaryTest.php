<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 2019/2/24
 * Time: 11:05 PM
 */
namespace Tien\ThinkSwagger\Tests;

use Tien\ThinkSwagger\exceptions\FileException;
use Tien\ThinkSwagger\exceptions\InvalidArgumentException;
use Tien\ThinkSwagger\Summary;
use PHPUnit\Framework\TestCase;

class SummaryTest extends TestCase
{

    public function testHandlePathWithFile()
    {
        $path = __DIR__ . '/./.gitkeep';

        //断言会出现错误提示
        $this->expectException(FileException::class);

        //断言异常提示为 '创建路径失败'
        $this->expectExceptionMessage('路径错误，与文件同名：' . __DIR__ . '/./.gitkeep');

        $summary = new Summary($path);

        //断言失败
        $this->fail('路径错误，与文件同名：' . __DIR__ . '/./.gitkeep');
    }


    public function getSummaryObj()
    {
        return new Summary('./');
    }


    public function testSetFilename()
    {
        $summary = $this->getSummaryObj();

        $filename = [];

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('$filename 应该是字符串');

        $summary->setFilename($filename);

        $this->fail('断言失败：$filename 应该是字符串');

    }

    public function testSetSchemes()
    {
        $summary = $this->getSummaryObj();

        $schemes = '';

        $this->expectException('TypeError');

        //$this->expectExceptionMessage('$schemes 应该是数组');

        $summary->setSchemes($schemes);

        $this->fail('断言失败：$schemes 应该是数组');
    }


    public function testSetHost()
    {
        $summary = $this->getSummaryObj();

        $param = [];

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('$host 应该是字符串');

        $summary->setHost($param);

        $this->fail('断言失败：$host 应该是字符串');
    }


    public function testSetConsumes()
    {
        $summary = $this->getSummaryObj();

        $param = [];

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('$consumes 应该是字符串');

        $summary->setConsumes($param);

        $this->fail('断言失败：$consumes 应该是字符串');
    }


    public function testSetProduces()
    {
        $summary = $this->getSummaryObj();

        $param = [];

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('$produce 应该是字符串');

        $summary->setProduces($param);

        $this->fail('断言失败：$produce 应该是字符串');
    }



    public function testSetVersion()
    {
        $summary = $this->getSummaryObj();

        $param = [];

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('$version 应该是字符串');

        $summary->setVersion($param);

        $this->fail('断言失败：$version 应该是字符串');
    }


    public function testSetTitle()
    {
        $summary = $this->getSummaryObj();

        $param = [];

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('$title 应该是字符串');

        $summary->setTitle($param);

        $this->fail('断言失败：$title 应该是字符串');
    }

    public function testSetDescription()
    {
        $summary = $this->getSummaryObj();

        $param = [];

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('$description 应该是字符串');

        $summary->setDescription($param);

        $this->fail('断言失败：$description 应该是字符串');
    }




}