<?php
/**
 * Created by PhpStorm.
 * User: bastian_charlet
 * Date: 15.12.2017
 * Time: 11:07
 */

namespace Joyce\WordpressMiddleware\Service;


class WordpressBridgeService
{
    const WORDPRESS_FILENAME = 'public/wordpress/index.php';

    /**
     * Include wordpress and buffer its output
     * @return string
     */
    public function getStdOutString()
    {
        $output = '';
        if (file_exists(static::WORDPRESS_FILENAME)) {
            ob_start();
            include static::WORDPRESS_FILENAME;
            $output = ob_get_clean();
        }
        return $output;
    }

}