<?php

namespace App\Aws;

use Aws\S3\S3Client;
use Exception;
use Phalcon\Mvc\User\Component;

/**
 * Class Storage
 * @package App\Aws
 */
class Storage extends Component
{
    /**
     * @var object
     */
    protected $config;

    public function __construct()
    {
        $this->configure();
        $this->client = $this->s3Client();
        $this->mediaType = new MediaType();
    }

    public function configure()
    {
        /** @noinspection PhpIncludeInspection */
        $config = require config_path('aws.php');
        $this->config = $config['aws'];
    }

    /**
     * @param string $fileName it is a filename
     * @param string $path it is temp file upload on server
     * @param string $privacy
     *
     * @return bool
     */
    public function upload($fileInfo = [])
    {
        if (!file_exists($fileInfo['path'])) {
            return false;
        }
        if (!isset($fileInfo['acl'])) {
            $fileInfo['acl'] = 'public-read';
        }
        if (!isset($fileInfo['contentType'])) {
            $fileInfo['contentType'] = 'application/octet-stream';
        }
        try {
            $this->client->putObject([
                'Bucket' => $this->config->bucket,
                'Key'    => $fileInfo['fileName'],
                'Body'   => fopen($fileInfo['path'], 'rb'),
                'ACL'    => $fileInfo['acl'],
                'ContentType' => $fileInfo['contentType']
            ]);
            return true;
        } catch (Exception $e) {
            $this->logger->error(
                "There was an error uploading the file to aws"
                . $e->getMessage()
            );
            return false;
        }
    }

    /**
     * @param $fileName
     * @param object $fileInfo
     *
     * @return bool
     */
    public function uploadImage($fileName, $fileInfo)
    {
        if (!$this->mediaType->imageCheck($fileInfo->getRealType())) {
            $this->logger->error('You need chose format image');
            return false;
        }
        $file['fileName'] = $fileName;
        $file['path'] = public_path($fileName);
        $file['contentType'] = $fileInfo->getRealType();
        return $this->upload($file);
    }

    /**
     * @param $pathToFile
     *
     * @return mixed|null
     */
    public function get($pathToFile)
    {
        try {
            return $this->client->getObject([
                'Bucket' => $this->config->bucket,
                'Key' => $pathToFile
            ])->get('Body');
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @param $pathToFile
     * @return mixed
     */
    public function getContent($pathToFile)
    {
        return $this->get($pathToFile)->getContents();
    }

    /**
     * @param $pathToFile
     *
     * @return string
     */
    public function getObjectUrl($pathToFile)
    {
        try {
            return $this->client->getObjectUrl(
                $this->config->bucket,
                $pathToFile
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @param $pathToFile
     *
     * @return string
     */
    public function createPreSignedRequest($pathToFile)
    {
        try {
            $cmd = $this->client->getCommand('GetObject', [
                'Bucket' => $this->config->bucket,
                'Key' => $pathToFile
            ]);

            $request = $this->client->createPresignedRequest($cmd, '+20 minutes');
            // Get the actual pre signed-url
            return (string)$request->getUri();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @return S3Client
     */
    protected function s3Client()
    {
        $s3Client = new S3Client(
            [
                'version' => 'latest',
                'region'  => $this->config->region,
                'credentials' => [
                    'key' => $this->config->key,
                    'secret' => $this->config->secret
                ]
            ]
        );
        return $s3Client;
    }
}
