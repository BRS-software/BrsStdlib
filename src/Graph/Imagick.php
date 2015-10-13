<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\Graph;

use ImagickPixel;
use Brs\Stdlib\Math\Geometry;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0
 */
class Imagick extends \Imagick
{
    protected $imageX = 0;
    protected $imageY = 0;

    public function resizeImageToDimensions($width, $height, $dimensionsUnit, $dpi)
    {
        $width = Size2px::conv2px($width, $dimensionsUnit, $dpi);
        $height = Size2px::conv2px($height, $dimensionsUnit, $dpi);
        $this->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 0.9, false);
        return $this;
    }

    public function setImageX($imageX)
    {
        $this->imageX = $imageX;
        return $this;
    }

    public function getImageX()
    {
        return $this->imageX;
    }

    public function setImageY($imageY)
    {
        $this->imageY = $imageY;
        return $this;
    }

    public function getImageY()
    {
        return $this->imageY;
    }

    public function setImageCoordinates($x, $y)
    {
        $this->setImageX($x);
        $this->setImageY($y);
        return $this;
    }

    public function getImageCoordinates()
    {
        return [$this->getImageX(), $this->getImageY()];
    }

    public function rotateImageAroundAxis($degrees, $ax = null, $ay = null)
    {
        if ($degrees) {
            $newCoordinates = Geometry::calcCoordinatesForRotateObjectInscribedInRectangular(
                0,
                0,
                $this->getImageWidth(),
                $this->getImageHeight(),
                $degrees,
                $ax,
                $ay
            );

            $this
                ->setImageX($this->getImageX() + $newCoordinates['x'])
                ->setImageY($this->getImageY() + $newCoordinates['y'])
            ;

            // $background = 'graya(50%, 0.5)';
            // $this->rotateImage($background, $degrees);
            $this->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
            $this->distortImage(Imagick::DISTORTION_SCALEROTATETRANSLATE, [$this->getImageWidth()/2, $this->getImageHeight()/2, 1, $degrees], true);
        }
        return $this;
    }

    public function autoRotateImage()
    {
        $orientation = $this->getImageOrientation();

        switch($orientation) {
            case \Imagick::ORIENTATION_BOTTOMRIGHT:
                $this->rotateimage("#000", 180); // rotate 180 degrees
                break;

            case \Imagick::ORIENTATION_RIGHTTOP:
                $this->rotateimage("#000", 90); // rotate 90 degrees CW
                break;

            case \Imagick::ORIENTATION_LEFTBOTTOM:
                $this->rotateimage("#000", -90); // rotate 90 degrees CCW
                break;
        }

        // Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image!
        $this->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);
        return $this;
    }
}