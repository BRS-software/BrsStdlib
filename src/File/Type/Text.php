<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\File\Type;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 */
class Text extends Generic
{
    const TYPE_HTML = 'html';
    const TYPE_PLAIN = 'plain';

    protected $type = self::TYPE_PLAIN;

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getContentType()
    {
        return 'text/' . $this->getType();
    }
}