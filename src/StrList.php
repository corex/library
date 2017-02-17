<?php

namespace CoRex\Support;

class StrList
{
    private static $usortTag;

    /**
     * Count.
     *
     * @param string $list
     * @param string $separator
     * @return integer
     */
    public static function count($list, $separator)
    {
        return count(self::explode($separator, $list));
    }

    /**
     * Add.
     *
     * @param string $list
     * @param string $item
     * @param string $separator
     * @param string $tag Default ''.
     * @return string
     */
    public static function add($list, $item, $separator, $tag = '')
    {
        $items = self::explode($separator, $list);
        if (!in_array($tag . $item . $tag, $items)) {
            $items[] = $tag . $item . $tag;
        }
        return implode($separator, $items);
    }

    /**
     * Get.
     *
     * @param string $list
     * @param string $index
     * @param string $separator
     * @param string $tag Default ''.
     * @return string
     */
    public static function get($list, $index, $separator, $tag = '')
    {
        $items = self::explode($separator, $list);
        if (isset($items[$index])) {
            $item = $items[$index];
            if ($tag != '' && substr($item, 0, 1) == $tag && substr($item, -1) == $tag) {
                $item = substr($item, 1, -1);
            }
            return $item;
        }
        return '';
    }

    /**
     * Pos.
     *
     * @param string $list
     * @param string $item
     * @param string $separator
     * @param string $tag Default ''.
     * @return integer
     */
    public static function pos($list, $item, $separator, $tag = '')
    {
        $items = self::explode($separator, $list);
        $pos = array_search($tag . $item . $tag, $items);
        if ($pos === false) {
            $pos = -1;
        }
        return $pos;
    }

    /**
     * Remove.
     *
     * @param string $list
     * @param string $item
     * @param string $separator
     * @param string $tag Default ''.
     * @return string
     */
    public static function remove($list, $item, $separator, $tag = '')
    {
        $items = self::explode($separator, $list);
        $pos = self::pos($list, $item, $separator, $tag);
        if ($pos > -1 && isset($items[$pos])) {
            unset($items[$pos]);
        }
        return implode($separator, $items);
    }

    /**
     * Remove index.
     *
     * @param string $list
     * @param string $index
     * @param string $separator
     * @return string
     */
    public static function removeIndex($list, $index, $separator)
    {
        $items = self::explode($separator, $list);
        if (isset($items[$index])) {
            unset($items[$index]);
        }
        return implode($separator, $items);
    }

    /**
     * Exist.
     *
     * @param string $list
     * @param string $item
     * @param string $separator
     * @param string $tag Default ''.
     * @return boolean
     */
    public static function exist($list, $item, $separator, $tag = '')
    {
        $items = self::explode($separator, $list);
        return in_array($tag . $item . $tag, $items);
    }

    /**
     * Merge.
     *
     * @param string $list1
     * @param string $list2
     * @param boolean $sort Default false.
     * @param string $separator
     * @param string $tag Default ''.
     * @return string
     */
    public static function merge($list1, $list2, $sort, $separator, $tag = '')
    {
        self::$usortTag = $tag;
        $items1 = self::explode($separator, $list1);
        $items2 = self::explode($separator, $list2);
        if (count($items2) > 0) {
            foreach ($items2 as $item) {
                if (!in_array($item, $items1)) {
                    $items1[] = $item;
                }
            }
        }
        if ($sort) {
            usort($items1, ['self', 'sortCompare']);
        }
        return implode($separator, $items1);
    }

    /**
     * Sort compare (usort for merge).
     *
     * @param string $item1
     * @param string $item2
     * @return integer
     */
    private static function sortCompare($item1, $item2)
    {
        if (self::$usortTag != '') {
            if (substr($item1, 0, 1) == self::$usortTag && substr($item1, -1) == self::$usortTag) {
                $item1 = substr($item1, 1, -1);
            }
            if (substr($item2, 0, 1) == self::$usortTag && substr($item2, -1) == self::$usortTag) {
                $item2 = substr($item2, 1, -1);
            }
        }
        if ($item1 == $item2) {
            return 0;
        }
        return ($item1 < $item2) ? -1 : 1;
    }

    /**
     * Explode.
     *
     * @param string $separator
     * @param string $list
     * @return array
     */
    private static function explode($separator, $list)
    {
        if ($list != '') {
            return explode($separator, $list);
        }
        return [];
    }
}