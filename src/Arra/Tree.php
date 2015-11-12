<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Stdlib\Arra;

use Closure;
use __;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 */
abstract class Tree
{
    public static function toText(array $treeArray, Closure $labelFn = null, $childrenKey = 'children', $identation = '')
    {
        $txtTree = '';
        foreach ($treeArray as $v) {
            if ($labelFn) {
                $label = $labelFn($v);
            } else {
                $tmp = $v;
                unset($tmp[$childrenKey]);
                $label = implode(', ', $tmp);
            }
            $txtTree .= $identation . $label . "\n";
            if (! empty($v[$childrenKey])) {
                $txtTree .= static::toText($v[$childrenKey], $labelFn, $childrenKey, $identation . '   ');
            }
        }
        return $txtTree;
    }

    /**
     * Build array tree from flat structure.
     * @param array $data Flat array
     * @param string $idKey Key with id value
     * @param string $parentKey Key with parent id
     * @param string $childrenKey The key for children in the parent item
     * @param Closure $dataFn Function creates leaf of tree
     * @return array Array with tree structure
     */
    public static function fromFlat(array $data, $idKey, $parentKey, $childrenKey = 'children', Closure $dataFn = null)
    {
        $new = [];
        foreach ($data as $v){
            $v = (array) $v;
            if ($dataFn) {
                $data = $dataFn($v);
                $data[$idKey] = $v[$idKey];
            } else {
                $data = $v;
            }
            $new[$v[$parentKey]][] = $data;
        }
        return self::crtTree($new, $new[1], $idKey, $childrenKey);
    }

    private static function crtTree(&$list, $parent, $idKey, $childrenKey)
    {
        $tree = [];
        foreach ($parent as $k=>$l){
            if(isset($list[$l[$idKey]])){
                $l[$childrenKey] = self::crtTree($list, $list[$l[$idKey]], $idKey, $childrenKey);
            }
            $tree[] = $l;
        }
        return $tree;
    }
}