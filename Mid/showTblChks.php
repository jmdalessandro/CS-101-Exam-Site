<?php
    $url = 'https://web.njit.edu/~hy276/beta/back/showTblChks.php';
    //$url = 'https://web.njit.edu/~jmd35/beta/back/getQuestions.php';
    
    
    
    	
	 
    
 
   
   $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        ));
    $response = curl_exec($curl);
    curl_close($curl);
    
    
    echo $response;
    ?>
