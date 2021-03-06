<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\File\Validator;

use FileUpload\File;
use FileUpload\Validator\Validator;

/**
 * Checks file system permissions.
 */
class PermissionsValidator implements Validator
{
    const BAD_PERMISSIONS_DIRECTORY = 0;
    const BAD_PERMISSIONS_FILE = 1;

    private $pathToUpload;

    private $fileName;

    /**
     * @param string $pathToUpload
     * @param string $fileName
     */
    public function __construct(string $pathToUpload, string $fileName)
    {
        $this->pathToUpload = $pathToUpload;
        $this->fileName = $fileName;
    }

    protected $errorMessages = array(
        self::BAD_PERMISSIONS_DIRECTORY => "Unable to create %s file in %s, please check file permissions",
        self::BAD_PERMISSIONS_FILE => "Unable to overwrite %s file, please check file permissions",
    );

    /**
     * @param array $messages
     * @return void
     */
    public function setErrorMessages(array $messages)
    {
        foreach ($messages as $key => $value) {
            $this->errorMessages[$key] = $value;
        }
    }

    /**
     * @param  File $file
     * @param  null|int $current_size
     * @return bool
     */
    public function validate(File $file, $current_size = null): bool
    {
        $isValid = true;
        if (!is_writable($this->pathToUpload)) {
            $file->error = sprintf($this->errorMessages[self::BAD_PERMISSIONS_DIRECTORY], $this->fileName, $this->pathToUpload);
            $isValid = false;
        }
        $filePath = $this->pathToUpload . DIRECTORY_SEPARATOR . $this->fileName;
        if (file_exists($filePath)
            && !is_writable($filePath)
        ) {
            $file->error = sprintf($this->errorMessages[self::BAD_PERMISSIONS_FILE], $this->fileName);
            $isValid = false;
        }

        return $isValid;
    }
}
