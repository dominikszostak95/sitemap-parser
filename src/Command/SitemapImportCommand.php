<?php

namespace Snowdog\DevTest\Command;

use Snowdog\DevTest\Model\SitemapImportManager;
use Snowdog\DevTest\Model\UserManager;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SitemapImportCommand
 * @package Snowdog\DevTest\Command
 */
class SitemapImportCommand
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var SitemapImportManager
     */
    private $sitemapImportManager;

    /**
     * SitemapImportCommand constructor.
     * @param UserManager $userManager
     * @param SitemapImportManager $sitemapImportManager
     */
    public function __construct(UserManager $userManager, SitemapImportManager $sitemapImportManager)
    {
        $this->userManager = $userManager;
        $this->sitemapImportManager = $sitemapImportManager;
    }

    public function __invoke($filePath, $userLogin, OutputInterface $output)
    {
        if (!$filePath || !$userLogin) {
            $output->writeln('<error>File path and login is required!</error>');
        } elseif (!file_exists($filePath) || !$this->userManager->getByLogin($userLogin)) {
            $output->writeln('<error>File  or user with this login not exists!</error>');
        } else {
            $user = $this->userManager->getByLogin($userLogin);

            $file = [
                'error'    => UPLOAD_ERR_OK,
                'type'     => mime_content_type($filePath),
                'tmp_name' => $filePath,
                'name'     => $filePath
            ];

            $import = $this->sitemapImportManager->import($file, $user);

            if ($import) {
                $output->writeln('<error>Sitemap imported!</error>');
            } else {
                $output->writeln('<error>Something went wrong. Please try again later!</error>');
            }
        }
    }
}