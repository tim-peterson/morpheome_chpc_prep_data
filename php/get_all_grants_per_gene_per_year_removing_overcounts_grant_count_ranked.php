<?php

ini_set('memory_limit', '-1');

$base_path = "/home/jiwpark00/timrpeterson/MORPHEOME/FOR_PAPER/Figure1-pubmed/";

if ( ($handle1 = fopen($base_path."get_all_grants_per_gene_from_csv_files.csv", "r") ) !== FALSE) {

        
    $input_genes = [];
    while (($line = fgetcsv($handle1,  0, ",")) !== FALSE){

        $input_genes[$line[0]] = $line[1];

    }

}
fclose($handle1);


if ( ($handle1 = fopen($base_path."get_all_grants_per_gene_per_year_from_csv_files_analyze_gt2_characters_with_term_gene_grant_count_ranked.csv", "r") ) !== FALSE) {


//if ( ($handle1 = fopen($base_path."get_all_grants_per_gene_per_year_from_csv_files_analyze.csv", "r") ) !== FALSE) {

        
    $input_genes1 = [];
    while (($line = fgetcsv($handle1,  0, ",")) !== FALSE){

        if(!isset($input_genes1[$line[2]])){
            $input_genes1[$line[2]] = [$line[4]];
        }
        else $input_genes1[$line[2]][] = $line[4];

    }

}
fclose($handle1);

$input_genes2 = [];
foreach($input_genes1 as $key => $val){
    $input_genes2[$key] = array_sum($val);
}


$input_genes3 = [];
foreach($input_genes as $key => $val){

    if($input_genes2[$key] > $input_genes[$key]*2) continue;

    $input_genes3[$key] = $val;
}


if ( ($handle1 = fopen($base_path."get_all_grants_per_gene_per_year_from_csv_files_removing_overcounts_gt2_grant_count_ranked.csv", "w") ) !== FALSE) {

    if ( ($handle2 = fopen($base_path."get_all_grants_per_gene_per_year_from_csv_files_analyze_gt2_characters_with_term_gene_grant_count_ranked.csv", "r") ) !== FALSE) {

    //if ( ($handle1 = fopen($base_path."get_all_grants_per_gene_per_year_from_csv_files_analyze.csv", "r") ) !== FALSE) {
            
            
        while (($line = fgetcsv($handle2,  0, ",")) !== FALSE){

            if(isset($input_genes3[$line[2]])){

                 fputcsv($handle1, $line);

            }
        }

    }
    fclose($handle2);


}
fclose($handle1);








