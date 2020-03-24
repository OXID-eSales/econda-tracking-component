<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\Tests\Integration\File;

use OxidEsales\EcondaTrackingComponent\Tests\Unit\File\VirtualFileHelperTrait;
use Symfony\Component\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

class FileSystemTest extends TestCase
{
    use VirtualFileHelperTrait;

    private $virtualDirectory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->virtualDirectory = $this->createVirtualPath();
    }

    public function testDirectorySuccessfulCreation()
    {
        $fileSystem = new \OxidEsales\EcondaTrackingComponent\File\FileSystem(new Filesystem());

        $this->assertTrue($fileSystem->createDirectory($this->virtualDirectory.'/testDirectory'));
        $this->assertTrue(is_dir($this->virtualDirectory.'/testDirectory'));
    }

    public function testDirectoryUnsuccessfulCreation()
    {
        $fileSystem = new \OxidEsales\EcondaTrackingComponent\File\FileSystem(new Filesystem());

        $this->assertFalse($fileSystem->createDirectory('/not_existing_directory/testDirectory'));
        $this->assertFalse(is_dir('/not_existing_directory/testDirectory'));
    }

    public function testIfPathNotWritable()
    {
        $fileSystem = new \OxidEsales\EcondaTrackingComponent\File\FileSystem(new Filesystem());
        chmod($this->virtualDirectory, 555);

        $this->assertFalse($fileSystem->isWritable($this->virtualDirectory.'/testDirectory'));
    }

    public function testFileDoesNotExists()
    {
        $fileSystem = new \OxidEsales\EcondaTrackingComponent\File\FileSystem(new Filesystem());

        $this->assertFalse($fileSystem->isFilePresent($this->virtualDirectory . '/any_file'));
    }

    public function testFileExists()
    {
        $fileSystem = new \OxidEsales\EcondaTrackingComponent\File\FileSystem(new Filesystem());

        $this->assertTrue($fileSystem->isFilePresent($this->virtualDirectory.'/file'));
    }
}
