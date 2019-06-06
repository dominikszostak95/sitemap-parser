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

    /**
     * @param array $file
     * @return bool|string
     */
    public function getFileContent(array $file)
    {
        if (empty($file['tmp_name'])) {
            throw new \RuntimeException('Temporary file not exists!');
        }

        return file_get_contents($file['tmp_name']);
    }

    /**
     * @param array $file
     * @return bool
     */
    public function validate(array $file)
    {
        if (preg_match("/\.(xml)$/", $file['ame'])) {
            throw new \RuntimeException('Invalid file type!');
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new \RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new \RuntimeException('Exceeded filesize limit.');
            default:
                throw new \RuntimeException('Unknown errors.');
        }

        return true;
    }
}