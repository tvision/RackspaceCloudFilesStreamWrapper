<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Service;

use Tvision\RackspaceCloudFilesStreamWrapper\Interfaces\FileTypeGuesserInterface;

/**
 * Class FileTypeGuesser
 * @package Tvision\RackspaceCloudFilesStreamWrapper\Service
 */
class FileTypeGuesser implements FileTypeGuesserInterface
{
    /**
     * @var string $associationExtensionDefault
     */
    private $associationExtensionDefault = 'txt';

    /**
     * @var array $associationExtensionFileType
     */
    private $associationExtensionFileType = array(
        'xls'   =>  'application/excel',
        'hqx'   =>  'application/macbinhex40',
        'doc'   =>  'application/msword',
        'dot'   =>  'application/msword',
        'wrd'   =>  'application/msword',
        'pdf'   =>  'application/pdf',
        'pgp'   =>  'application/pgp',
        'ps'    =>  'application/postscript',
        'eps'   =>  'application/postscript',
        'ai'    =>  'application/postscript',
        'ppt'   =>  'application/powerpoint',
        'rtf'   =>  'application/rtf',
        'tgz'   =>  'application/x-gtar',
        'gtar'  =>  'application/x-gtar',
        'gz'    =>  'application/x-gzip',
        'php'   =>  'application/x-httpd-php',
        'php3'  =>  'application/x-httpd-php',
        'php4'  =>  'application/x-httpd-php',
        'js'    =>  'application/x-javascript',
        'ppd'   =>  'application/x-photoshop',
        'psd'   =>  'application/x-photoshop',
        'swf'   =>  'application/x-shockwave-flash',
        'swc'   =>  'application/x-shockwave-flash',
        'rf'    =>  'application/x-shockwave-flash',
        'tar'   =>  'application/x-tar',
        'zip'   =>  'application/zip',
        'mid'   =>  'audio/midi',
        'midi'  =>  'audio/midi',
        'kar'   =>  'audio/midi',
        'mp2'   =>  'audio/mpeg',
        'mp3'   =>  'audio/mpeg',
        'mpga'  =>  'audio/mpeg',
        'ra'    =>  'audio/x-realaudio',
        'wav'   =>  'audio/wav',
        'bmp'   =>  'image/bitmap',
        'gif'   =>  'image/gif',
        'iff'   =>  'image/iff',
        'jb2'   =>  'image/jb2',
        'jpg'   =>  'image/jpeg',
        'jpe'   =>  'image/jpeg',
        'jpeg'  =>  'image/jpeg',
        'jpx'   =>  'image/jpx',
        'png'   =>  'image/png',
        'tif'   =>  'image/tiff',
        'tiff'  =>  'image/tiff',
        'wbmp'  =>  'image/vnd.wap.wbmp',
        'xbm'   =>  'image/xbm',
        'css'   =>  'text/css',
        'txt'   =>  'text/plain',
        'htm'   =>  'text/html',
        'html'  =>  'text/html',
        'xml'   =>  'text/xml',
        'xsl'   =>  'text/xsl',
        'mpg'   =>  'video/mpeg',
        'mpe'   =>  'video/mpeg',
        'mpeg'  =>  'video/mpeg',
        'qt'    =>  'video/quicktime',
        'mov'   =>  'video/quicktime',
        'avi'   =>  'video/x-ms-video',
        'eml'   =>  'message/rfc822'
        );


    /**
     * @param string $filename
     * @return string
     */
    private function getExtensionByFilename($filename)
    {
        $ext = substr(strrchr($filename, '.'), 1);

        if (!$ext) {
            return $this->associationExtensionDefault;
        }
        return $ext;
    }

    /**
     * {@inheritdoc}
     */
    public function guessByFileName($filename)
    {
        $extension = $this->getExtensionByFilename($filename);

        if (array_key_exists($extension, $this->associationExtensionFileType)) {
            return $this->associationExtensionFileType[$extension];
        } else {
            return false;
        }
    }
}
