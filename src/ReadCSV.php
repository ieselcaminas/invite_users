<?php 
namespace IESElCaminas;

class ReadCSV{
    private $files;

    public function __construct() {
        $this->files = $this->getFiles();

    }
    public function genCsvFile() {
        for ($i = 0; $i < count($this->files); $i++) {
            // Observe que $i es preservado entre yields
            yield $this->files[$i];
        }
    }
    //https://www.php.net/manual/es/language.generators.syntax.php
    public function read($fileName) {
        //https://stackoverflow.com/questions/9139202/how-to-parse-a-csv-file-using-php
        return array_map('str_getcsv', file("../csv/$fileName"));
    }
    public function getFiles(): array {
        $files = [];
        foreach (new \DirectoryIterator('../csv/') as $file) {
            if ($this->isCSVFile($file)) {
                $files[] = $file->getFilename();
            }
          }
        sort($files);
        return $files;
    }
    private function isCSVFile($file) : bool{
        if ($file->isFile() && ($file->getExtension() == 'csv') && ($file->getFilename() != 'exemple.csv'))  {
            return true;
        }
        return false;
    }
}