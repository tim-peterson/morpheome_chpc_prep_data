<?php
// NOTE: this doesn't produce useful data because the top MeSH terms for each gene are things like Homo Sapiens and other terms that aren't useful to users.

ini_set('memory_limit', '-1');

$storage = "/home/jiwpark00/timrpeterson/njacobs/";


//$start = microtime(true);


$path = $storage."mesh_gene_split/";

$files = array_diff(scandir($path), array('.', '..'));

$genes_top_5 = [];

$cnt = 0;

foreach($files as $file){

    //$cnt = 0;

    if ( ($handle = fopen($path.$file, "r") ) !== FALSE) {

        while (($line = fgetcsv($handle,  0, ",")) !== FALSE){

            $line0 = $line;
            $gene = array_shift($line0);

            if(!isset($genes_top_5[$gene])){

                $genes_top_5[$gene] = [];
            }

            $line1 = [];

            for($i = 0; $i < count($line0); $i++){

                if ($i % 2 == 0){
                    $line1[$line0[$i]] = $line0[$i+1];


                }

            }


            arsort($line1);

            $top10 = array_slice($line1, 0, 5, true);

            //print_r($top10);


            if(empty($genes_top_5[$gene])){

                $genes_top_5[$gene] = $top10;

            }
            else{

                $tmp = $genes_top_5[$gene];

                $tmp = array_merge($tmp, $top10);

                arsort($tmp);

                $genes_top_5[$gene] = array_slice($tmp, 0, 5, true);


            }

           //$cnt++;

        }
        
    }
    fclose($handle);

   if($cnt > 1) break;
    $cnt++;
}



if ( ($handle1 = fopen($storage."aggregate_mesh_gene_top5_php.csv", "w") ) !== FALSE) {

        
        foreach($genes_top_5 as $gene => $mesh_arr){

            $tmp = [$gene];

            foreach($mesh_arr as $k => $v){
              
                $tmp[] = $k;
                $tmp[] = $v;

            }

            fputcsv($handle1, $tmp);
           
        }

}
fclose($handle1);






