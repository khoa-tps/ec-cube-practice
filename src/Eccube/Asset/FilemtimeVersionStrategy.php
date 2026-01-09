<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Asset;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

/**
 * ファイルの最終更新時刻をバージョンとして使用するバージョン戦略
 */
class FilemtimeVersionStrategy implements VersionStrategyInterface
{
    private $basePath;

    /**
     * @param string $basePath アセットファイルのベースパス
     */
    public function __construct($basePath)
    {
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion($path)
    {
        $fullPath = $this->basePath.'/'.$path;

        if (file_exists($fullPath)) {
            return (string) filemtime($fullPath);
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function applyVersion($path)
    {
        $version = $this->getVersion($path);

        if ('' === $version) {
            return $path;
        }

        return sprintf('%s?v=%s', $path, $version);
    }
}
