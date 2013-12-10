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
 * @version 1.0 2013-12-05
 */
class Imagick extends \Imagick
{
    protected $imageX = 0;
    protected $imageY = 0;

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

    public function rotateImageAroundAxis($background, $degrees, $ax = null, $ay = null)
    {
        if ($degrees) {
            $newCoordinates = Geometry::calcCoordinatesForRotateObjectInscribedInRectangular(
                $this->getImageX(),
                $this->getImageY(),
                $this->getImageWidth(),
                $this->getImageHeight(),
                $degrees,
                $ax,
                $ay
            );
            // dbgd($newCoordinates);
            $this
                ->setImageX($newCoordinates['x'])
                ->setImageY($newCoordinates['y'])
            ;
            $this->rotateImage($background, $degrees);
        }
        return $this;
    }
}