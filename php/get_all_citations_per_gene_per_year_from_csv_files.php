<?php
// NOTE: this doesn't produce useful data because the top MeSH terms for each gene are things like Homo Sapiens and other terms that aren't useful to users.

ini_set('memory_limit', '-1');

$storage = "/home/jiwpark00/timrpeterson/MORPHEOME/";


//$start = microtime(true);

$base_path = "/home/jiwpark00/timrpeterson/MORPHEOME/FOR_PAPER/Figure1-pubmed/";

if ( ($handle = fopen($base_path."gene_paper_count_lt10_per_paper.txt", "r") ) !== FALSE) {

    //$genes = [];

    $input_genes = [];

    $cnt = 0;

    while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){

        $input_genes[strtolower($line[0])] = ["citation_counts" => $line[1]];

    }
    
}
fclose($handle);


$storage_path = "/home/jiwpark00/timrpeterson/medline/hgwdev.gi.ucsc.edu/~max/trpeterson/medline/";


$files = array_diff(scandir($storage_path), array('.', '..', '._'));

$files = array_reverse($files);

//print_r(count($files));

$cnt = 0;

foreach($files as $file){

/*#articleId      externalId      source  publisher       origFile        journal printIssn       eIssn   journalUniqueId year    articleType     articleSection  authors authorEmails    authorAffiliations      keywords        title   abstract        vol     issue   page    pmid    pmcId   pii     doi     fulltextUrl     time    offset  size
3358200110      PMID32359029    medline ncbi

*/

    if(strpos($file, "._")!==false || strpos($file, "DS_Store")!==false || strpos($file, "index.html")!==false || strpos($file, "articles.db")!==false  ) continue;

    //echo $file;

    $grants = [];

    if ( ($handle = fopen($storage_path.$file, "r") ) !== FALSE) {

        $cnt0 = 0;

        while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){

            if($cnt0 == 0){

                $content_key = '';
                foreach($line as $k => $v){
                    if($v == "year") $year_key = $k;
                    if($v == "abstract") $content_key = $k; // || $v == "PROJECT_TITLE"

                }

                $cnt0++;
                continue;
            }

            $grants[] = [$line[$year_key], $line[$content_key]];


        }
        
    }
    fclose($handle);


    foreach($grants as $arr){

        $arr_str = explode(" ", $arr[1]);

        //if(strpos(strtolower($arr[1]), "gene")==false) continue;

        $arr_str = explode(" ", $arr[1]);

        foreach($arr_str as $val){

            $val0 = strtolower($val);

            if(isset($input_genes[$val0])){

                if(!isset($input_genes[$val0][$arr[0]])){
                    $input_genes[$val0][$arr[0]] = 1;
                }
                else $input_genes[$val0][$arr[0]]++;
            }
        } 
    }


    //if($cnt > 21) break;
    /*echo "\r\n";
    echo $cnt++;
    echo "\r\n";*/
}


if ( ($handle1 = fopen($base_path."get_all_citations_per_gene_per_year_from_csv_files.csv", "w") ) !== FALSE) {

    foreach($input_genes as $gene => $arr){
        
        unset($arr["citation_counts"]);

        foreach($arr as $year => $val){

            fputcsv($handle1, [$gene, $year, $val]);
           
        } 
    }

}
fclose($handle1);








