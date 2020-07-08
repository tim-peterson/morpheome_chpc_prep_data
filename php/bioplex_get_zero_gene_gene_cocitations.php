<?php

	ini_set('memory_limit', '-1');

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

    //echo "done";

	$input_path = $base_dir."gene_gene_intersect_split/";

	$files = array_diff(scandir($input_path), array('.', '..'));

	$ppi_no_citations = $ppi;


	$ppi_with_citations = [];


    $cnt0 = 0;

	foreach($files as $file){

        if ( ($handle = fopen($input_path.$file, "r") ) !== FALSE) {

            $cnt = 0;

            while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){

                if($cnt == 0){  
                    $col_names = $line;
                    
                    //$genes = array_flip($col_names);

                    $cnt++;
                    continue; 
                }

                $line0 = $line;

                // remove first item, the gene name for that row, from $line0 array. This preserve keys.

                $gene = array_shift($line0);


                if(isset($ppi[$gene])){ // isset($line1[$genes[$ppi[$gene]]])

                    // removes array items with 0 values, in this case 0 citations. This preserves keys.
                    $line1 = array_filter($line0);

                    $line2 = [];
                    foreach($line1 as $k => $v){
                        $line2[] = $col_names[$k];
                    }

                    // get the gene names of those paired with $gene in Huttlin that have greater than 0 citations
                    $tmp = array_intersect($ppi[$gene], $line2);

                    if($tmp){
                    //print_r($gene.$ppi[$gene]);
                        foreach($tmp as $k => $v){

                            if(!isset($ppi_with_citations[$gene])){
                                $ppi_with_citations[$gene] = [$v => $v];
                            }
                            else $ppi_with_citations[$gene][$v] = $v;

                            unset($ppi_no_citations[$gene][$v]);  

                            //echo "unset_".$gene."_".$v;  
                            //echo "\r\n";                         
                        }
                       
                    }


                }

            } 
        }
        fclose($handle);

        //echo $cnt0;

        //if($cnt0 > 0) break;

        $cnt0++;

	}


    $ppi_no_citations0 = [];
    foreach ($ppi_no_citations as $k => $v) {

        foreach($v as $row){

            $tmp = [$k, $row];
            sort($tmp);

            $ppi_no_citations0[] = $tmp;
        }
        
    }

    $ppi_no_citations0 = array_unique($ppi_no_citations0, SORT_REGULAR);

    if (($handle0 = fopen($base_dir."bioplex/ppi_no_citations5.csv", "w")) !== FALSE) {

        foreach ($ppi_no_citations0 as $row) {
     
            fputcsv($handle0, $row);
            
        }
    }
    fclose($handle0);


    $ppi_with_citations0 = [];
    foreach ($ppi_with_citations as $k => $v) {

        foreach($v as $row){

            $tmp = [$k, $row];
            sort($tmp);

            $ppi_with_citations0[] = $tmp;
        }
        
    }

    $ppi_with_citations0 = array_unique($ppi_with_citations0, SORT_REGULAR);

    if (($handle0 = fopen($base_dir."bioplex/ppi_with_citations5.csv", "w")) !== FALSE) {

        foreach ($ppi_with_citations0 as $row) {
     
            fputcsv($handle0, $row);
            
        }
    }
    fclose($handle0);

