<?php

namespace Snowdog\DevTest\Parser;

/**
 * Interface SitemapParserInterface
 * @package Snowdog\DevTest\Processor\SitemapProcessor\Interfaces
 */
interface SitemapParserInterface
{
    /**
     * @param $xmlString
     * @return mixed
     */
    public function parse($xmlString);
}
