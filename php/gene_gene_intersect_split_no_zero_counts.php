<?php

ini_set('memory_limit', '-1');

$output_path = "/home/jiwpark00/timrpeterson/njacobs/gene_gene_intersect_split_no_zeros/";


/* NOTE: don't use, because it disrupts rows and columns alignment of genes if one removes the zeros */

$input_path = "/home/jiwpark00/timrpeterson/njacobs/gene_gene_intersect_split/";

$files = array_diff(scandir($input_path), array('.', '..'));

foreach($files as $file){


    if ( ($handle1 = fopen($output_path.$file."_no_zeros_.csv", "w") ) !== FALSE) {


        if ( ($handle = fopen($input_path.$file, "r") ) !== FALSE) {

            $cnt = 0;

            while (($line = fgetcsv($handle,  0, ",")) !== FALSE){

                if($cnt == 0){  
                    
                    fputcsv($handle1, $line);

                    $cnt++;
                    continue; 
                }

                $line0 = $line;

                $line1 = array_filter($line0);


                fputcsv($handle1, $line1);

            }
            
        }
        fclose($handle);

    }
    fclose($handle1);

}




