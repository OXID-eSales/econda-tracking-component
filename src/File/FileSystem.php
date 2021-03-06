<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\File;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem as SymfonyFileSystem;

/**
 * Class responsible for file system actions.
 */
class FileSystem
{
    private $filesystem;

    /**
     * @param SymfonyFileSystem $filesystem
     */
    public function __construct(SymfonyFileSystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $pathToDirectory
     *
     * @return bool
     */
    public function createDirectory($pathToDirectory): bool
    {
        try {
            $this->filesystem->mkdir($pathToDirectory);
            return true;
        } catch (IOException $exception) {
            return false;
        }
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isWritable(string $path): bool
    {
        return is_writable($path);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function isFilePresent(string $path): bool
    {
        return $this->filesystem->exists($path);
    }
}
