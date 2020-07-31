<?php


        // NOTE: first run of counting "nih_grant_count" was July 2018, so two years earlier than nih_grant_count_07262020

ini_set('memory_limit', '-1');

$base_path = "/home/jiwpark00/timrpeterson/MORPHEOME-Figure1-pubmed/";

if ( ($handle = fopen($base_path."gene_paper_count_lt10_per_paper.txt", "r") ) !== FALSE) {

    //$genes = [];

    $input_genes = [];

    $cnt = 0;

    while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){

        $input_genes[$line[0]] = $line[1];

    }
    
}
fclose($handle);


if ( ($handle1 = fopen($base_path."gene_grants_count_lt10_genes_per_paper_per_year.csv", "w") ) !== FALSE) {

    foreach($input_genes as $key => $val){
        //dd($row->name);

        if(strlen($key) < 3) continue;

        $offset = 0;
        //$url = 'https://api.federalreporter.nih.gov/v1/projects/search?query=text:'.$key.'$textFields:title,abstract';

        $url = 'https://api.federalreporter.nih.gov/v1/projects/search?offset='.$offset.'&query=text:' . $key . '$textFields:title,abstract';

        try {

            $json = json_decode(file_get_contents($url), true);

            if ($json['totalCount'] > 100000){

                echo "\r\n";
                echo 'gene produces too many results: ';

                echo $key;
                echo "\r\n";
                continue; //throw new \Exception();
            } 


            foreach($json['items'] as $row){

                fputcsv($handle1, [$key, $json['totalCount'], $row['projectNumber'], $row['fy'], $val);

            }

            if($json['totalCount'] > 50){

                echo "\r\n";
                echo "inside".$key;
                echo "\r\n";

                $query_count = ceil($json['totalCount']/50);

                //echo $query_count;

                for ($i = 0; $i <= $query_count; $i++) {

                    if ($i == 0){
                        //$i++;
                        continue;
                    } 

                    //

                    $offset = 50 * $i;

                    echo 'sup'.$offset;

                    $url = 'https://api.federalreporter.nih.gov/v1/projects/search?offset='.$offset.'&query=text:' . $key . '$textFields:title,abstract';


                    try{

                        $json0 = json_decode(file_get_contents($url), true);

                        foreach($json0['items'] as $row){

                            fputcsv($handle1, [$key, $json['totalCount'], $row['projectNumber'], $row['fy'], $val);

                        }

                        sleep(0.35);

                    }
                    catch (\Exception $e) {

                    }                            
                 
                }
            }
            else{

                echo "\r\n";
                echo 'wut';

                echo "inside1".$key;
                echo "\r\n";

                // already got these so commented out

                foreach($json['items'] as $row){

                    fputcsv($handle1, [$key, $json['totalCount'], $row['projectNumber'], $row['fy'], $val);

                }

                sleep(0.35);
                
            }

        } 
        catch (\Exception $e) {
            echo "Something went wrong";
            print_r($e);
        }


    }

}
fclose($handle1);



            
