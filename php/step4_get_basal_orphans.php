<?php

ini_set('memory_limit', '-1');

$storage = "/scratch/timrpeterson/MORPHEOME/";

$huttlin_pairs = [];
if ( ($handle0 = fopen($storage."huttlin_genes_only.csv", "r") ) !== FALSE) {

    while (($line0 = fgetcsv($handle,  0, ",")) !== FALSE){

        if(!isset($huttlin_pairs[$line0[0]])){
            $huttlin_pairs[$line0[0]] = [$line0[1]];
        }
        else{
            $huttlin_pairs[$line0[0]][] = $line0[1];
        }
        if(!isset($huttlin_pairs[$line0[0]])){
            $huttlin_pairs[$line0[1]] = [$line0[0]];
        }  
        else{
            $huttlin_pairs[$line0[1]][] = $line0[0];
        }      
    }
}
fclose($handle0);   


if ( ($handle1 = fopen($storage."mesh_gene_paper_count_limited_homologs_top10_with_aliases_basal_orphans_php.csv", "w") ) !== FALSE) {

    if ( ($handle = fopen($storage."mesh_gene_paper_count_limited_homologs_top10_with_aliases_php.csv", "r") ) !== FALSE) {

        $row = 0;
        $col_names = [];
        $genes_keys = [1,3,5,7,9,11,13,15,17,19];

        while (($line = fgetcsv($handle,  0, ",")) !== FALSE){
            
            $orphan_pairs_top100 = [];

            foreach($genes_keys as $row){

                $orphan_pairs = [];

                if ( ($handle0 = fopen($storage."interaction_correlations_basal/".$line[$row]."-2019q2-pearsons-python.csv", "r") ) !== FALSE) {

                    while (($line0 = fgetcsv($handle,  0, ",")) !== FALSE){
                        
                        // set key to gene_symbol
                        $orphan_pairs[$line0[0]] = $line0;

                    }
                }
                fclose($handle0);     

                if ( ($handle0 = fopen($storage."interaction_correlations_basal-split/".$line[$row]."-DepMap_pearsons_2019q1.csv", "r") ) !== FALSE) {

                    while (($line0 = fgetcsv($handle,  0, ",")) !== FALSE){

                        if(isset($orphan_pairs[$line0[1]])){
                            $orphan_pairs[$line0[1]][1] = ($orphan_pairs[$line0[1]][1] + $line0[2])/2;
                        }                  
                    }
                }
                fclose($handle0);   

                usort($orphan_pairs, function ($item1, $item2) {
                    return $item2[1] <=> $item1[1];
                });

                $orphan_pairs_top100[$row] = array_slice($orphan_pairs, 0, 100, true);
            }

            $new_arr = [];

            foreach($orphan_pairs_top100 as $row => $orphan_pairs){

                foreach($orphan_pairs as $k => $v){
                    if($v[3]==0 && !isset($new_arr[$row])){

                        $new_arr[$row] = $v;
                    }
                    else{
                        if(isset($huttlin_pairs[$line[$row]]) && $huttlin_pairs[$line[$row]==$k && $v[3]==0 && isset($new_arr[$row])){
                            // if no citations
                            array_pop($new_arr);
                            $new_arr[$row] = $v;
                        }                        
                    }             
                }                
            }
            // get top 10 orphans
            $new_arr = array_slice($new_arr, 0, 10, true);
            
            fputcsv($handle1, array_merge($line, $new_arr));

        
        }
        
    }
    fclose($handle);

}
fclose($handle1);