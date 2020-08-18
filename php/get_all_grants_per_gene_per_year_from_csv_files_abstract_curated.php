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


$storage_path = "/home/jiwpark00/timrpeterson/NIHReporter/";

$files = array_diff(scandir($storage_path), array('.', '..', '._'));

$files = array_reverse($files);

//print_r(count($files));

$cnt = 0;

foreach($files as $file){


    if(strpos($file, "._")!==false || strpos($file, "DS_Store")!==false || strpos($file, "zip")!==false ) continue;

    echo $file;

    $grants = [];

    if ( ($handle = fopen($storage_path.$file, "r") ) !== FALSE) {

        $cnt0 = 0;

        //continue;

        while (($line = fgetcsv($handle,  0, ",")) !== FALSE){

            if($cnt0 == 0){

                //$content_key = '';
                /*foreach($line as $k => $v){
                    if($v == "FY"){
                        $year_key = $k;
                        
                    } 
                    if($v == "PROJECT_TERMS") $content_key = $k; // || $v == "PROJECT_TITLE"


                }*/

                $cnt0++;
                continue;
            }

            $str = "";
            foreach($line as $row){
                $str.=" ".$row." ";
            }

            /*if(strlen($line[$year_key])!=4){

                foreach($line as $k => $v){

                    if(strlen($line[$year_key])==4 && is_numeric($v) && 1984 < $v && $v < 2021){
                        $year_key = $k;
                        
                    } 
                }

            }

            if(substr_count($line[$content_key], ";") < 3){

                foreach($line as $k => $v){

                    if(substr_count($v, ";") > 1){
                        $content_key = $k;
                        
                    } 
                }

            }
            $grants[] = [$line[$year_key], $line[$content_key]];*/

            $grants[] = $str;

        }
        
    }
    fclose($handle);


    foreach($grants as $arr){

        //$arr_str = explode(" ", $arr[1]);

        if(strpos(strtolower($arr), "gene")==false) continue;

        $arr_str = explode(" ", $arr);

        $tmp = [];

        foreach($arr_str as $val){

            $val = trim($val);

            $val_ = strtolower($val);

            // exclude genes with short names that could be common words
            if(strlen($val_) < 3 || $val_ == 'mice') continue;

            if(isset($input_genes[$val_])){ 

                // prevent a gene from being counted twice within the same grant
                if(in_array($val_, $tmp)) continue;

                foreach($arr_str as $v){

                    $v = trim($v);

                    if(is_numeric($v) && 1984 < $v && $v < 2021){
                        if(!isset($input_genes[$val_][$v])){
                            $input_genes[$val_][$v] = 1;
                        
                        }
                        else $input_genes[$val_][$v]++;  
                        
                        break;                      
                    }


                }

                $tmp[] = $val_;

                //break;
                //echo "\r\n";
                //echo 'hit: '.$input_genes[strtolower($val)];
                //echo "\r\n";




                /*if(!isset($input_genes[strtolower($val)][1])){
                    $input_genes[strtolower($val)][1] = 1;
                    $input_genes[strtolower($val)][2] = 1;
                }
                else $input_genes[strtolower($val)][1]++;*/
            }
        }
        
    }


    //if($cnt > 21) break;
    echo "\r\n";
    echo $cnt++;
    echo "\r\n";
}

/*foreach($input_genes as $gene => &$arr){

    $citation_counts = $arr["citation_counts"];

    $sum = 0;
    foreach($arr as $key => $val){
         $sum = $sum + $val;
    }
    //if(!isset($arr[1])) $arr[1] = 0;

    // get rid of grant counts for genes that are 10X more abundant in grant than in citations
    if($citation_counts*10 < $sum) $arr[1] = $arr[0];
   
}*/


if ( ($handle1 = fopen($base_path."get_all_grants_per_gene_per_year_from_csv_files_abstract_curated_allow_multiple_genes_per_grant.csv", "w") ) !== FALSE) {

        
        foreach($input_genes as $gene => $arr){

            unset($arr["citation_counts"]);

            //if(!isset($arr[1])) $arr[1] = 0;
            
            foreach($arr as $year => $val){

                fputcsv($handle1, [$gene, $year, $val]);
               

            }
            

           
        }

}
fclose($handle1);








