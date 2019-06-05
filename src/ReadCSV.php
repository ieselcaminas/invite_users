<?php 
namespace IESElCaminas;
class ReadCSV{
    //https://www.php.net/manual/es/language.generators.syntax.php
    public static function read(){
        //https://stackoverflow.com/questions/9139202/how-to-parse-a-csv-file-using-php
        return array_map('str_getcsv', file('../csv/exemple.csv'));
    }
}