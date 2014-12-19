<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\File;

use PHPExcel;
use PHPExcel_IOFactory;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0 2014-12-18
 */
class Excel extends AbstractFile
{
    protected $excel;
    protected $writer;

    public function __construct($name = null, PHPExcel $excel = null)
    {
        $this->setExcel($excel ?: new PHPExcel());
        if ($name) {
            $this->setName($name);
        }
    }

    public function getContentType()
    {
        return 'application/xls';
    }

    public function setExcel(PHPExcel $excel)
    {
        $this->excel = $excel;
        return $this;
    }

    public function getExcel()
    {
        return $this->excel;
    }

    public function getWriter()
    {
        if ($this->writer === null) {
            $this->writer = PHPExcel_IOFactory::createWriter($this->getExcel(), 'Excel5');
        }
        return $this->writer;
    }

    public function stream()
    {
        $this->getWriter()->save('php://output');
        return $this;
    }

    public function saveFile($path)
    {
        $this->getWriter()->save($path);
        return $this;
    }

    public function readFile($path)
    {
        // TODO
        throw new Exception\NotSupportedException('This method is not supported yet');
    }
}