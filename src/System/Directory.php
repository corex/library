<?php

namespace CoRex\Support\System;

class Directory
{
    /**
     * Check if directory exists.
     *
     * @param string $path
     * @return boolean
     */
    public static function exist($path)
    {
        return is_dir($path);
    }

    /**
     * Check if directory is writable.
     *
     * @param string $path
     * @return boolean
     */
    public static function isWritable($path)
    {
        return is_writable($path);
    }

    /**
     * Make directory.
     *
     * @param string $path
     * @param integer $mode See mkdir() for options.
     */
    public static function make($path, $mode = 0777)
    {
        if (!is_dir($path)) {
            mkdir($path, $mode, true);
        }
    }

    /**
     * Get files in directory.
     *
     * @param string $path
     * @param string $criteria
     * @param boolean $dirs
     * @param boolean $files
     * @param boolean $recursive Default false.
     * @param string $subPath Do not use. Used in recursion. Default ''.
     * @return array
     */
    public static function entries($path, $criteria, $dirs, $files, $recursive = false, $subPath = '')
    {
        $entries = [];
        if (substr($path, -1) == '/') {
            $path = substr($path, 0, -1);
        }
        if (!is_dir($path)) {
            return $entries;
        }
        if ($handle = opendir($path)) {
            while ($entryName = readdir($handle)) {

                // Validate entry.
                if (substr($entryName, 0, 1) == '.') {
                    continue;
                }
                if (!fnmatch($criteria, $entryName)) {
                    continue;
                }

                // Prepare entry.
                $info = pathinfo($path . '/' . $entryName);
                $entry = [
                    'name' => $subPath . $entryName,
                    'path' => $path,
                    'basename' => isset($info['basename']) ? $info['basename'] : '',
                    'filename' => isset($info['filename']) ? $info['filename'] : '',
                    'extension' => isset($info['extension']) ? $info['extension'] : '',
                    'modified' => filemtime($path . '/' . $entryName),
                    'is_dir' => is_dir($path . '/' . $entryName)
                ];

                // Add to list.
                if ($dirs && $entry['is_dir'] || $files && !$entry['is_dir']) {
                    $entries[] = $entry;
                }

                // Recursive.
                if ($recursive && $entry['is_dir']) {
                    $recursiveEntries = static::entries(
                        $path . '/' . $entryName,
                        $criteria,
                        $dirs,
                        $files,
                        $recursive,
                        $entryName . '/'
                    );
                    $entries = array_merge($entries, $recursiveEntries);
                }
            }
            closedir($handle);
        }
        return $entries;
    }
}