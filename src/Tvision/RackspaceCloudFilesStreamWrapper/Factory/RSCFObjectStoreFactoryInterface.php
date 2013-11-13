<?php

/**
 * This file is part of the RackspaceCloudFilesStreamWrapper package.
 *
 * (c) Claudio D'Alicandro <claudio.dalicandro@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tvision\RackspaceCloudFilesStreamWrapper\Factory;

interface RSCFObjectStoreFactoryInterface
{
    /**
     * @param array $credentials
     * @param string $endPoint
     * @param string $region
     * @param string $urlType
     * @return \OpenCloud\ObjectStore\Service
     * @throws \Tvision\RackspaceCloudFilesStreamWrapper\Exception\InvalidArgumentException
     */
    public static function newObjectStore(array $credentials, $endPoint, $region, $urlType);
} 