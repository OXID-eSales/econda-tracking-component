<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\File\Validator;

use FileUpload\File;
use FileUpload\Validator\Validator;

/**
 * Checks if in provided file exists provided needle.
 */
class ContentValidator implements Validator
{
    const NEEDLE = 'econda';

    const FILE_IS_WRONG = 0;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @var array
     */
    protected $errorMessages = array(
        self::FILE_IS_WRONG => 'Provided file is not %s file',
    );

    /**
     * @inheritdoc
     */
    public function setErrorMessages(array $messages)
    {
        foreach ($messages as $key => $value) {
            $this->errorMessages[$key] = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function validate(File $file, $current_size = null): bool
    {
        $isValid = true;
        $fileContents = (string) file_get_contents($file);
        if (strpos($fileContents, static::NEEDLE) === false) {
            $isValid = false;
            $file->error = sprintf($this->errorMessages[self::FILE_IS_WRONG], $this->fileName);
        }

        return $isValid;
    }
}
