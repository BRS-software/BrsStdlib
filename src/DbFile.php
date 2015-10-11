<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0 2013-11-06
 */
class DbFile
{
    protected $file;
    protected $data = array();

    public function __construct($file)
    {
        $pathInfo = pathinfo($file);
        $this->file = sprintf('%s/%s.json', $pathInfo['dirname'], $pathInfo['filename']);
        if (file_exists($this->file)) {
            $d = json_decode(file_get_contents($this->file), true);
            $this->data = is_array($d) ? $d : [];
        }
    }

    public function __set($key, $data)
    {
        $this->data[$key] = $data;
        file_put_contents($this->file, json_encode($this->data, JSON_PRETTY_PRINT));
        return $this;
    }

    public function __get($key)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    public function toArray()
    {
        return $this->data;
    }
}