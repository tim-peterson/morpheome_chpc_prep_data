<?php

ini_set('memory_limit', '-1');

$storage = "/home/jiwpark00/timrpeterson/njacobs/";

//$genes = [];

$start = microtime(true);


if ( ($handle = fopen($storage."mesh_gene_intersect_count_top5_mesh_php_2020.csv", "r") ) !== FALSE) {

    $mesh = [];

    //$arr = [];
    while (($line = fgetcsv($handle,  0, ",")) !== FALSE){

        //$arr[] = $line;

        if($line[2]==0) continue;

        for($i = 0; $i < count($line); $i++){

            if ($i % 2 != 0){

                if($line[$i+1]==0) continue;

                if(!isset($mesh[$line[$i]])){
                    $mesh[$line[$i]] = [ $line[0] => $line[$i+1] ];
                }
                else $mesh[$line[$i]][$line[0]] = $line[$i+1];
                
            }

        }
    }

}
fclose($handle);


$path = $storage."gene_gene_intersect_split/";

$files = array_diff(scandir($path), array('.', '..'));

$cnt0 = 0;

foreach($files as $file){

    if ( ($handle = fopen($path.$file, "r") ) !== FALSE) {

        $col_names = [];

        $cnt = 0;


        while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){

            if($cnt == 0){  
                
                $col_names = $line;

                $cnt++;
                continue; 
            }

            $line0 = $line;

            $gene_name = array_shift($line0);

            arsort($line0);

            //print_r($line0);
            
            
            foreach($line0 as $k => $v){

                if($gene_name!=$col_names[$k]) break;
            }


            if(isset($mesh[$gene_name])){

                    if(!isset($mesh[$gene_name]['top_gene'])){
                        $mesh[$gene_name]["top_gene"] = [$col_names[$k], $v];
                    }
                    else{
                        if($v > $mesh[$gene_name]["top_gene"][1]){

                            $mesh[$gene_name]["top_gene"] = [$col_names[$k], $v];

                        }
                    }


            }


            //$cnt++;

           //echo "\r\n";
        }
        
    }
    fclose($handle);

    //if($cnt0 > 5) break;

    $cnt0++;

}


/*$mesh_top5 = [];

foreach($mesh as $k => $v){

    $tmp = [$k];
    foreach($v as $key => $value){
        if($key!="top_gene"){

            if(!isset($mesh_top5[$value]))

        }
    }

    fputcsv($handle1, );



}*/


if ( ($handle1 = fopen($storage."top5_mesh_top5_cocited_gene_php.csv", "w") ) !== FALSE) {

        foreach($mesh as $k => $v){

            $tmp = [$k];

            foreach($v as $key => $value){

                if($key=="top_gene"){
                    $tmp[] = "top_gene";
                    $tmp[] = $value[0];
                    $tmp[] = $value[1];
                 }
                else{
                    $tmp[] = $key;
                    $tmp[] = $value;
                }
            }

            fputcsv($handle1, $tmp);
        }
}
fclose($handle1);





