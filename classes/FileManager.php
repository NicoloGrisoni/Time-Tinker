<?php
    class FileManager {
        public static function GetRowFromFile($fileName) {
            if(!file_exists("../files/$fileName")) {
                return null;
            } else {
                $contents = file_get_contents("../files/$fileName");
                if ($contents === false) {
                    return null;
                } else {
                    return explode("\r\n", $contents);
                }
            }
        }

        public static function GetFieldsFromRow($separator, $row) {
            if (empty($separator) || empty($row)) {
                return null;
            } else {
                return explode($separator, $row);
            }
        }

        public static function InsertContent($fileName, $contents, $append) {
            if(!file_exists("../files/$fileName")) {
                return null;
            } else {
                if (!is_bool($append)) {
                    return null;
                } else {
                    if ($append && count(FileManager::GetRowFromFile($fileName)) > 0) {
                        file_put_contents("../files/$fileName", "\r\n$contents", FILE_APPEND);
                    } else {
                        file_put_contents("../files/$fileName", $contents);
                    }
                }
            }
        }
    }
?>