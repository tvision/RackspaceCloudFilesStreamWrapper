<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Interfaces;

interface FileTypeGuesserInterface
{
    /**
     * @static
     * @abstract
     * @param $filename
     * @return String
     */
    public static function guessByFileName($filename);

}
