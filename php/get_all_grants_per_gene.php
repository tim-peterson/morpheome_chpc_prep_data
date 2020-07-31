<?php


        // NOTE: first run of counting "nih_grant_count" was July 2018, so two years earlier than nih_grant_count_07262020

ini_set('memory_limit', '-1');

print_r(ini_get_all());
die;

ini_set('extension', 'php_openssl.dll');
ini_set('allow_url_include', 'On');



$base_path = "/home/jiwpark00/timrpeterson/MORPHEOME/FOR_PAPER/Figure1-pubmed/";

if ( ($handle = fopen($base_path."gene_paper_count_lt10_per_paper.txt", "r") ) !== FALSE) {

    //$genes = [];

    $input_genes = [];

    $cnt = 0;

    while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){

        $input_genes[$line[0]] = $line[1];

    }
    
}
fclose($handle);


if ( ($handle1 = fopen($base_path."gene_grants_count_lt10_genes_per_paper.csv", "w") ) !== FALSE) {

    foreach($input_genes as $key => $val){
        //dd($row->name);

        //if(strlen($key) < 3) continue;

        $offset = 0;
        //$url = 'https://api.federalreporter.nih.gov/v1/projects/search?query=text:'.$key.'$textFields:title,abstract';

        $url = 'http://api.federalreporter.nih.gov/v1/projects/search?offset='.$offset.'&query=text:' . $key . '$textFields:title,abstract';


        try {

            $json = json_decode(file_get_contents($url), true);


            /*$ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
             // 'X-RapidAPI-Host: kvstore.p.rapidapi.com',
             // 'X-RapidAPI-Key: 7xxxxxxxxxxxxxxxxxxxxxxx',
              'Content-Type: application/json'
            ]);

            //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            //curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            $json = curl_exec($ch);

            print_r($json);
            die;


            curl_close($ch);*/


            fputcsv($handle1, [$key, $json['totalCount'], $val]);

            
            sleep(0.35);
                
            

        } 
        catch (\Exception $e) {
            echo "Something went wrong";
            print_r($e);
        }


    }

}
fclose($handle1);



            
