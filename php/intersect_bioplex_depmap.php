<?php


ini_set('memory_limit', '-1');

$storage = "/home/jiwpark00/timrpeterson/MORPHEOME/";


//$start = microtime(true);

$base_path = "/home/jiwpark00/timrpeterson/MORPHEOME/FOR_PAPER/Figure1-pubmed/";



$base_dir = "/home/jiwpark00/timrpeterson/njacobs/";

 $sql_tables = [
    "bioplex_v1_293T_minimum_info.csv",
    "bioplex_v2_293T_minimum_info.csv",
    "bioplex_v3_293T_minimum_info.csv",
    "bioplex_v3_HCT116_minimum_info.csv",

];

$ppi = [];

foreach($sql_tables as $file){
    
    if ( ($handle = fopen($base_dir."bioplex/".$file, "r") ) !== FALSE) {

        $i = 0;
        while (($line = fgetcsv($handle,  0, ",")) !== FALSE){

            if($i==0){
                $i++; continue;
            }

            if(!isset($ppi[$line[0]])){
                $ppi[$line[0]] = [$line[1] => $line[1]];
            }
            else{
                $ppi[$line[0]][$line[1]] = $line[1];
            }

            if(!isset($ppi[$line[1]])){
                $ppi[$line[1]] = [$line[0] => $line[0]];
            }
            else{
                $ppi[$line[1]][$line[0]] = $line[0];
            }


            $i++;
        }

    }
    fclose($handle);

}




$storage_path = "/home/jiwpark00/timrpeterson/njacobs/interactions_correlation_basal/";


$files = array_diff(scandir($storage_path), array('.', '..', '._'));

//$files = array_reverse($files);
$files1 = [];

foreach($files as $key => $val){

    $gene_arr = explode("-", $val);

    $files1[$gene_arr[0]] = $key;
}
//print_r(count($files));

$cnt = 0;

foreach($ppi as $key => $arr){

    if(isset($files1[$key])){

        $citations = [];

        foreach($arr as $k => $v){

            if ( ($handle = fopen($storage_path.$file, "r") ) !== FALSE) {

                $cnt0 = 0;

                while (($line = fgetcsv($handle,  0, ",")) !== FALSE){


                    if($line[2] < 0.05){

                    }
                    
                }
            }

        }


    }
}


foreach($files as $file){

/*#articleId      externalId      source  publisher       origFile        journal printIssn       eIssn   journalUniqueId year    articleType     articleSection  authors authorEmails    authorAffiliations      keywords        title   abstract        vol     issue   page    pmid    pmcId   pii     doi     fulltextUrl     time    offset  size
3358200110      PMID32359029    medline ncbi

*/

    if(strpos($file, "._")!==false || strpos($file, "DS_Store")!==false  ) continue;

    //echo $file;

    $citations = [];

    if ( ($handle = fopen($storage_path.$file, "r") ) !== FALSE) {

        $cnt0 = 0;

        while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){

            if($cnt0 == 0){

                $journal_key = $eIssn_key = $issn_key = $content_key = '';
                foreach($line as $k => $v){
                    if($v == "year") $year_key = $k;


                }

                $cnt0++;
                continue;
            }

            //echo $line[$issn_key]."___";

            if(isset($line[$issn_key])){
                $issn = str_replace("-", "", $line[$issn_key]);

            }
            if(isset($line[$eIssn_key])){
                $eIssn = str_replace("-", "", $line[$eIssn_key]);

            }            
            //exit;
            if(isset($h_index[$issn])){
                //echo "inside";

                $h_in = $h_index[$issn_key];
            }
            elseif(isset($h_index[$eIssn])){
                //echo "inside1".$h_index[$eIssn];
                $h_in = $h_index[$eIssn];
            }
            else{
               // echo "inside2";
                $h_in = 0;
            }

            $citations[] = [$line[$year_key], $line[$content_key], $h_in];


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



    //if($cnt > 51) break;
    /*echo "\r\n";
    echo $cnt++;
    echo "\r\n";*/
}


if ( ($handle1 = fopen($base_path."get_all_citations_per_gene_per_year_from_csv_files_with_h_index.csv", "w") ) !== FALSE) {

    foreach($input_genes as $gene => $arr){
        
        unset($arr["citation_counts"]);

        foreach($arr as $year => $val){

            fputcsv($handle1, [$gene, $year, $val[0], array_sum($val[1])/count($val[1])]);
           
        } 
    }

}
fclose($handle1);








