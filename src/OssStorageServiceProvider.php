<?php

/*
 * This file is part of the iidestiny/flysystem-oss.
 *
 * (c) iidestiny <iidestiny@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Telanflow\LaravelFilesystemOss;

use Telanflow\Flysystem\Oss\OssAdapter;
use Telanflow\Flysystem\Oss\Plugins\FileUrl;
use Telanflow\Flysystem\Oss\Plugins\Kernel;
use Telanflow\Flysystem\Oss\Plugins\SignUrl;
use Telanflow\Flysystem\Oss\Plugins\TemporaryUrl;
use Telanflow\Flysystem\Oss\Plugins\SignatureConfig;
use Telanflow\Flysystem\Oss\Plugins\SetBucket;
use Telanflow\Flysystem\Oss\Plugins\Verify;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

/**
 * Class OssStorageServiceProvider
 *
 * @author iidestiny <iidestiny@vip.qq.com>
 */
class OssStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        app('filesystem')->extend('oss', function ($app, $config) {
            $root = $config['root'] ?? null;
            $buckets = isset($config['buckets'])?$config['buckets']:[];
            $adapter = new OssAdapter(
                $config['access_key'],
                $config['secret_key'],
                $config['endpoint'],
                $config['bucket'],
                $config['isCName'],
                $root,
                $buckets
            );

            $filesystem = new Filesystem($adapter);

            $filesystem->addPlugin(new FileUrl());
            $filesystem->addPlugin(new SignUrl());
            $filesystem->addPlugin(new TemporaryUrl());
            $filesystem->addPlugin(new SignatureConfig());
            $filesystem->addPlugin(new SetBucket());
            $filesystem->addPlugin(new Verify());
            $filesystem->addPlugin(new Kernel());

            return $filesystem;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
