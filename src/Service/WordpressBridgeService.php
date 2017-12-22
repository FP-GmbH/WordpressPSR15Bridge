<?php
/**
 * Created by PhpStorm.
 * User: bastian_charlet
 * Date: 15.12.2017
 * Time: 11:07
 */

namespace Joyce\WordpressMiddleware\Service;


use Joyce\WordpressMiddleware\Exception\FileNotFoundException;
use Joyce\WordpressMiddleware\Exception\InvalidArgumentException;

class WordpressBridgeService
{
    /**
     * @var string Path to index-file of Wordpress based on DocRoot
     */
    private $pathToIndex = 'public/wordpress/index.php';

    /**
     * WordpressBridgeService constructor.
     * @param null $pathToIndex
     * @throws \Joyce\WordpressMiddleware\Exception\FileNotFoundException
     * @throws \InvalidArgumentException
     */
    public function __construct($pathToIndex = null)
    {
        if (null !== $pathToIndex) {
            $this->setPathToIndex($pathToIndex);
        }
    }

    /**
     * Include wordpress and buffer its output
     * @return string
     */
    public function getStdOutString()
    {
        $output = '';
        if (file_exists($this->pathToIndex)) {
            ob_start();
            include $this->pathToIndex;
            $output = ob_get_clean();
        }
        return $output;
    }

    /**
     * @param string $pathToIndex
     * @throws InvalidArgumentException
     * @throws FileNotFoundException
     */
    public function setPathToIndex($pathToIndex)
    {
        if (!is_string($pathToIndex)) throw new InvalidArgumentException('Path must be string');
        if (!file_exists($pathToIndex)) throw new FileNotFoundException('Invalid path supplied for wordpress index.php');
        $this->pathToIndex = $pathToIndex;
    }

}