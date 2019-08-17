<?php
namespace App\Image;

use Phalcon\Mvc\User\Component;
use Phalcon\Image;
use Phalcon\Image\Adapter\Gd;

/**
 * Class ImageManager
 * @package App\Image
 */
class ImageManager extends Component
{
    /**
     * @param array $file
     *
     * @return Image\Adapter
     */
    public function resize($file = [])
    {
        $image = new Gd($file['path']);
        $width = $file['width'] ?? Image::WIDTH;
        $height = $file['height'] ?? Image::HEIGHT;

        $image->resize(
            $width,
            $height,
            Image::NONE
        );

        return $image->save($file['fileName']);
    }
}
