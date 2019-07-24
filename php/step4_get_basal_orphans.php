<?php


$storage = "/scratch/timrpeterson/MORPHEOME/";

if ( ($handle1 = fopen($storage."mesh_gene_paper_count_limited_homologs_top10_with_aliases_basal_orphans_php.csv", "w") ) !== FALSE) {

    if ( ($handle = fopen($storage."mesh_gene_paper_count_limited_homologs_top10_with_aliases_php.csv", "r") ) !== FALSE) {

        $row = 0;
        $col_names = [];
        $genes_keys = [1,3,5,7,9,11,13,15,17,19];

        while (($line = fgetcsv($handle,  0, ",")) !== FALSE){
            
            $orphan_pairs = [];
            for($genes_keys as $row){

                if ( ($handle0 = fopen($storage."interaction_correlations_basal/".$line[$row]."-2019q2-pearsons-python.csv", "r") ) !== FALSE) {

                    while (($line0 = fgetcsv($handle,  0, ",")) !== FALSE){
                        
                        $orphan_pairs[$row][] = $line0;
                    }
                }
                fclose($handle0);                
            }


            if(isset($new_arr[$line[0]])){

                $line0 = $line;
            
                fputcsv($handle1, array_merge($line0, $new_arr[$line[0]]));

            }

        }
        
    }
    fclose($handle);

}
fclose($handle1);