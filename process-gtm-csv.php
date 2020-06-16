<?php

/**
 * @author Roshan Labh
 * @copyright 2019
 * @description process csv file exported from gtm (google tag manager) to extract specific keys and their values in another csv 
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

$row = 1;
$keysArr = array('e', 'url', 'page', 'refr', 'tv', 'tna', 'p', 'tz', 'lang', 'cs', 'res', 'cd', 'cookie', 'eid', 'dtm', 'vp', 'ds', 'vid', 'sid', 'duid', 'fp', 'f_pdf', 'f_qt', 'f_realp', 'f_wma', 'f_dir', 'f_fla', 'f_java', 'f_gears', 'f_ag', 'se_ca', 'se_ac', 'se_la', 'se_pr', 'se_va');

$outDir = 'processed';  // output dir, should be located at the same loaction.
$inDir = 'csv';     // input dir, in which all csv files exist, which needs to be processed.
$files = array_slice(scandir($inDir), 2);

foreach($files as $file_name) {
    $final = array();
    if (($handle = fopen("{$inDir}/$file_name", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            $str = $data[11];
            $sArr = explode("\n", $str);
            foreach($sArr as $str1) {
                $tmp = [];
                $decodeStr = rawurldecode($str1);
                $arr = explode('&', $decodeStr);
                foreach ($arr as $key => $value) {
                    $vArr = explode('=', $value);
                    $v0 = trim($vArr[0]);
                    $v1 = rawurldecode($vArr[1]);
                    $tmp[$v0] = $v1;
                }
            }
            
            foreach($keysArr as $v) {
                $data[$v] = $tmp[$v];
            }
            $final[] = array_values($data);
        
            $row++;
        }
        fclose($handle);
    }
    
    $fp = fopen("{$outDir}/$file_name", 'w');
    foreach ($final as $fields) {
        fputcsv($fp, $fields);
    }
    
    fclose($fp);
}

?>
