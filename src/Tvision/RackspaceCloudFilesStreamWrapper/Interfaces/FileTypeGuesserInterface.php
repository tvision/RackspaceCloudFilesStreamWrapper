<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Interfaces;

interface FileTypeGuesserInterface
{
    /**
     * Attempt to get the content-type of a file based on the extension
     *
     * @static
     * @param $filename
     * @return String
     *
     * @api
     */
    public static function guessByFileName($filename);

}
