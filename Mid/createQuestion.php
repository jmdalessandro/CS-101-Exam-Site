<?php


   $url = 'https://web.njit.edu/~hy276/beta/back/createQuestion.php';
    //$url = 'https://web.njit.edu/~jmd35/beta/back/createQuestionback.php';
    
    $getData = file_get_contents('php://input');
    
    	
	   //$to_back = json_decode($getdata,true);
      
     /*$getdata = ["question_text" => "Hello",
            "parameters" => "Recur",
            "topic" => "Loop",
            "difficulty" => "Hard",
            "input1" => "1",
            "output1" => "2",
            "input2" => "3",
            "output2" => "4"
            ];
       $to_back = json_encode($getdata, true);    
    */
 
   
   $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_POST => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POSTFIELDS => $getData
        ));

    $response = curl_exec($curl);
    curl_close($curl);
    
    
    echo $response;

    ?>
