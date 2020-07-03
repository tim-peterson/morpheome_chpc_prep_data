<?php

#ini_set('memory_limit', '-1');

$storage = "/scratch/timrpeterson/MORPHEOME/";

//$genes = [];

$start = microtime(true);

//$next = "";

$gene_mesh_terms = [];

if ( ($handle = fopen("/scratch/njacobs/mesh_gene_intersect.tsv", "r") ) !== FALSE) {

    $cnt = 0;
    $col_names = [];

    $mesh_terms = ["gene"];

    while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){

        if($cnt == 0){  
            
            $col_names = $line;
         
            //$genes = array_flip($col_names);

            $cnt++;
            continue; 
        }

        $line0 = $line;
        $mesh_name = array_shift($line0);


        //print_r(count($line0));

        $line1 = array_filter($line0);

        //print_r(count($line1));

      
        $mesh_terms[] = $mesh_name;

       /* if($mesh_name==""){
            $next = $cnt;
            echo "inside: ";
            echo $cnt;
            echo "\r\n";
        } */

        foreach($line1 as $k => $v){

            if(!isset($gene_mesh_terms[$k] )){
                $gene_mesh_terms[$k] = [];
            }
            
            $gene_mesh_terms[$k][$cnt] = $v;
             
        }


       //if($cnt > 100) break;

       $cnt++;

       //echo "\r\n";
    }
    
}
fclose($handle);

if ( ($handle1 = fopen($storage."mesh_gene_intersect_count_top5_gene_php_2020.csv", "w") ) !== FALSE) {

    // "mesh_gene_intersect_limited.tsv"
    

        $used_mesh_terms = [];
        foreach($gene_mesh_terms as $gene => $mesh_arr){

            arsort($mesh_arr);
            $top10 = array_slice($mesh_arr, 0, 5, true);

            $tmp = [$col_names[$gene]];
            foreach($top10 as $k => $v){
                $tmp[] = $mesh_terms[$k];

                $used_mesh_terms[] = $k;
                $tmp[] = $v;
            }

            fputcsv($handle1, $tmp);

           // print_r($tmp);
            //echo "\r\n";

        }

        //$used_mesh_terms = array_unique($used_mesh_terms);

        /*echo "used/total mesh_terms: ";
        echo count($used_mesh_terms);
        echo "/";
        echo count($mesh_terms);

        echo " % mesh_terms in top 5: ";
        print_r(count($used_mesh_terms)/count($mesh_terms));*/

}
fclose($handle1);



if ( ($handle2 = fopen($storage."genes_with_no_mesh_terms_php_2020.csv", "w") ) !== FALSE) {

    $diff = array_diff_key($col_names, $gene_mesh_terms);

    foreach($diff as $k => $v){
        fputcsv($handle2, [$col_names[$k], 0]);
    }


}
fclose($handle2);

/*echo "\r\n";

echo round((microtime(true) - $start), 1);


echo "\r\n";

echo $mesh_terms[$next-1];
echo " ";
echo $mesh_terms[$next+1];*/




