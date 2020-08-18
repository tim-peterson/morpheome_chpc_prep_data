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

        $input_genes[strtolower($line[0])] = [$line[1]];

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

    echo $file;

    $citations = [];

    if ( ($handle = fopen($storage_path.$file, "r") ) !== FALSE && strpos($file, ".article")!==false) {

        $cnt0 = 0;

        //continue;

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

            $citations[] = [$line[$year_key], $line[$content_key]];

        }
        
    }
    fclose($handle);


    foreach($citations as $arr){

        $arr_str = explode(" ", $arr[1]);

        $arr_str = array_filter($arr_str);

        $tmp = [];

        foreach($arr_str as $val){

            $val_ = strtolower($val);

            //if(strlen($val_) < 3 || $val_ == 'mice') continue;

            if(isset($input_genes[$val_])){

                if(in_array($val_, $tmp)) continue;

                if(!isset($input_genes[$val_][1])){
                    $input_genes[$val_][1] = 1;
                }
                else $input_genes[$val_][1]++;

                $tmp[] = $val_;

            }
        }    
    }


    //if($cnt > 21) break;
    echo "\r\n";
    echo $cnt++;
    echo "\r\n";
}


if ( ($handle1 = fopen($base_path."get_all_citations_per_gene_from_csv_files_abstract_curated_allow_multiple_genes_per_citation.csv", "w") ) !== FALSE) {

        
    foreach($input_genes as $gene => $arr){

        if(!isset($arr[1])) $arr[1] = 0;

        $tmp = array_merge([$gene], $arr);

        fputcsv($handle1, $tmp);
       
    }

}
fclose($handle1);








