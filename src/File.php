<?php
/**
 * Created by PhpStorm.
 * User: Raheel Sarfraz
 * Date: 5/1/2021
 * Time: 5:45 PM
 */

namespace App;


class File
{
    public static function isExists($file) : bool
    {
        return file_exists($file);
    }
    public static function createFile($file, $data)
    {
        return file_put_contents($file, $data);
    }
    public static function getFile($file)
    {
        return file_get_contents($file);
    }

}