<?php
// NOTE: this doesn't produce useful data because the top MeSH terms for each gene are things like Homo Sapiens and other terms that aren't useful to users.




ini_set('memory_limit', '-1');


function Standard_Deviation($array){

    $number_of_elements = count($array);

    $variance = 0.0;

    // using array_sum() function to calculate mean

    $avg = array_sum($array)/$number_of_elements;

    foreach($array as $i){

        $variance += pow(($i - $avg), 2);

    }

    return (float)sqrt($variance/$number_of_elements);

}


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

if ( ($handle = fopen($base_path."institutions_list.txt", "r") ) !== FALSE) {

    //$genes = [];

    $cnt = 0;

    while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){

        $institutions_list[$line[0]] = $line[1];

    }
    
}
fclose($handle);




$storage_path = "/home/jiwpark00/timrpeterson/NIHReporter/";

$files = array_diff(scandir($storage_path), array('.', '..', '._'));

$files = array_reverse($files);

//print_r(count($files));

$cnt = 0;

$inst = [];
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



            $school_key = $school = '';

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
                    $school_key = $key;
                    break;
                }
            }

            $grants[] = [$str, $school_key, $line[count($line)-2]];

 
            //$grants[] = $str;

        }
        
    }
    fclose($handle);


    foreach($grants as $arr){

        if(strpos(strtolower($arr[0]), "gene")==false) continue; 

        if(!isset($inst[$arr[1]])){

            //echo $arr[1];

            $inst[$arr[1]] = [$arr[2]];
        }
        else{
            $inst[$arr[1]][] = $arr[2];

        } 

        
    }


    //if($cnt > 21) break;
    echo "\r\n";
    echo $cnt++;
    echo "\r\n";
}


     

if ( ($handle1 = fopen($base_path."get_univs_grant_cnts_and_money.csv", "w") ) !== FALSE) {

        
        fputcsv($handle1, ['name', 'count', 'avg', 'std_dev', 'indiv_vals'] );

        foreach($inst as $univ => $arr){

            //unset($arr["citation_counts"]);
            $arr = array_filter($arr);
            $cnt = count($arr);

            fputcsv($handle1, [$univ, $cnt, $cnt == 0 ? 0 : round(array_sum($arr)/$cnt, 0), $cnt > 0 ? round(Standard_Deviation($arr), 0) : 0, $cnt > 0 ? implode("_", $arr) : "n/a" ]  );

           
        }

}
fclose($handle1);








