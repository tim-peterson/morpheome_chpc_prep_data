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



$institutions_list = [];

if ( ($handle = fopen($base_path."get_univs_grant_cnts_and_money.csv", "r") ) !== FALSE) {

    //$genes = [];

    $cnt = 0;

    while (($line = fgetcsv($handle,  0, ",")) !== FALSE){

        if($cnt == 0){
            $cnt++;
            continue;
        }
        if($line[0] == '') continue;

        $institutions_list[$line[0]] = round($line[1]*$line[2]/1000, 0);

    }
    
}
fclose($handle);




$storage_path = "/home/jiwpark00/timrpeterson/NIHReporter/";

$files = array_diff(scandir($storage_path), array('.', '..', '._'));

$files = array_reverse($files);

//print_r(count($files));

$cnt = 0;

//$institutions = [];
foreach($files as $file){


    if(strpos($file, "._")!==false || strpos($file, "DS_Store")!==false  || strpos($file, "zip")!==false ) continue;

    echo $file;

    $grants = [];

    if ( ($handle = fopen($storage_path.$file, "r") ) !== FALSE) {

        $cnt0 = 0;

        //continue;

        while (($line = fgetcsv($handle,  0, ",")) !== FALSE){

            if($cnt0 == 0){


                $cnt0++;
                continue;
            }

            $str = "";
            foreach($line as $row){
                $str.=" ".$row." ";
            }


            $school = '';

            foreach($institutions_list as $key => $val) {

                $pos = strpos($str, $key);

                // Note our use of ===.  Simply == would not work as expected
                // because the position of 'a' was the 0th (first) character.
                if ($pos === false) {
                   // echo "The string '$findme' was not found in the string '$mystring'";
                } else {
                    //echo "The string '$findme' was found in the string '$mystring'";
                    //echo " and exists at position $pos";
                    $school = $val;
                    break;
                }
            }

            $grants[] = [$str, $school];

 
            //$grants[] = $str;

        }
        
    }
    fclose($handle);




    foreach($grants as $arr){

        if(strpos(strtolower($arr[0]), "gene")==false) continue; 

        $arr_str = explode(" ", $arr[0]);

        $arr_str = array_filter($arr_str);

        $tmp = [];

        foreach($arr_str as $val){

            $val = trim($val);

            $val_ = strtolower($val);

            // exclude genes with short names that could be common words
            if(strlen($val_) < 3 || $val_ == 'mice') continue;

            if(isset($input_genes[$val_])){ 

                if(in_array($val_, $tmp)) continue;

                foreach($arr_str as $v){

                    $v = trim($v);

                    if(is_numeric($v) && 1984 < $v && $v < 2021){

                        if(!isset($input_genes[$val_][$v])){

                            //echo $arr[1];

                            $input_genes[$val_][$v] = [$arr[1]];
                        }
                        else{
                            $input_genes[$val_][$v][] = $arr[1];

                        } 
                        
                        break;                      
                    }

                }

                $tmp[] = $val_;
                //break;

            }
        }
        
    }


    //if($cnt > 21) break;
    echo "\r\n";
    echo $cnt++;
    echo "\r\n";
}


     


if ( ($handle1 = fopen($base_path."get_all_grants_per_gene_per_year_from_csv_files_abstract_curated__allow_multiple_genes_per_grant_with_univs_all_values_w_dollars.csv", "w") ) !== FALSE) {

        
        foreach($input_genes as $gene => $arr){

            unset($arr["citation_counts"]);

            //if(!isset($arr[1])) $arr[1] = 0;
            
            //print_r(array_slice($arr, 0, 2));
            //exit;
            foreach($arr as $year => $val){

                //echo $year;

                $val = array_filter($val);
                /*$school_rank = 0;
                foreach($val as $school){
                    $school_rank = $schools0[$school]+$school_rank;
                }*/

                if(count($val)==0) continue;

                fputcsv($handle1, [$gene, $year, count($val), array_sum($val)/count($val) ]);
                //fputcsv($handle1, array_merge([$gene, $year], $val) );
               

            }
            

           
        }

}
fclose($handle1);








