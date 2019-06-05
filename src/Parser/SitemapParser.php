<?php

namespace Snowdog\DevTest\Parser;

class SitemapParser
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
                $parsed[(parse_url($website->loc)['host'])][] = parse_url($page->loc)['path'];
            }
        }

        return $parsed;
    }
}
