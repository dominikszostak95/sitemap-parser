<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;
use Snowdog\DevTest\Parser\SitemapParser;

/**
 * Class VarnishManager
 * @package Snowdog\DevTest\Model
 */
class SitemapImportManager
{
    /**
     * @var Database|\PDO
     */
    private $database;

    /**
     * @var SitemapParser
     */
    private $sitemapParser;

    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var PageManager
     */
    private $pageManager;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * SitemapImportManager constructor.
     * @param Database $database
     * @param FileManager $fileManager
     * @param SitemapParser $sitemapParser
     * @param WebsiteManager $websiteManager
     * @param PageManager $pageManager
     */
    public function __construct(
        Database $database,
        FileManager $fileManager,
        SitemapParser $sitemapParser,
        WebsiteManager $websiteManager,
        PageManager $pageManager)
    {
        $this->database = $database;
        $this->fileManager = $fileManager;
        $this->sitemapParser = $sitemapParser;
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
    }

    /**
     * @param $file
     * @param User $user
     * @return bool
     */
    public function import($file, User $user)
    {
        if (!$user || !$this->fileManager->validate($file)) {
            return false;
        }

        $sitemapFile = $this->fileManager->getFileContent($file);
        $sitemap = $this->sitemapParser->parse($sitemapFile);

        if (!empty($sitemap)) {
            foreach ($sitemap as $website => $pages) {
                $this->saveData($website, $pages, $user);
            }
        }

        return true;
    }

    /**
     * @param $website
     * @param $pages
     * @param User $user
     * @return bool
     */
    private function saveData($website, $pages, User $user)
    {
        if (empty($website) || empty($pages)) {
            return false;
        }

        $websiteId = $this->websiteManager->create($user, $website, $website);

        if (empty($websiteId)) {
            return false;
        }

        foreach ($pages as $page) {
            $this->pageManager->create($this->websiteManager->getById($websiteId), $page);
        }

        return true;
    }
}