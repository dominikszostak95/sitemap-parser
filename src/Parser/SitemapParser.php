<?php

namespace Snowdog\DevTest\Parser;

/**
 * Class SitemapParser
 * @package Snowdog\DevTest\Parser
 */
class SitemapParser implements SitemapParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse($xmlString)
    {
        $parsed = [];
        $xml = new \SimpleXMLElement($xmlString);

        foreach ($xml->url as $website) {
            foreach ($website->url as $page) {
                if (!empty($website->loc) && !empty($page->lock)) {
                    $parsed[(parse_url($website->loc)['host'])][] = parse_url($page->loc)['path'];
                }
            }
        }

        return $parsed;
    }
}
