<?php

ini_set('memory_limit', '-1');

$storage = "/scratch/timrpeterson/MORPHEOME/";

$huttlin_pairs = [];
if ( ($handle0 = fopen($storage."huttlin_genes_only.csv", "r") ) !== FALSE) {

    while (($line0 = fgetcsv($handle0,  0, ",")) !== FALSE){

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

                    $cnt = 0;
                    while (($line0 = fgetcsv($handle0,  0, ",")) !== FALSE){
                        if($cnt == 0){
                            $cnt++;
                            continue;
                        }
                        // set key to gene_symbol
                        $orphan_pairs[$line0[0]] = $line0;

                    }

                    fclose($handle0);  
                }
                   

                /*if ( ($handle0 = fopen($storage."interaction_correlations_basal-split/".$line[$row]."-DepMap_pearsons_2019q1.csv", "r") ) !== FALSE) {

                    while (($line0 = fgetcsv($handle0,  0, ",")) !== FALSE){

                        if(isset($orphan_pairs[$line0[1]])){
                            $orphan_pairs[$line0[1]][1] = ($orphan_pairs[$line0[1]][1] + $line0[2])/2;
                        }                  
                    }

                    fclose($handle0);  
                }*/
                 
               // array_multisort($orphan_pairs, SORT_DESC, 1);
                //krsort($orphan_pairs);

                usort($orphan_pairs, function ($item1, $item2) {
                    return $item2[1] <=> $item1[1];
                });

                $orphan_pairs_top100[$row] = array_slice($orphan_pairs, 0, 100, true);
            }

            //print_r($orphan_pairs_top100);
            //exit;//
            $new_arr = [];
            $multi_hit_orphans = [];
            foreach($orphan_pairs_top100 as $row => $orphan_pairs){

                foreach($orphan_pairs as $k => $v){
                    $multi_hit_orphans[] = $k;
                }
            }

            $multi_hit_orphans = array_count_values($multi_hit_orphans);

            foreach($multi_hit_orphans as $key => $val){
                if($val < 2){
                    unset($multi_hit_orphans[$key]);
                }
            }

            foreach($orphan_pairs_top100 as $row => $orphan_pairs){

                foreach($orphan_pairs as $k => $v){
                    if($v[3]==0 && !isset($new_arr[$row])){

                        $new_arr[$row] = $v;
                    }
                    else{
                        if($v[3]==0 && isset($new_arr[$row]) && isset($multi_hit_orphans[$k])){
                            // if no citations
                            array_pop($new_arr);
                            $new_arr[$row] = $v;
                        } 

                        if(isset($huttlin_pairs[$line[$row]]) && in_array($k, $huttlin_pairs[$line[$row]]) && $v[3]==0 && isset($new_arr[$row])){
                            // if no citations
                            array_pop($new_arr);
                            $new_arr[$row] = $v;
                        }                        
                    }             
                }                
            }
            // get top 10 orphans
            //$new_arr = array_slice($new_arr, 0, 10, true);
            $new_arr0 = [];
            foreach($new_arr as $k => $v){
                $new_arr0[] = $v[0];
                $new_arr0[] = $v[1];
                $new_arr0[] = $v[3];
            }
            fputcsv($handle1, array_merge($line, $new_arr0));


        }
        
    }
    fclose($handle);

}
fclose($handle1);