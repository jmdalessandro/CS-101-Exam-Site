<?php


    //$url = 'https://web.njit.edu/~hy276/beta/back/getQuestions.php';
    $url = 'https://web.njit.edu/~jmd35/beta/back/getQuestions.php';
    
    //$getdata = file_get_contents('php://input');
    
    	
	   // $to_back = json_decode($getdata,true);
      
     //$getdata = ["username" => "admin",
       //          "password" => "teacher",
         //        ];
    
 
   
   $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_POST => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POSTFIELDS => $getdata
        ));

    $response = curl_exec($curl);
    curl_close($curl);
    
    
    echo $response;

    ?>
