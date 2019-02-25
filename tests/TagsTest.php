<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 2019/2/25
 * Time: 2:03 PM
 */

namespace Tien\ThinkSwagger\Tests;


use PHPUnit\Framework\TestCase;
use Tien\ThinkSwagger\exceptions\InvalidArgumentException;
use Tien\ThinkSwagger\Tags;

class TagsTest extends TestCase
{

    public function getTagObj()
    {
        return new Tags('./');
    }

    public function testSetFilename()
    {
        $tags = $this->getTagObj();

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('$filename 应该是字符串');

        $filename = [];

        $tags->setFilename($filename);

        $this->fail('testSetFilename fail');
    }



    public function testSetTags()
    {
        $tags = $this->getTagObj();

        $this->expectException('TypeError');

        //$this->expectExceptionMessage('$tags 应该是数组');

        $info = '';

        $tags->setTags($info);

        $this->fail('setTags test fail');
    }


    public function testFormatContent()
    {
        $tags = $this->getTagObj();

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('Tags 数组不存在');

        $tags->formatContent();

        $this->fail('formatContent test fail');
    }


}