<?php


    // storing  request (ie, get/post) global array to a variable  
    $requestData= $_REQUEST;
    $columns = array( 
        0 => 'id',        
		1 => 'villageName',
		2 => 'post',
		3 => 'anchal',
		4 => 'district',
        5 => 'action'
    );    
    $data=array();	
    try { 
	require_once 'includes/dbconn.php';


	$sql = "SELECT * from village";
	$totalData = $db->query("select count(*) from ($sql) b")->fetchColumn(); 
	$totalFiltered = $totalData;
	// if( !empty($requestData['search']['value']) ) {
	// 		$rd=$requestData['search']['value'];
	// 	$sql.=" where (village_name LIKE '%$rd%')";    
	// 	$totalFiltered = $db->query("select count(*) from ($sql) b")->fetchColumn();
	// 	}
	// $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	// echo $sql;
	$data=$db->query($sql)->fetchAll();

	$objArr = array();
	foreach ($data as $row) {
	 	
	 		$object = new stdClass();

	 	$id = $row['id'];
	 	$action = "<div class='flex justify-left items-center'>

				
						<a class='flex items-center mr-5' id=\"edit-button\" href='javascript:;' onclick=\"load_data('$id')\"
						data-tw-toggle=\"modal\" data-tw-target=\"#header-footer-modal-preview-view\">
						<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" icon-name=\"check-square\" data-lucide=\"check-square\" class=\"lucide lucide-check-square w-4 h-4 mr-1\"><polyline points=\"9 11 12 14 22 4\"></polyline><path d=\"M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11\"></path></svg></a>

						<a class=\"flex items-center text-danger px-5\" onclick=\"remove_data('$id')\">  
										<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" icon-name=\"trash-2\" data-lucide=\"trash-2\" class=\"lucide lucide-trash-2 w-4 h-4 mr-1\"><polyline points=\"3 6 5 6 21 6\"></polyline><path d=\"M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2\"></path><line x1=\"10\" y1=\"11\" x2=\"10\" y2=\"17\"></line><line x1=\"14\" y1=\"11\" x2=\"14\" y2=\"17\"></line></svg>
									</a>
									</div>";

	 	$object->id 	= $row['id'];
		 $id = $row['id'];
		 $object->village = $row['villageName'];
		 $object->post = $row['post'];
		 $object->anchal = $row['anchal'];
		 $object->district = $row['district'];
		//   $object->district	= "<a href='index_trip.php?type=b.dist_id&value=$id'>$district</a>";
	 	$object->action 	= $action;

	 	array_push($objArr, $object);
		// print_r($objArr);
	} 


			
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		print "<br>$sql";
		die();
	}
    $json_data = array(
			//"sql"			  => $sql,
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $objArr   // total data array
			);	
    echo json_encode($json_data);
?>

