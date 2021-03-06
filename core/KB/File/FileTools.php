<?php

namespace KB\File;

class FileTools
{
    /**
     * @param $fullPathName Ex: /home/user01/workspace/POO.dev/log.ext
     * @throws \Exception
     */
    static public function createFileIfNotExists($fullPathName)
    {
        if (!file_exists($fullPathName)) {
            if (!is_dir(dirname($fullPathName))) {
                if (!mkdir(dirname($fullPathName), '0777', true)) {
                    throw new \Exception(sprintf('Unable to create file: "%s"', $fullPathName));
                }
            } else {
                if (!touch($fullPathName)) {
                    throw new \Exception(sprintf('Unable to create file: "%s"', $fullPathName));
                }
            }

            touch($fullPathName);
        }
    }
} 