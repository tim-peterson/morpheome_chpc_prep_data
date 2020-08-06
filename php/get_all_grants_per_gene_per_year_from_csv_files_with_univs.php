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

if ( ($handle = fopen($base_path."institutions_list.txt", "r") ) !== FALSE) {

    //$genes = [];

    $cnt = 0;

    while (($line = fgetcsv($handle,  0, "\t")) !== FALSE){

        $institutions_list[$line[0]] = $line[1];

    }
    
}
fclose($handle);



/*$schools = ["Harvard University","Massachusetts General Hospital", "Rockefeller", "Johns Hopkins University","University of Pennsylvania (Perelman)","New York University (Grossman)","Stanford University","Columbia University","Mayo Clinic School of Medicine (Alix)","University of California--Los Angeles (Geffen)","University of California--San Francisco","Washington University in St. Louis","Cornell University (Weill)","Duke University","University of Washington","University of Pittsburgh","University of Michigan--Ann Arbor","Yale University","University of Chicago (Pritzker)","Northwestern University (Feinberg)","Vanderbilt University","Icahn School of Medicine at Mount Sinai","University of California--San Diego","Baylor College of Medicine","University of North Carolina--Chapel Hill","Case Western Reserve University","Emory University","University of Texas Southwestern Medical Center","University of Wisconsin--Madison","Oregon Health and Science University","Boston University","University of Virginia","University of Alabama--Birmingham","University of Colorado","University of Southern California (Keck)","Ohio State University","University of Iowa (Carver)","University of Maryland","University of Rochester","Brown University (Alpert)","University of Utah","Albert Einstein College of Medicine","University of California--Davis","University of Florida","University of Minnesota","Georgetown University","University of California--Irvine","University of Cincinnati","Indiana University--Indianapolis","University of Massachusetts--Worcester","University of South Florida","Dartmouth College (Geisel)","University of Miami (Miller)","Wake Forest University","Tufts University","University of Connecticut","University of Illinois","University of Texas Health Science Center--San Antonio","Thomas Jefferson University (Kimmel)","George Washington University","Medical University of South Carolina","Rush University","Stony Brook University--SUNY","University of Arizona--Tucson","University of Hawaii--Manoa (Burns)","University of Kansas Medical Center","University of Nebraska Medical Center","Temple University (Katz)","University of Vermont (Larner)","University of Kentucky","Virginia Commonwealth University","Hofstra University","Rutgers New Jersey Medical School--Newark","University of Oklahoma","Wayne State University","Rutgers Robert Wood Johnson Medical School--New Brunswick","Saint Louis University","Texas A&M University","University of Tennessee Health Science Center","University of Louisville","University at Buffalo--SUNY (Jacobs)","University of Missouri","University of Arkansas for Medical Sciences","University of New Mexico","Virginia Tech Carilion School of Medicine (Carilion)","Augusta University","SUNY Upstate Medical University","University of Central Florida","West Virginia University","Loyola University Chicago (Stritch)","University of Missouri--Kansas City","Texas Tech University Health Sciences Center","Drexel University","University of California--Riverside","University of South Carolina","Cooper Medical School of Rowan University","East Carolina University (Brody)","Eastern Virginia Medical School","East Tennessee State University (Quillen)","Edward Via College of Osteopathic Medicine","Florida Atlantic University (Schmidt)","Florida International University (Wertheim)","Florida State University","Howard University","Lake Erie College of Osteopathic Medicine","Lincoln Memorial University (DeBusk)","Louisiana State University Health Sciences Center--Shreveport","Marshall University (Edwards)","New York Medical College","Nova Southeastern University Patel College of Osteopathic Medicine (Patel)","Ohio University","Oklahoma State University","Quinnipiac University","Rowan University School of Osteopathic Medicine","Touro University California 2","University of Nevada--Reno","University of New England","University of North Texas Health Science Center","University of Pikeville","University of Toledo","Western University of Health Sciences","West Virginia School of Osteopathic Medicine","William Carey University College of Osteopathic Medicine","Wright State University (Boonshoft)","Alabama College of Osteopathic Medicine 1","Albany Medical College 1","Arkansas College of Osteopathic Medicine","A.T. Still University of Health Sciences--Kirksville 1","A.T. Still University of Health Sciences--Mesa 1","Burrell College of Osteopathic Medicine 1","California Health Sciences University College of Osteopathic Medicine 1","California Northstate University 1","California University of Science and Medicine","Campbell University (Wallace) 1","Carle Illinois College of Medicine 1","Central Michigan University 1","Creighton University 1","CUNY School of Medicine 1","Des Moines University 1","Geisinger Commonwealth School of Medicine 1","Idaho College of Osteopathic Medicine 1","Kaiser Permanente School of Medicine 1","Kansas City University of Medicine and Biosciences 1","Liberty University College of Osteopathic Medicine 1","Loma Linda University 1","Louisiana State University Health Sciences Center--New Orleans 1","Marian University College of Osteopathic Medicine 1","Medical College of Wisconsin 1","Meharry Medical College 1","Mercer University 1","Michigan State University College of Human Medicine (Broad) 1","Michigan State University College of Osteopathic Medicine 1","Midwestern University 1","Midwestern University 1","Morehouse School of Medicine 1","New York Institute of Technology 1","New York University--Long Island","Northeast Ohio Medical University 1","Nova Southeastern University Patel College of Allopathic Medicine","Oakland University 1","Pacific Northwest University of Health Sciences 1","Pennsylvania State University College of Medicine 1","Philadelphia College of Osteopathic Medicine 1","Ponce Health Sciences University 1","Rocky Vista University 1","Rosalind Franklin University of Medicine and Science 1","San Juan Bautista School of Medicine 1","Seton Hall University (Hackensack Meridian)","Southern Illinois University--Springfield 1","SUNY Downstate Medical Center 1","Texas Tech University Health Sciences Center--El Paso 1","Touro College of Osteopathic Medicine 1","Tulane University 1","Uniformed Services University of the Health Sciences (Hebert) 1","Universidad Central del Caribe 1","University of Arizona--Phoenix 1","University of Mississippi 1","University of Nevada--Las Vegas 1","University of North Dakota 1","University of Puerto Rico School of Medicine 1","University of South Alabama 1","University of South Carolina--Greenville 1","University of South Dakota (Sanford) 1","University of Texas--Austin (Dell) 1","University of Texas Health Science Center--Houston (McGovern) 1","University of Texas Medical Branch--Galveston 1","University of Texas--Rio Grande Valley 1","University of the Incarnate Word 1","Washington State University (Floyd) 1","Western Michigan University 1"];

$schools0 = [];
$cnt = 0;
foreach($schools as &$school){

    $school = strtolower($school);

    //echo $school;
   // exit;

    $school = str_replace("university", "", $school);
    $school = str_replace("college", "", $school);
    $school = str_replace("of", "", $school);

    $school = trim(preg_replace("/\([^)]+\)/","",$school));

    if($cnt < 10) $schools0[$school] = 1000;
    elseif($cnt >= 10 && $cnt < 20) $schools0[$school] = 800;
    elseif($cnt >= 20 && $cnt < 40) $schools0[$school] = 600;
    elseif($cnt >= 40 && $cnt < 60) $schools0[$school] = 400;
    elseif($cnt >= 60 && $cnt < 100) $schools0[$school] = 200;
    elseif($cnt >= 100 && $cnt < 200) $schools0[$school] = 100;


    $cnt++;
}
*/

// https://www.usnews.com/best-graduate-schools/top-medical-schools/research-rankings

/*var arr = [];

$('.DetailCardCompare__CardText-sc-1x70p5o-2 h3').each(function(){
  arr.push($(this).text());
});


function downloadObjectAsJson(exportObj, exportName){
    var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(exportObj));
    var downloadAnchorNode = document.createElement('a');
    downloadAnchorNode.setAttribute("href",     dataStr);
    downloadAnchorNode.setAttribute("download", exportName + ".json");
    document.body.appendChild(downloadAnchorNode); // required for firefox
    downloadAnchorNode.click();
    downloadAnchorNode.remove();
  }
*/


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

                //$content_key = '';
                /*foreach($line as $k => $v){

                    if($v == "FY") $year_key = $k;
 
                    //if($v == "PROJECT_TERMS") $content_key = $k; // || $v == "PROJECT_TITLE"
                    if($v == "ORG_NAME") $org_key = $k; // 

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

            //$v = $line[$year_key];
            
            //if(!is_numeric($v) || 1984 < $v || $v > 2020) ) continue;

           

/*            $school = str_replace("university", "", strtolower($line[$org_key]));
            $school = str_replace("college", "", $school);
            $school = str_replace("of", "", $school);

            $school = trim(preg_replace("/\([^)]+\)/","",$school));

            $institutions[$line[$org_key]] = [$school, isset($schools0[$school]) ? $schools0[$school] : 'unknown institution: '. $str];

            
*/


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


   
    /*if ( ($handle1 = fopen($base_path."institutions_list.csv", "w") ) !== FALSE) {

        foreach($institutions as $key => $arr){

            fputcsv($handle1, [$key, $arr[0], $arr[1]]);
               
        }
    }
    fclose($handle1);*/






    foreach($grants as $arr){

        //$arr_str = explode(" ", $arr[1]);

        //print_r($arr[0]);
        //exit;

        if(strpos(strtolower($arr[0]), "gene")==false){
            continue;
        } 


        $arr_str = explode(" ", $arr[0]);

        $arr_str = array_filter($arr_str);

        foreach($arr_str as $val){

            $val = trim($val);

            if(isset($input_genes[strtolower($val)])){ 

                foreach($arr_str as $v){

                    $v = trim($v);

                    if(is_numeric($v) && 1984 < $v && $v < 2021){

                        if(!isset($input_genes[strtolower($val)][$v])){

                            //echo $arr[1];

                            $input_genes[strtolower($val)][$v] = [$arr[1]];
                        }
                        else{
                            $input_genes[strtolower($val)][$v][] = $arr[1];

                        } 
                        
                        break;                      
                    }

                }

                break;

            }
        }
        
    }


    //if($cnt > 21) break;
    echo "\r\n";
    echo $cnt++;
    echo "\r\n";
}


     


if ( ($handle1 = fopen($base_path."get_all_grants_per_gene_per_year_from_csv_files_require_the_term_gene_with_univs.csv", "w") ) !== FALSE) {

        
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
               

            }
            

           
        }

}
fclose($handle1);








