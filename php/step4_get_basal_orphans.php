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

$gene_paper_greater_than_25 = [];
if ( ($handle0 = fopen($storage."gene_paper_counts.csv", "r") ) !== FALSE) {

    while (($line0 = fgetcsv($handle0,  0, ",")) !== FALSE){

        if($line[1] < 25){

            if(!isset($gene_paper_greater_than_25[$line[0]])){
                $gene_paper_greater_than_25[$line[0]] = $line[1];
            }
        }

     
    }
}
fclose($handle0);  

function assc_array_count_values( $array, $key ) {
    foreach( $array as $row ) {
         $new_array[] = $row[$key];
    }
    return array_count_values( $new_array );
}

if ( ($handle1 = fopen($storage."mesh_gene_paper_count_limited_homologs_top10_with_aliases_basal_orphans_php-less25.csv", "w") ) !== FALSE) {

    if ( ($handle = fopen($storage."mesh_gene_paper_count_limited_homologs_top10_with_aliases_php-10-aliases.csv", "r") ) !== FALSE) {

        $row = 0;
        $col_names = [];
        $genes_keys = [1,3,5,7,9,11,13,15,17,19];
        //$paper_count_keys = [2,4,6,8,10,12,14,16,18,20];
        while (($line = fgetcsv($handle,  0, ",")) !== FALSE){
            
            $orphan_pairs_top100 = [];

            foreach($genes_keys as $row){

                if($line[(int)$row + 1] == 0){
                   
                    continue;
                }
               
                $orphan_pairs = [];

                if ( ($handle0 = fopen($storage."interaction_correlations_basal/".$line[$row]."-2019q2-pearsons-python.csv", "r") ) !== FALSE) {

                    $cnt = 0;
                    while (($line0 = fgetcsv($handle0,  0, ",")) !== FALSE){

                        if($cnt == 0){
                            $cnt++;
                            continue;
                        }

                        // if orphan has less than 25 citations
                        if(!isset($gene_paper_greater_than_25[$line0[0]])){
                            // set key to orphan gene_symbol
                            $orphan_pairs[$line0[0]] = $line0;
                        }
                        

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


                /*if ( ($handle0 = fopen($storage."interaction_correlations_basal/2018q4/".$line[$row]."-DepMap_pearsons_2018q4.csv", "r") ) !== FALSE) {

                    while (($line0 = fgetcsv($handle0,  0, ",")) !== FALSE){

                        if(isset($orphan_pairs[$line0[1]])){
                            $orphan_pairs[$line0[1]][1] = ($orphan_pairs[$line0[1]][1] + $line0[2])/3;
                        }                  
                    }

                    fclose($handle0);  
                }*/
            
                //FGF8,219,THUMPD3-AS1,195,BMP4,193,PAX6,168,TP53,153,SOX9,148,PAX2,140,PTCH1,131,GLI3

                // sort desc all Pearson's correlations for a given top-cited gene
                usort($orphan_pairs, function ($item1, $item2) {
                    return $item2[1] <=> $item1[1];
                });

                // get top 100 genes for each top_cited gene
                $orphan_pairs_top100[$row] = array_slice($orphan_pairs, 0, 100, true);
            }
            
            $multi_hit_orphans = [];
            $multi_hit_orphans0 = [];
            foreach($orphan_pairs_top100 as $row => $orphan_pairs){

                foreach($orphan_pairs as $row0){

                    $multi_hit_orphans0[] = $row0[0];

                    /*if(!isset($multi_hit_orphans[$line[$row]])){
                        $multi_hit_orphans[$line[$row]] = [$row0[0]];
                    }
                    else $multi_hit_orphans[$line[$row]][] = $row0[0];*/
                }
            }

            $multi_hit_orphans0 = array_count_values($multi_hit_orphans0);
            //print_r($multi_hit_orphans0);
            //exit;

            foreach($orphan_pairs_top100 as $key => $val){
                //print_r($val);
                //exit;
                foreach($val as $k => $v){
                    if(isset($multi_hit_orphans0[$v[0]]) && $multi_hit_orphans0[$v[0]] > 1){
                    
                        //echo "inside".$multi_hit_orphans0[$v[0]];
    
                        $multi_hit_orphans[$line[$key]] = $v[0];
                    }
                    //else echo 'foo';
                }

            }
            //exit;
            /*foreach($multi_hit_orphans as $k => $v){
                $v0 = array_count_values($v);
                $multi_hit_orphans[$k] = $v0;
            }*/

            /*foreach($multi_hit_orphans as $key => $val){
                foreach($val as $k => $v){
                    if($v < 2){
                        unset($multi_hit_orphans[$key][$val[$k]]);
                    }
                }

            }*/

            //print_r($multi_hit_orphans);
            //exit;
            $new_arr = [];
            foreach($orphan_pairs_top100 as $row => $orphan_pairs){

                foreach($orphan_pairs as $row0){

                    if($row0[3]==0){
                        if(!isset($new_arr[$row])){

                            $new_arr[$row] = $row0;
                            //print_r($v);
                            //exit;
                            //break;
                        }
                        else{
                            if(isset($multi_hit_orphans[$line[$row]])){
                                // if no citations
                                //array_pop($new_arr);
                                echo 'inside1 multi-hits';
                                print_r($line[$row]."___".$row0[0]);
                                echo "\r\n";
                                $row1 = array_replace($row0, [0 => $row0[0]."-multi-hit-".$line[$row]] );
                                $new_arr[$row] = $row1;
                            } 
    
                            if(isset($huttlin_pairs[$line[$row]]) && in_array($row0[0], $huttlin_pairs[$line[$row]]) ){
                                // if no citations
                                //array_pop($new_arr);
                                echo 'inside2 huttlin match';
                                print_r($line[$row]."___".$row0[0]);
                                echo "\r\n";
                                $row1 = array_replace($row0, [0 => $row0[0]."-huttlin-".$line[$row]] );
                                $new_arr[$row] = $row1;
                            }                        
                        } 

                    }
                } 
                
               // break;
            }
            // get top 10 orphans
            $new_arr = array_slice($new_arr, 0, 10, true);
            $new_arr0 = [];
            foreach($new_arr as $row){
                $new_arr0[] = $row[0];
                $new_arr0[] = $row[1];
                $new_arr0[] = $row[3];
            }
            fputcsv($handle1, array_merge($line, $new_arr0));


        }
        
    }
    fclose($handle);

}
fclose($handle1);