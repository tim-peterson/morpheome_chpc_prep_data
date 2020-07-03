<?php

ini_set('memory_limit', '-1');

$storage = "/scratch/timrpeterson/MORPHEOME/";

if ( ($handle1 = fopen($storage."mesh_gene_intersect_count_top5_mesh_php_2020.csv", "w") ) !== FALSE) {

    // "mesh_gene_intersect_limited.tsv"
    
    if ( ($handle = fopen("/scratch/njacobs/mesh_gene_intersect.tsv", "r") ) !== FALSE) {

        $row = 0;
        $col_names = [];

        $mesh_terms = [];

        $genes = [];
        while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){
 
            $row++;
        
            if($row == 1){  
                
                $col_names = $line;
             
                $THUMPD3_AS1_key = array_search("THUMPD3-AS1", $line);
               // $genes = array_flip($col_names);

                continue; 
            }

            $line0 = $line;
            $mesh_name = array_shift($line0);

            unset($line0[$THUMPD3_AS1_key]);

            arsort($line0);

            $top10 = array_slice($line0, 0, 5, true);


            /*$mesh_terms[] = $mesh_name;

            foreach($line0 as $k => $v){
                $col_names[$k] = [$mesh_name => $v];
            }*/

            $new_arr = [str_replace('"', "", $mesh_name)];
            foreach($top10 as $k => $v){
                $new_arr[] = $col_names[$k];

                $genes[] = $col_names[$k];
                $new_arr[] = $v;
            }


            fputcsv($handle1, $new_arr);


        }

        $genes = array_unique($genes);

        print_r("total MeSH term count: ".$row);
        echo "\r\n";

        echo "total genes in top 5: ";
         echo count($genes);
         echo "/";
         echo count($col_names);
         echo " : ";
        print_r(count($genes)/count($col_names));
        
    }
    fclose($handle);

}
fclose($handle1);

