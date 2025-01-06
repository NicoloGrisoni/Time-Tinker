<?php
    /**
     * Classe statica utilizzata per gestire le operazioni da effettuare sui file:
     * 1) Lettura di tutte le righe
     * 2) Divisione di ciascuna riga in campi in base al separatore
     * 3) Sovrascrittura e scrittura in append in un file
     */
    class FileManager {
        /**
         * Metodo per ottenre tutte le righe presenti all'interno di un file
         * @param string $file Stringa che rappresenta il file che si vuole leggere
         * @return array|bool|null:
         *      1) Vettore di stringhe rappresentanti le righe nel caso in cui sia tutto corretto
         *      2) False nel caso in cui andasse storto qualcosa durante la divisione dell'intero contenuto del file in righe
         *      3) Null nel caso in cui il parametro non sia una stringa, il file non esista o ci sia stato un errore durante la lettura del file
         */
        public static function GetRowFromFile($file) {
            if (!is_string($file)) {
                return null;
            } else {
                if(!file_exists("../files/$file")) {
                    return null;
                } else {
                    $contents = file_get_contents("../files/$file");
                    if ($contents === false) {
                        return null;
                    } else {
                        return explode("\r\n", $contents);
                    }
                }
            }
        }

        /**
         * Metodo per ottenere tutti i campi presenti in una riga, divisi da un separatore
         * @param string $separator Stringa che rappresenta il separatore dei campi nella riga
         * @param string $row Stringa che rappresenta l'intera riga nella quale sono presenti i campi da separare
         * @return array|bool|null:
         *      1) Vettore di stringhe rappresentanti i campi nel caso in cui sia tutto corretto
         *      2) False nel caso in cui andasse storto qualcosa durante la divisione della riga in campi
         *      3) Null nel caso in cui i parametri non siano stringhe o siano stringhe vuote
         */
        public static function GetFieldsFromRow($separator, $row) {
            if (!is_string($separator) || !is_string($row)) {
                return null;
            } else if (empty($separator) || empty($row)) {
                return null;
            } else {
                return explode($separator, $row);
            }
        }

        /**
         * Metodo per scrivere in un file sovrascrivendo il contenuto o aggiungendo contenuto alla fine del file (modalità append)
         * @param string $file Stringa che rappresenta il file nel quale si vuole scrivere
         * @param string $contents Stringa che rappresenta il contenuto da inserire nel file
         * @param bool $append Variabile booleana che rappresenta la modalità nella quale scrivere:
         *                          true --> append
         *                          false --> sovrascrittura
         * @return bool|null:
         *      1) Variabile booleana nel caso in cui l'operazione di scrittura avvenga:
         *              true --> scrittura avvenuta correttamente
         *              false --> errori durante la scrittura
         *      2) Null nel caso in cui il file non esista o se i parametri non sono del tipo di dato che dovrebbero essere
         */
        public static function InsertContent($file, $contents, $append) {
            if(!file_exists("../files/$file")) {
                return null;
            } else {
                if (!is_string($file) || !is_string($contents) || !is_bool($append)) {
                    return null;
                } else {
                    $result = null;
                    if ($append && count(FileManager::GetRowFromFile($file)) > 0 && FileManager::GetRowFromFile($file)[0] != "") {
                        $resut = file_put_contents("../files/$file", "\r\n$contents", FILE_APPEND);
                    } else {
                        $result = file_put_contents("../files/$file", $contents);
                    }

                    if (is_numeric($result)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }

        /**
         * Metodo per creae un file se non esiste
         * @param string $fileName Stringa che rappresenta il nome del file da creare
        */
        public static function createFile($fileName) {
            if (!file_exists("../files/$fileName")) {
                file_put_contents("../files/$fileName", "");
            }
        }
    }
?>