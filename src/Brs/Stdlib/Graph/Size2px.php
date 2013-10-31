<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\Graph;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0 2013-08-30
 */
abstract class Size2px
{
    // $oneInch = 25.4;
    /**
     * dpi = px / cal
     * cal = px / dpi
     * 1inch = 25,4mm
     * 1 mm = 1/25,4 inch
     * mm = (px * 25,4) / dpi
     * px = (mm * dpi) / 25,4
     */

    public static function conv2px($size, $inputUnit, $dpi = 72)
    {
        $mm = self::unitConv($size, $inputUnit, 'mm');
        return (int) round(($mm * $dpi) / 25.4);
    }

    public static function conv2size($px, $outputUnit, $dpi = 72)
    {
        $mm = ($px * 25.4) / $dpi;
        return self::unitConv($mm, 'mm', $outputUnit);
    }

    public static function unitConv($value, $inputUnit, $outputUnit)
    {
        switch (strtolower($inputUnit)) {
            case 'm':
                $si = $value;
                break;
            case 'dm':
                $si = $value/10;
                break;
            case 'cm':
                $si = $value/100;
                break;
            case 'mm':
                $si = $value/1000;
                break;
            default:
                throw new \InvalidArgumentException(
                    sprintf('invaild input unit "%s"', $inputUnit)
                );
        }

        switch (strtolower($outputUnit)) {
            case 'm':
                return $si;
            case 'dm':
                return $si*10;
            case 'cm':
                return $si*100;
            case 'mm':
                return $si*1000;
            default:
                throw new \InvalidArgumentException(
                    sprintf('invaild output unit "%s"', $inputUnit)
                );
        }
    }
}