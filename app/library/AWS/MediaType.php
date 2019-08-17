<?php
namespace App\Aws;

/**
 * \App\Aws\MediaType
 *
 * @package App\Aws
 */
class MediaType
{
    public $imageTypes = [
        'image/gif',
        'image/jpg',
        'image/png',
        'image/bmp',
        'image/jpeg'
    ];
    public $videoTypes = [
        'video/mp3',
        'video/mp4'
    ];

    public $documentTypes  = [
        'text/plain',
        'application/pdf'
    ];
    /**
     * Attempt to determine the real file type of a file.
     *
     * @param string $extension Extension (eg 'jpg')
     *
     * @return boolean
     */
    public function imageCheck($extension)
    {
        $allowedTypes = $this->imageTypes;
        return in_array($extension, $allowedTypes);
    }

    /**
     * @param $extension
     * @return bool
     */
    public function videoCheck($extension)
    {
        $allowedTypes = $this->videoTypes;
        return in_array($extension, $allowedTypes);
    }

    /**
     * get file extension allowed for upload from db
     * @return array
     */
    public function getExtensionAllowed()
    {
        return array_merge($this->imageTypes, $this->videoTypes, $this->documentTypes);
    }

    public function checkExtension($extension)
    {
        $allowedTypes = $this->getExtensionAllowed();
        return in_array($extension, $allowedTypes);
    }
}
