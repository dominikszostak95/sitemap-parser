<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

/**
 * Class VarnishManager
 * @package Snowdog\DevTest\Model
 */
class FileManager
{
    /**
     * @var Database|\PDO
     */
    private $database;

    /**
     * UserManager constructor.
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getFileContent(array $file)
    {
        if (empty($file['tmp_name'])) {
            throw new \RuntimeException('Temporary file not exists!');
        }

        return file_get_contents($file['tmp_name']);
    }
}