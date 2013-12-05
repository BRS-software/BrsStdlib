<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\Math;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0 2013-12-05
 */
abstract class Geometry
{
    /**
     * Calculate coordinates for some point after rotation with respect to axis
     * @param integer $x coordinate X for point
     * @param integer $y coordinate Y for point
     * @param integer $ax coordinate X for rotation axis
     * @param integer $ay coordinate Y for rotation axis
     * @param integer $a angle in degrees of rotation
     * @return array new coordinates of the point ['x' => (int), 'y' => (int)]
     */
    public static function rotatePoint($x, $y, $ax, $ay, $a)
    {
        $a = $a * M_PI / 180; // convert to radians
        $xr = ($x - $ax) * cos($a) - ($y - $ay) * sin($a) + $ax;
        $yr = ($x - $ax) * sin($a) + ($y - $ay) * cos($a) + $ay;
        return ['x' => $xr, 'y' => $yr];
    }

    /**
     * Calculating coordinates of rectangular when other rectangular object
     * will be inscribed in it and will be rotates relative to given the axis
     * @param integer $x coordinate X for object (left)
     * @param integer $y coordinate Y for object (top)
     * @param integer $w width of object
     * @param integer $w height of object
     * @param integer $a angle in degrees of rotation
     * @param integer $ax coordinate X for object rotation axis (default in to the center of object)
     * @param integer $ay coordinate Y for object rotation axis (default in to the center of object)
     * @return array coordinates of square like this ['x' => (int), 'y' => (int)]
     */
    public static function calcCoordinatesForRotateObjectInscribedInRectangular($x, $y, $w, $h, $a, $ax = null, $ay = null)
    {
        if ($ax === null) {
            $ax = $x + $w / 2;
        }
        if ($ay === null) {
            $ay = $y + $h / 2;
        }
        $r1 = self::rotatePoint($x, $y, $ax, $ay, $a);
        $r2 = self::rotatePoint($x + $w, $y, $ax, $ay, $a);
        $r3 = self::rotatePoint($x, $y + $h, $ax, $ay, $a);
        $r4 = self::rotatePoint($x + $w, $y + $h, $ax, $ay, $a);

        return [
            'x' => (int) round(min([$r1['x'], $r2['x'], $r3['x'], $r4['x']])),
            'y' => (int) round(min([$r1['y'], $r2['y'], $r3['y'], $r4['y']])),
        ];
    }
}