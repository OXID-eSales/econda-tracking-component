<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EcondaTrackingComponent\File;

use FileUpload\FileUpload;
use FileUpload\PathResolver\Simple;
use OxidEsales\EcondaTrackingComponent\File\Validator\ContentValidator;
use OxidEsales\EcondaTrackingComponent\File\Validator\ExtensionValidator;
use OxidEsales\EcondaTrackingComponent\File\Validator\PermissionsValidator;

/**
 * Factory class to build file uploader.
 */
class JsFileUploadFactory
{
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

    /**
     * @return \FileUpload\FileUpload
     */
    public function makeFileUploader(): FileUpload
    {

        $pathResolver = new Simple($this->pathToUpload);
        $filesystem = new \FileUpload\FileSystem\Simple();
        $validatorSimple = new \FileUpload\Validator\Simple('1M', []);
        $permissionsValidator = new PermissionsValidator($this->pathToUpload, $this->fileName);
        $contentValidator = new ContentValidator($this->fileName);
        $fileForUpload = $_FILES['file_to_upload'];
        $fileName = $fileForUpload['name'] ? $fileForUpload['name'] : '';
        $extensionValidator = new ExtensionValidator($fileName);
        $fileNameGenerator = new \FileUpload\FileNameGenerator\Custom($this->fileName);

        $fileUpload = new FileUpload($fileForUpload, $_SERVER);
        $fileUpload->setPathResolver($pathResolver);
        $fileUpload->setFileSystem($filesystem);
        $fileUpload->addValidator($validatorSimple);
        $fileUpload->addValidator($permissionsValidator);
        $fileUpload->addValidator($contentValidator);
        $fileUpload->addValidator($extensionValidator);
        $fileUpload->setFileNameGenerator($fileNameGenerator);

        return $fileUpload;
    }
}
