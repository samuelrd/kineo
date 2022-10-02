<?php
namespace App\Test;

use PHPUnit\Framework\TestCase;
use Samuelrd\Kineo\Url;

final class UrlTest extends TestCase
{
    public function testParse(): void
    {
        $url = new Url("http://foo.com/bar?page=1&offset=1");
        self::assertEquals("http", $url->getScheme());
        self::assertEquals("foo.com", $url->getHost());
        self::assertEquals("/bar", $url->getPath());
        self::assertEquals("page=1&offset=1", $url->getQuery());
    }

    public function testSwitchProtocol(): void
    {
        $url = new Url("https://beepbopboop.co.uk?q=test");
        $url->toHttp();
        self::assertEquals("http", $url->getScheme());
        $url->toHttps();
        self::assertEquals("https", $url->getScheme());
    }

    public function testAddAndRemoveQueryParameters(): void
    {
        $url = new Url("https://spider.net?prey=fly");
        $url->addQueryParameter("prey", "bee");
        self::assertEquals("prey=bee", $url->getQuery());
        $url->addQueryParameter("morning", "dew");
        self::assertEquals("prey=bee&morning=dew", $url->getQuery());
        $url->removeQueryParameter("prey");
        self::assertEquals("morning=dew", $url->getQuery());
    }

    public function testReturnUrl(): void
    {
        $url = new Url("http://austin.powers?yeah=baby");
        self::assertEquals("https://austin.powers?yeah=baby", $url->toHttps());
        self::assertEquals("http://austin.powers?yeah=baby", $url->toHttp());
        self::assertEquals("http://austin.powers?yeah=baby&fire=laser", $url->addQueryParameter("fire","laser"));
        self::assertEquals("http://austin.powers?fire=laser", $url->removeQueryParameter("yeah"));
    }

    public function testEquals(): void
    {
        $url = new Url("http://mytube.com?video=8gh09");
        $url2 = new Url("http://mytube.com?video=pe89r");
        self::assertEquals(true, $url->equals($url2));
        self::assertEquals(false, $url->equals($url2, true));
    }
}