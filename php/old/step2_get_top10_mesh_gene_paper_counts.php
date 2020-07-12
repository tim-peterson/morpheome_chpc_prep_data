<?php

ini_set('memory_limit', '-1');

$storage = "/scratch/timrpeterson/MORPHEOME/";

if ( ($handle1 = fopen($storage."mesh_gene_paper_count_limited_homologs_top10_php.csv", "w") ) !== FALSE) {

    // "mesh_gene_intersect_limited.tsv"
    
    if ( ($handle = fopen("/scratch/njacobs/mesh_gene_intersect_limited_homologs.tsv", "r") ) !== FALSE) {

        $row = 0;
        $col_names = [];

        while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){
            
            $line0 = $line;
            $mesh_name = array_shift($line0);
          
            $row++;
        
            if($row == 1){  
                
                $col_names = $line0;
             
                continue; 
            }

            arsort($line0);

            $top10 = array_slice($line0, 0, 10, true);

            $new_arr = [str_replace('"', "", $mesh_name)];
            foreach($top10 as $k => $v){
                $new_arr[] = $col_names[$k];
                $new_arr[] = $v;
            }

            fputcsv($handle1, $new_arr);


        }
        
    }
    fclose($handle);

}
fclose($handle1);

