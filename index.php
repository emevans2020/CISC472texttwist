<?php
	//header('Access-Control-Allow-Origin: *');

    function generate_rack($n){
		$tileBag = "AAAAAAAAABBCCDDDDEEEEEEEEEEEEFFGGGHHIIIIIIIIIJKLLLLMMNNNNNNOOOOOOOOPPQRRRRRRSSSSTTTTTTUUUUVVWWXYYZ";
		$rack_letters = substr(str_shuffle($tileBag), 0, $n);
		
		$temp = str_split($rack_letters);
		sort($temp);
		return implode($temp);
	  };
	  $rack = generate_rack(7);

	  $myrack = $rack;
	  $racks = [];
	  for($i = 0; $i < pow(2, strlen($myrack)); $i++){
		  $ans = "";
		  for($j = 0; $j < strlen($myrack); $j++){
			  //if the jth digit of i is 1 then include letter
			  if (($i >> $j) % 2) {
				$ans .= $myrack[$j];
			  }
		  }
		  if (strlen($ans) > 1){
				$racks[] = $ans;	
		  }
	  }
	  $racks = array_unique($racks);
	  print_r($racks);
	  
	  $response = array('letters' => $rack, 'words' => array());
	  //   print_r($racks);
	  //this is the basic way of getting a database handler from PDO, PHP's built in quasi-ORM
	  $dbhandle = new PDO("sqlite:scrabble.sqlite") or die("Failed to open DB");
	  if (!$dbhandle) die ($error);
	  
		foreach ($racks as $index=>$r){
			if (array_key_exists($index, $racks)){
			$query = "SELECT words FROM racks WHERE rack = '".$r."'";
			$statement = $dbhandle->prepare($query);
			//$statement->bindParam(1, $r, PDO::PARAM_STR);
			$statement->execute();
			
			$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($results as $row) {
			$response['words'] = array_merge(
				$response['words'],
				explode('@@', $row['words'])
			);
		}
	}

        // foreach ($results as $answer) {
        //    $tmparr = explode('@@', $answer);
        //    foreach ($tmparr as $a){
        //     $response['words']=array_push(
        //        $response['words'], $a);
        //    }
		// }
	// }
}

	  //this part is perhaps overkill but I wanted to set the HTTP headers and status code
	  //making to this line means everything was great with this request
	  header('HTTP/1.1 200 OK');
	  //this lets the browser know to expect json
	  header('Content-Type: application/json');
	  //this creates json and gives it back to the browser
	  echo json_encode($response);
?>