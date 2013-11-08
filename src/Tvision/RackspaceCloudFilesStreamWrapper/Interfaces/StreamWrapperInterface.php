<?php

namespace Tvision\RackspaceCloudFilesStreamWrapper\Interfaces;

/**
 * Interface StreamWrapperInterface
 * @package Tvision\RackspaceCloudFilesStreamWrapper\Interfaces
 *
 * @author liuggio
 */
interface StreamWrapperInterface
{
    /**
     * @return bool
     *
     * @api
     */
    function dir_closedir();

    /**
     * @param string $path
     * @param int $options
     * @return bool
     *
     * @api
     */
    function dir_opendir($path, $options);

    /**
     * @return string
     *
     * @api
     */
    function dir_readdir();

    /**
     * @return bool
     *
     * @api
     */
    function dir_rewinddir();

    /**
     * @param string $path
     * @param int $mode
     * @param int $options
     * @return bool
     *
     * @api
     */
    function mkdir($path, $mode, $options);

    /**
     * @param string $path_from
     * @param string $path_to
     * @return bool
     *
     * @api
     */
    function rename($path_from, $path_to);

    /**
     * @param string $path
     * @param int $options
     * @return bool
     *
     * @api
     */
    function rmdir($path, $options);

    /**
     * @param int $cast_as
     * @return resource
     *
     * @api
     */
    function stream_cast($cast_as);

    /**
     * @api
     */
    function stream_close();

    /**
     * @api
     */
    function stream_eof();

    /**
     * @api
     */
    function stream_flush();

    /**
     * @param int $operation
     * @return bool
     *
     * @api
     */
    function stream_lock($operation);

    /**
     * @param string $path
     * @param int $option
     * @param mixed $var
     * @return bool
     *
     * @api
     */
    function stream_metadata($path, $option, $var);

    /**
     * @param string $path
     * @param string $mode
     * @param int $options
     * @param string $opened_path
     * @return bool
     *
     * @api
     */
    function stream_open($path, $mode, $options, &$opened_path);

    /**
     * @param int $count
     * @return string
     *
     * @api
     */
    function stream_read($count);

    /**
     * @param int $offset
     * @param int $whence
     * @return bool
     *
     * @api
     */
    function stream_seek($offset, $whence);

    /**
     * @param int $option
     * @param int $arg1
     * @param int $arg2
     * @return bool
     *
     * @api
     */
    function stream_set_option($option, $arg1, $arg2);

    /**
     * @return array
     *
     * @api
     */
    function stream_stat();

    /**
     * @return int
     *
     * @api
     */
    function stream_tell();

    /**
     * @param string $data
     * @return int
     *
     * @api
     */
    function stream_write($data);

    /**
     * @param string $path
     * @return bool
     *
     * @api
     */
    function unlink($path);

    /**
     * @param string $path
     * @param int $flags
     * @return array
     *
     * @api
     */
    function url_stat($path, $flags);
}

