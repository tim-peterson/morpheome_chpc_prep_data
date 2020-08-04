<?php


ini_set('memory_limit', '-1');

$base_dir = "/home/jiwpark00/timrpeterson/njacobs/";

 $sql_tables = [
    "bioplex_v1_293T_minimum_info.csv",
    "bioplex_v2_293T_minimum_info.csv",
    "bioplex_v3_293T_minimum_info.csv",
    "bioplex_v3_HCT116_minimum_info.csv",

];

$ppi = [];

foreach($sql_tables as $file){
    
    if ( ($handle = fopen($base_dir."bioplex/".$file, "r") ) !== FALSE) {

        $i = 0;
        while (($line = fgetcsv($handle,  0, ",")) !== FALSE){

            if($i==0){
                $i++; continue;
            }

            if(!isset($ppi[$line[0]])){
                $ppi[$line[0]] = [$line[1] => $line[1]];
            }
            else{
                $ppi[$line[0]][$line[1]] = $line[1];
            }

            if(!isset($ppi[$line[1]])){
                $ppi[$line[1]] = [$line[0] => $line[0]];
            }
            else{
                $ppi[$line[1]][$line[0]] = $line[0];
            }


            $i++;
        }

    }
    fclose($handle);

}


$storage_path = "/home/jiwpark00/timrpeterson/njacobs/interactions_correlation_basal/";


$files = array_diff(scandir($storage_path), array('.', '..', '._'));

//$files = array_reverse($files);
$files1 = [];

foreach($files as $key => $val){

    $gene_arr = explode("-", $val);

    $files1[$gene_arr[0]] = $val;
}
//print_r(count($files));

$cnt = 0;
$ppi1 = [];
$ppi2 = [];
foreach($ppi as $key => $arr){

    if(isset($files1[$key])){

        $citations = [];

        foreach($arr as $k => $v){

            if(!isset($files1[$k])) continue;

            if ( ($handle = fopen($storage_path.$files1[$k], "r") ) !== FALSE) {

                $cnt0 = 0;

                while (($line = fgetcsv($handle,  0, ",")) !== FALSE){

                    if($line[2] < 0.05){

                        if(!isset($ppi1[$key])){

                            $ppi1[$key] = [$k => $k];
                            
                        }
                        else $ppi1[$key][$k] = $k;
                    }
                    else{

                        if(!isset($ppi2[$key])){

                            $ppi2[$key] = [$k => $k];
                            
                        }
                        else $ppi2[$key][$k] = $k;

                    }

                }
            }

        }
    }


    if($cnt > 5) break;
    echo "\r\n";
    echo $cnt++;
    echo "\r\n";

}


$base_path = "/home/jiwpark00/timrpeterson/MORPHEOME/FOR_PAPER/Figure2-introduce-morpheome/";


if ( ($handle1 = fopen($base_path."intersect_bioplex_depmap.csv", "w") ) !== FALSE) {

    foreach($ppi1 as $gene => $arr){

        foreach($arr as $k => $v){

            fputcsv($handle1, [$gene, $k, "pval_lt_0.05"]);
           
        } 
    }

    foreach($ppi2 as $gene => $arr){

        foreach($arr as $k => $v){

            fputcsv($handle1, [$gene, $k, "pval_gt_0.05"]);
           
        } 
    }


}
fclose($handle1);








