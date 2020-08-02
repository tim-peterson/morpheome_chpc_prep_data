<?php


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



if ( ($handle = fopen($base_path."scimagojr 2019.txt", "r") ) !== FALSE) {

    //$genes = [];

    $h_index = [];

    $cnt0 = 0;

    while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){

        if($cnt0 == 0){

            foreach($line as $k => $v){
                if($v == "Issn") $issn_key = $k;
                if($v == "H index") $h_index_key = $k;
            }

            $cnt0++;
            continue;
        }

        //$issn = $line[$issn_key];

        //print_r($line[$issn_key]);
        //exit;

        if(isset($line[$issn_key])){

            $issns = explode(", ", $line[$issn_key]);

            if(isset($issns[0]) && $issns[0]!= ''){

                if(isset($line[$h_index_key])){
                    $h_index[$issns[0]] = $line[$h_index_key];
                }    

            }
            elseif(isset($issns[1]) && $issns[1]!= ''){

                if(isset($line[$h_index_key])){
                    $h_index[$issns[1]] = $line[$h_index_key];
                }
            }
            else{

                if(isset($line[$h_index_key])){
                    $h_index[$line[$issn_key]] = $line[$h_index_key];
                }

            }

        }

        

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

    $citations = [];

    if ( ($handle = fopen($storage_path.$file, "r") ) !== FALSE) {

        $cnt0 = 0;

        while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){

            if($cnt0 == 0){

                $journal_key = $eIssn_key = $issn_key = $content_key = '';
                foreach($line as $k => $v){
                    if($v == "year") $year_key = $k;
                    if($v == "abstract") $content_key = $k; // || $v == "PROJECT_TITLE"
                    //if($v == "journal") $journal_key = $k;
                    if($v == "printIssn") $issn_key = $k;
                    if($v == "eIssn") $eIssn_key = $k;

                }

                $cnt0++;
                continue;
            }

            //echo $line[$issn_key]."___";

            if(isset($line[$issn_key])){
                $issn = str_replace("-", "", $line[$issn_key]);

                if(isset($h_index[$issn])){
                    //echo "inside";

                    $h_in_i = $h_index[$issn_key];
                }

            }
            if(isset($line[$eIssn_key])){
                $eIssn = str_replace("-", "", $line[$eIssn_key]);

                if(isset($h_index[$eIssn])){
                    //echo "inside1".$h_index[$eIssn];
                    $h_in_e = $h_index[$eIssn];
                }

            }            
            //exit;
        
            if(isset($h_in_i) && isset($h_in_e)){
                if($h_in_e > $h_in_i) $h_in = $h_in_e;
                elseif($h_in_i > $h_in_e) $h_in = $h_in_i;
                else $h_in = $h_in_e;
            }

            if(isset($h_in_i) && !isset($h_in_e)){
                $h_in = $h_in_i;
            }

            if(!isset($h_in_i) && isset($h_in_e)){
                $h_in = $h_in_e;
            }



            $citations[] = [$line[$year_key], $line[$content_key], isset($h_in) ? $h_in : "" ];


        }
        
    }
    fclose($handle);


    foreach($citations as $arr){

        //if($arr[2] > 0) echo 'hurrah';


        $arr_str = explode(" ", $arr[1]);

        //if(strpos(strtolower($arr[1]), "gene")==false) continue;

        $arr_str = explode(" ", $arr[1]);

        foreach($arr_str as $val){

            $val0 = strtolower($val);

            if(isset($input_genes[$val0])){

                if(!isset($input_genes[$val0][$arr[0]])){

                    $input_genes[$val0][$arr[0]] = [1, [$arr[2]] ];
                }
                else{
                    $input_genes[$val0][$arr[0]][0]++;
                    $input_genes[$val0][$arr[0]][1][] = $arr[2];
                } 
            }
        } 
    }



    /*if($cnt > 51) break;
    echo "\r\n";
    echo $cnt++;
    echo "\r\n";*/
}


if ( ($handle1 = fopen($base_path."get_all_citations_per_gene_per_year_from_csv_files_with_h_index_wo_zeros.csv", "w") ) !== FALSE) {

    foreach($input_genes as $gene => $arr){
        
        unset($arr["citation_counts"]);

        foreach($arr as $year => $val){


            fputcsv($handle1, [$gene, $year, $val[0], count(array_filter($val[1])) > 0 ? array_sum(array_filter($val[1]))/count(array_filter($val[1])) : 0]);
           
        } 
    }

}
fclose($handle1);








