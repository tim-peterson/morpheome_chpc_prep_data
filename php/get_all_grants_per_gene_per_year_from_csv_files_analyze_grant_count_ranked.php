<?php

ini_set('memory_limit', '-1');


$base_path = "/home/jiwpark00/timrpeterson/MORPHEOME/FOR_PAPER/Figure1-pubmed/";

if ( ($handle = fopen($base_path."gene_paper_grant_count.txt", "r") ) !== FALSE) {

//if ( ($handle = fopen($base_path."gene_paper_count_lt10_per_paper.txt", "r") ) !== FALSE) {

    //$genes = [];

    $input_genes = [];

    $cnt = 0;

    while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){

                $line[0] = strtolower($line[0]);

                if ($cnt >= 0 and $cnt < 60) {

                    $input_genes[$line[0]] = [$line[1], "0.1%"];
                }
                elseif ($cnt >= 60 and $cnt < 600){

                    $input_genes[$line[0]] = [$line[1], "1%"];
                }
                elseif($cnt >= 600 and $cnt < 3000){

                    $input_genes[$line[0]] = [$line[1], "5%"];
                }
                elseif ($cnt >= 3000 and $cnt < 6000){

                    $input_genes[$line[0]] = [$line[1], "10%"];
                }
                elseif ($cnt >= 6000 and $cnt < 30000){

                    $input_genes[$line[0]] = [$line[1], "11-50%"];

                }
                elseif ($cnt >= 30000 and $cnt < 60000){

                    $input_genes[$line[0]] = [$line[1], "51-100%"];

                }


                $cnt++;

        /*for($i = 1985; $i < 2021; $i++){
            $input_genes[strtolower($line[0])] = [$line[0],$line[1],];
        }*/
        

    }
    
}
fclose($handle);



if ( ($handle1 = fopen($base_path."get_all_grants_per_gene_per_year_from_csv_files_require_the_term_gene.csv", "r") ) !== FALSE) {

        
    $input_genes1 = [];
    while (($line = fgetcsv($handle1,  0, ",")) !== FALSE){

        if(isset($input_genes[$line[0]])){

            if(!isset($input_genes1[$line[0]])){
                $input_genes1[$line[0]] = [array_merge($input_genes[$line[0]], $line)];
            }
            else $input_genes1[$line[0]][] = array_merge($input_genes[$line[0]], $line);
        }

    }

}
fclose($handle1);



if ( ($handle1 = fopen($base_path."get_all_grants_per_gene_per_year_from_csv_files_analyze_gt2_characters_with_term_gene_grant_count_ranked.csv", "w") ) !== FALSE) {

        
        foreach($input_genes1 as $gene => $arr){
            
            if($gene=='mice') continue;
            
            if(strlen($gene) < 3) continue;

            foreach($arr as $arr1){

                fputcsv($handle1, $arr1);
               

            }
           
        }

}
fclose($handle1);








