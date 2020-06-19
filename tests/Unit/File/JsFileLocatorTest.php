<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\Tests\Unit\File;

use org\bovigo\vfs\vfsStream;
use OxidEsales\EcondaTrackingComponent\File\JsFileLocator;
use PHPUnit\Framework\TestCase;

class JsFileLocatorTest extends TestCase
{
    const TRACKING_CODE_DIRECTORY_NAME = 'test_dir';

    public function testGetFileName()
    {
        $locator = new JsFileLocator('',static::TRACKING_CODE_DIRECTORY_NAME,'file_name', '', 1);

        $this->assertSame('file_name', $locator->getFileName());
    }

    public function testGetDirectoryName()
    {
        $locator = new JsFileLocator('root_path', static::TRACKING_CODE_DIRECTORY_NAME, '', '', 1);
        $this->assertSame(static::TRACKING_CODE_DIRECTORY_NAME, $locator->getDirectoryName());
    }

    public function testGetJsDirectoryLocationWhenMainShop()
    {
        $locator = new JsFileLocator('root_path',static::TRACKING_CODE_DIRECTORY_NAME, '', '', 1);
        $this->assertSame('root_path/'.static::TRACKING_CODE_DIRECTORY_NAME, $locator->getJsDirectoryLocation());
    }

    public function testGetJsDirectoryLocationWhenSubShop()
    {
        $locator = new JsFileLocator('root_path',static::TRACKING_CODE_DIRECTORY_NAME, '', '', 2);
        $this->assertSame('root_path/'.static::TRACKING_CODE_DIRECTORY_NAME.'/2', $locator->getJsDirectoryLocation());
    }

    public function testGetJsFileLocation()
    {
        $locator = new JsFileLocator('root_path',static::TRACKING_CODE_DIRECTORY_NAME, 'file_name', '', 1);
        $expectedLocation = 'root_path'
            . '/' . static::TRACKING_CODE_DIRECTORY_NAME
            . '/file_name';

        $this->assertSame($expectedLocation, $locator->getJsFileLocation());
    }

    public function testGetJsFileUrlWhenMainShop()
    {
        $structure = [
            static::TRACKING_CODE_DIRECTORY_NAME => [
                'file_name' => 'some content'
            ]
        ];
        $rootPath = vfsStream::setup(
            'root_path',
            NULL,
            $structure
        );

        $locator = new JsFileLocator(
            $rootPath->url(),
            static::TRACKING_CODE_DIRECTORY_NAME,
            'file_name',
            'oxideshop.local/out',
            1
        );

        $expectedUrl = 'oxideshop.local/out'
            . '/' . static::TRACKING_CODE_DIRECTORY_NAME
            . '/file_name?' . $rootPath->filemtime();

        $this->assertSame($expectedUrl, $locator->getJsFileUrl());
    }

    public function testGetJsFileUrlWhenSubShop()
    {
        $structure = [
            static::TRACKING_CODE_DIRECTORY_NAME => [
                '2' => [
                    'file_name' => 'some content'
                ]
            ]
        ];
        $rootPath = vfsStream::setup(
            'root_path',
            NULL,
            $structure
        );

        $locator = new JsFileLocator(
            $rootPath->url(),
            static::TRACKING_CODE_DIRECTORY_NAME,
            'file_name',
            'oxideshop.local/out',
            2
        );

        $expectedUrl = 'oxideshop.local/out'
            . '/' . static::TRACKING_CODE_DIRECTORY_NAME
            . '/2'
            . '/file_name?' . $rootPath->filemtime();

        $this->assertSame($expectedUrl, $locator->getJsFileUrl());
    }

    public function testGetJsFileUrlWhenFileNotExists()
    {
        $structure = [
            static::TRACKING_CODE_DIRECTORY_NAME => [
                'file_name' => 'some content'
            ]
        ];
        $rootPath = vfsStream::setup(
            'root_path',
            NULL,
            $structure
        );

        $locator = new JsFileLocator(
            $rootPath->url(),
            static::TRACKING_CODE_DIRECTORY_NAME,
            'not_existing_file',
            'oxideshop.local/out',
            1);
        $expectedUrl = 'oxideshop.local/out'
                       . '/' . static::TRACKING_CODE_DIRECTORY_NAME
                       . '/not_existing_file';

        $this->assertSame($expectedUrl, $locator->getJsFileUrl());
    }
}
