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
    /**
     * @var string
     */
    private $basePath;

    /**
     * @param string $basePath アセットファイルのベースパス
     */
    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion(string $path): string
    {
        $fullPath = $this->basePath.'/'.$path;

        if (file_exists($fullPath) && is_readable($fullPath)) {
            $mtime = @filemtime($fullPath);
            if ($mtime !== false) {
                return (string) $mtime;
            }
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function applyVersion(string $path): string
    {
        $version = $this->getVersion($path);

        if ('' === $version) {
            return $path;
        }

        return sprintf('%s?v=%s', $path, $version);
    }
}
