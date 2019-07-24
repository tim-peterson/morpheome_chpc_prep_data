<?php

ini_set('memory_limit', '-1');


if ( ($handle = fopen($storage."/scratch/timrpeterson/MORPHEOME/MeSH_terms.csv", "r") ) !== FALSE) {

    $new_arr = [];

    while (($line = fgetcsv($handle,  0, ",")) !== FALSE){

        if(!isset($new_arr[$line[2]])){
            $new_arr[$line[2]] = [$line[6]];
        }
        else{
            $new_arr[$line[2]][] = $line[6];
        }  

    }
}


$storage = "/scratch/timrpeterson/MORPHEOME/";

if ( ($handle1 = fopen($storage."mesh_gene_paper_count_limited_homologs_top10_with_aliases_php-10-aliases.csv", "w") ) !== FALSE) {

    if ( ($handle = fopen($storage."mesh_gene_paper_count_limited_homologs_top10_php.csv", "r") ) !== FALSE) {

        $row = 0;
        $col_names = [];

        while (($line = fgetcsv($handle,  0, ",")) !== FALSE){
            
            // to exclude MeSH terms with no genes. "A-GAMMA3'E" is a gene with no papers in the MeSH gene table.
            //if($line[1] == "A-GAMMA3'E") continue;

            if(isset($new_arr[$line[0]])){

                $line0 = $line;
            
                $cnt = count($new_arr[$line[0]]);
                if($cnt < 10){
                    $cnt = 10-$cnt;
                    $new_arr[$line[0]] = array_merge($new_arr[$line[0]], array_fill(0, $cnt, ""));
                }
                else $new_arr[$line[0]] = array_slice($new_arr[$line[0]], 0, 10);


                fputcsv($handle1, array_merge($line0, $new_arr[$line[0]]));

            }

        }
        
    }
    fclose($handle);

}
fclose($handle1);

//echo "done \r\n";

