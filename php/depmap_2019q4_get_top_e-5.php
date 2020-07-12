<?php

ini_set('memory_limit', '-1');


//ini_set('auto_detect_line_endings', true);


$base_dir = "/home/jiwpark00/timrpeterson/njacobs/";


$files = array_diff(scandir($base_dir."interactions_correlation_basal/"), array('.', '..'));


if (($handle0 = fopen($base_dir."depmap_2019q4_get_top_e-5.csv", "w")) !== FALSE) {

    $cnt0 = 0;

    foreach($files as $file){

        if(strpos($file, "._")!==false) continue;

        $cnt = 0;

        $genes_top_5 = [];

        if ( ($handle = fopen($base_dir."interactions_correlation_basal/".$file, "r") ) !== FALSE) {

            while (($line = fgetcsv($handle,  0, ",")) !== FALSE){

                if($cnt == 0){

                        $gene = $line[0];

                    $cnt++;
                    continue;
                }
                else{

                    if((float)$line[2] < .0001){


                        $genes_top_5[] = $line[0]; //[$line[0] => $line[1]];
                        
                    }                        
                }
            }
        }

        fputcsv($handle0, array_merge([$gene], $genes_top_5));

        if($cnt0 > 100){
           // echo 'gt 100';
           // break;
        }

        $cnt0++;
    }

}
fclose($handle0);

