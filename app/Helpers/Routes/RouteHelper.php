<?php

namespace App\Helpers\Routes;

class RouteHelper
{
    /**
     * @param string $folderPath
     * @return void
     */
    public static function includeRouteFiles(string $folderPath)
    {
        //Iterate through the v1 folder recursively
        $dirIterator = new \RecursiveDirectoryIterator($folderPath);

        /** @var \RecursiveDirectoryIterator | \RecursiveIteratorIterator $it*/
        $it = new \RecursiveIteratorIterator($dirIterator);

        // Require the file in each iteration
        while ($it->valid()) {
            if (!$it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php')
            {
                require $it->key();
//                require  $it->current()->getPathname();
            }
            $it->next();
        }
    }
}
