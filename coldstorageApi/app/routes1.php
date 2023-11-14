<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
require_once 'db.php';


return function (App $app) {
	$burl='/dmartapi'; //Define base directory
    $app->options("$burl/{routes:.*}", function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });
	/*
	$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
	});
	*/
	
    $app->get("$burl/", function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });
	// Begin REST API for purchaseInv Entity
	$app->post("$burl/purinv", function (Request $request, Response $response) {
		try {
			//$body = $request->getBody();
			$inv = $request->getParsedBody();
			
			
			$sql = "INSERT INTO purreggrn(invoiceno,invoicedate,invoiceamt,suppid,discount,netamt) 
			VALUES(:invoiceno,:invoicedate,:invoiceamt,:suppid,:discount,0)";
			$db = getconn();
			// $db->beginTransaction();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":invoiceno", $inv['invoiceno']);
			$stmt->bindParam(":invoicedate", $inv['invoicedate']);
			$stmt->bindParam(":invoiceamt", $inv['invoiceamt']);
			$stmt->bindParam(":suppid", $inv['suppid']);
			$stmt->bindParam(":discount", $inv['discount']);
			$stmt->execute();

			// Get the last inserted ID for the GRN
			$grnno = $db->lastInsertId();

			$sql = "INSERT INTO purregprod(grnno,prodcode,qty,free,batchno,purrate,mrp,expdate,cgstp,cgstamt,packing,discount,total) 
					VALUES(:grnno,:prodcode,:qty,:free,:batchno,:purrate,:mrp,:expdate,:cgstp,:cgstamt,:packing,:discount,:total)";
			$db = getconn();
			
			$stmt = $db->prepare($sql);
			
			foreach($inv['items'] as $item) {
				$stmt->bindParam(":prodcode", $item['prodcode']);
				$stmt->bindParam(":qty", $item['qty']);
				$stmt->bindParam(":free", $item['free']);
				$stmt->bindParam(":batchno", $item['batchno']);
				$stmt->bindParam(":purrate", $item['purrate']);
				$stmt->bindParam(":mrp", $item['mrp']);
				$stmt->bindParam(":expdate", $item['expdate']);
				$stmt->bindParam(":cgstp", $item['cgstp']);
				$stmt->bindParam(":cgstamt", $item['cgstamt']);
				$stmt->bindParam(":packing", $item['packing']);
				$stmt->bindParam(":discount", $item['discount']);
				$stmt->bindParam(":total", $item['total']);
				$stmt->bindParam(":grnno", $grnno);
				$stmt->execute();
			}
				
			$db->exec("UPDATE purreggrn SET netamt='".$inv['netamt']."' where grnno='$grnno'");
			// $db->commit();
			// if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			// $db = null;$status=201;
			
			$status=200;
			$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$inv);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status);
	});
	// End REST API for purchaseInv Entity

	// Begin REST API for Received Items Entity
	$app->post("$burl/receiveitem", function (Request $request, Response $response) {
		try {
			//$body = $request->getBody();
			$inv = $request->getParsedBody();
			
			$sql = "UPDATE transfer SET transferglbid=CONCAT('DMT', '', `transferid`), transferstatus='Completed' where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);
	
			foreach($inv as $item) 
			{
				$stmt->bindParam(":id", $item);
				$stmt->execute();
			}
			// $db->commit();
			// if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			// $db = null;$status=201;
			
			$status=200;
			$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$inv);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status);
	});
	// End REST API for Received Items Entity

	// Begin REST API for Transfer Stock Entity
	$app->post("$burl/tranferstk", function (Request $request, Response $response) {
		try {
			//$body = $request->getBody();
			$inv = $request->getParsedBody();
			$db = getconn();

			$transferid=$db->query("SELECT max(transferid)+1 from transfer")->fetch()[0];
			$transferdate=date('Y-m-d H:i:s');


			$sql = "INSERT INTO transfer(netamt,receivebrnid,sendingbrnid,transferdate,transferid,discountamt,actualamt,gstamt) 
			VALUES(0,:receivebrnid,:sendingbrnid,:transferdate,:transferid,:discountamt,:actualamt,:gstamt)";

			$db->beginTransaction();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":receivebrnid", $inv['receivebrnid']);
			$stmt->bindParam(":sendingbrnid", $inv['sendingbrnid']);
			$stmt->bindParam(":transferid",$transferid);
			$stmt->bindParam(":transferdate",$transferdate);
			$stmt->bindParam(":discountamt", $inv['discountamt']);
			$stmt->bindParam(":actualamt", $inv['actualamt']);
			$stmt->bindParam(":gstamt", $inv['gstamt']);
			$stmt->execute();


			$sql = "INSERT INTO transferstk(drugcode,batchno,branchid,cgst,discount,expdate,mrp,packing,pcscount,purchasecost,sendingbrnid,transferid,amount,qty) 
					VALUES(:drugcode,:batchno,:branchid,:cgst,:discount,:expdate,:mrp,:packing,:pcscount,:purchasecost,:sendingbrnid,:transferid,:amount,:qty)";
			
			
			$stmt = $db->prepare($sql);
			
			foreach($inv['items'] as $item) {
				$stmt->bindParam(":drugcode", $item['drugcode']);
				$stmt->bindParam(":batchno", $item['batchno']);
				$stmt->bindParam(":branchid", $inv['receivebrnid']);
				$stmt->bindParam(":cgst", $item['cgst']);
				$stmt->bindParam(":discount", $item['discount']);
				$stmt->bindParam(":expdate", $item['expdate']);
				$stmt->bindParam(":mrp", $item['mrp']);
				$stmt->bindParam(":packing", $item['packing']);
				$stmt->bindParam(":pcscount", $item['pcscount']);
				$stmt->bindParam(":purchasecost", $item['purchasecost']);
				$stmt->bindParam(":sendingbrnid", $inv['sendingbrnid']);
				$stmt->bindParam(":amount", $item['amount']);
				$stmt->bindParam(":qty", $item['qty']);
				$stmt->bindParam(":transferid", $transferid);
				$stmt->execute();
			}
			$db->exec("UPDATE transfer SET netamt='".$inv['netamt']."', transferstatus='Pending' where transferid='$transferid'");
			// $db->exec("UPDATE purreggrn SET netamt='".$inv['netamt']."' where grnno='$grnno'");
			$db->commit();
			// if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;
			
			$status=200;
			$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$inv);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status);
	});
	// End REST API for Transfer Stock Entity
/// Start Retail Sales Invoice///
$app->post("$burl/retailsaleinv", function (Request $request, Response $response) {
	try {
		//$body = $request->getBody();
		$sinv = $request->getParsedBody();
		//$sql= "SELECT cgstp, cgstamt, sgstp, sgstamt, igstp, igstamt FROM purregprod where prodcode=':prodcode' and batchno=':batchno'";
		
		$date=date('Y-m-d H:i:s');
		
		$sql = "INSERT INTO saleregbill(date,patientid,doctorid,paymentmode,discount,netamount,actualamt,servicecharge,
				othercharge,cgstamt,sgstamt,igstamt,userid, branchid) VALUES(:date,:patientid,:doctorid,NULL,0,:netamount,0,0,0,0,0,0,1,:branch)";
		$db = getconn();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":date", $date);
		$stmt->bindParam(":patientid", $sinv['patientid']);
		$stmt->bindParam(":doctorid", $sinv['doctorid']);
		$stmt->bindParam(":netamount", $sinv['netamount']);
		$stmt->bindParam(":branch", $sinv['branch']);
		
		$stmt->execute();
		$branch=$sinv['branch'];
		$billno = $db->lastInsertId();
		$sql = "INSERT INTO saleregprod(prodcode,qty,batchno,amount,expdate,billno,cgstp,sgstp,igstp,discountper,discountamt,price,branchid) 
			VALUES(:prodcode,:qty,:batchno,0,:expdate,:billno,:cgstp,0,0,:discountper,:discountamt,0,:branch)";
		$db = getconn();
		$stmt = $db->prepare($sql);
		
		foreach($sinv['items'] as $item) {
			$stmt->bindParam(":prodcode", $item['prodcode']);
			$stmt->bindParam(":qty", $item['qty']);
			$stmt->bindParam(":batchno", $item['batchno']);
			$stmt->bindParam(":expdate", $item['expdate']);
			$stmt->bindParam(":billno", $billno);
			$stmt->bindParam(":branch", $branch);
			$stmt->bindParam(":discountper", $item['discountper']);
			$stmt->bindParam(":discountamt", $item['discountamt']);
			$stmt->bindParam(":cgstp", $item['cgstp']);
			$stmt->execute();
		}
		$db->exec("UPDATE saleregbill SET netamount='".$sinv['netamount']."' where billno='$billno'");
		//$db->commit();
		//if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
		//$db = null;$status=201;
		//$data=array("item"=>$inv);
		$status=200;
		$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$sinv);
	} catch(Exception $e) {
		$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
	}
	$response->getBody()->write(json_encode($data));
		return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus($status);
	});

/////End Retail sale Invoice//////

	// START REST API for Transfer Stock Entity

	$app->get("$burl/retailsaleinfo/{field}/{srch}", function (Request $request, Response $response, $args) {
        try {
			$field=$args['field'];
			$srch=$args['srch'];
			$db=getconn();
			$params = $request->getQueryParams();
			$branch=$params['branch'];
			//$branch=4;
			
			$sql="select code id, concat(name,' ',mstock.batchno,' #',mstock.qty) text, mstock.qty, mstock.batchno, mstock.mrp, mstock.pcsrate, mstock.expdate, purregprod.discount, purregprod.cgstp from druginfo 
			      left join purregprod on druginfo.Code=purregprod.prodcode
				  inner join mstock on druginfo.Code=mstock.drugcode and mstock.qty>0 and mstock.branchid=$branch";
			if ($srch)
				$sql.=" where $field like '%$srch%'";
			$sql.="limit 20";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage(),'data'=>$params);$status=400;
			}
			$payload = json_encode(array('results'=>$data));
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status);
		});

	// End REST API for Transfer Stock Entity

	$app->post("$burl/saleinv", function (Request $request, Response $response) {
		try {
			//$body = $request->getBody();
			$sinv = $request->getParsedBody();
			//$sql= "SELECT cgstp, cgstamt, sgstp, sgstamt, igstp, igstamt FROM purregprod where prodcode=':prodcode' and batchno=':batchno'";
			
			$date=date('Y-m-d H:i:s');
			
			$sql = "INSERT INTO saleregbill(date,patientid,doctorid,paymentmode,discount,netamount,actualamt,servicecharge,
			        othercharge,cgstamt,sgstamt,igstamt,userid, branchid) VALUES(:date,:patientid,:doctorid,NULL,0,:netamount,0,0,0,0,0,0,1,:branch)";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":date", $date);
			$stmt->bindParam(":patientid", $sinv['patientid']);
			$stmt->bindParam(":doctorid", $sinv['doctorid']);
			$stmt->bindParam(":netamount", $sinv['netamount']);
			$stmt->bindParam(":branch", $sinv['branch']);
			
			$stmt->execute();
			$branch=$sinv['branch'];
			$billno = $db->lastInsertId();
			$sql = "INSERT INTO saleregprod(prodcode,qty,batchno,amount,expdate,billno,cgstp,sgstp,igstp,discountper,discountamt,price,branchid) 
				VALUES(:prodcode,:qty,:batchno,0,:expdate,:billno,0,0,0,0,0,0,:branch)";
			$db = getconn();
			$stmt = $db->prepare($sql);
			
			foreach($sinv['items'] as $item) {
				$stmt->bindParam(":prodcode", $item['prodcode']);
				$stmt->bindParam(":qty", $item['qty']);
				$stmt->bindParam(":batchno", $item['batchno']);
				$stmt->bindParam(":expdate", $item['expdate']);
				$stmt->bindParam(":billno", $billno);
				$stmt->bindParam(":branch", $branch);
				$stmt->execute();
			}
			$db->exec("UPDATE saleregbill SET netamount='".$sinv['netamount']."' where billno='$billno'");
			//$db->commit();
			//if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			//$db = null;$status=201;
			//$data=array("item"=>$inv);
			$status=200;
			$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$sinv);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status);
		});
	
	/////End sale Invoice///////

	///// START REST API FOR SALE RETURN 

	$app->get("$burl/salereturn/{field}/{srch}", function (Request $request, Response $response, $args) {
        try {
			$field=$args['field'];
			$srch=$args['srch'];
			$db=getconn();
			$params = $request->getQueryParams();
			$branch=$params['branch'];
			//$branch=4;
			
			$sql="select saleregbill.billno id,concat(druginfo.Name,' ',saleregprod.batchno,' #',saleregprod.qty) text,druginfo.Name,saleregprod.batchno,saleregprod.qty,saleregbill.actualamt,saleregprod.expdate,saleregprod.cgstp,saleregprod.discountper,saleregprod.discountamt,
			saleregprod.price,patientdet.name customer,doctordet.name doctor from saleregbill
			JOIN saleregprod ON saleregbill.billno=saleregprod.billno
			JOIN druginfo on saleregprod.prodcode=druginfo.Code
			JOIN patientdet ON saleregbill.patientid=patientdet.id
            JOIN doctordet ON saleregbill.doctorid=doctordet.id
			and qty>0 and saleregprod.branchid=$branch";
			if ($srch)
				$sql.=" where $field like '%$srch%'";
			$sql.="limit 20";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage(),'data'=>$params);$status=400;
			}
			$payload = json_encode(array('results'=>$data));
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status);
	});

	// END REST API FOR SALE RETURN 

	// START REST API FOR PRODUCT INFO 

	$app->get("$burl/prodinfo/{field}/{srch}", function (Request $request, Response $response, $args) {
        try {
			$field=$args['field'];
			$srch=$args['srch'];
			$db=getconn();
			$sql="select code id,concat(name,' ',avlqty,' ',MRP) text,Category,Descn,Type,Packing,Unit,MfgComp,Salt,MinOrdQty,PurchaseRate,MRP,RateB,RateC,Discount,MinStock,MaxStock,Narcotic,
			ScheduleH,ScheduleH1,AvlQty,Location,gsttaxcat,gsthsncode,cgst,sgst,igst,ingredientid from druginfo";
			if ($srch)
				$sql.=" where $field like '%$srch%'";
			$sql.=" limit 20";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$payload = json_encode(array('results'=>$data));
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
    });

	// END REST API FOR SALE RETURN 

	// START REST API FOR SALE INFO

	$app->get("$burl/saleinfo/{field}/{srch}", function (Request $request, Response $response, $args) {
        try {
			$field=$args['field'];
			$srch=$args['srch'];
			$db=getconn();
			$params = $request->getQueryParams();
			$branch=$params['branch'];
			//$branch=4;
			
			$sql="select code id,concat(name,' ',batchno,' #',qty) text, mstock.qty, mstock.batchno, mstock.MRP, mstock.pcsrate,mstock.expdate from druginfo 
			      inner join mstock on druginfo.Code=mstock.drugcode and qty>0 and branchid=$branch";
			if ($srch)
				$sql.=" where $field like '%$srch%'";
			$sql.="limit 20";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage(),'data'=>$params);$status=400;
			}
			$payload = json_encode(array('results'=>$data));
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status);
		});

		// END REST API FOR SALE RETURN 
		
	// Begin REST API for Transfer Entity
	$app->get("$burl/stocktrnfer/{field}/{srch}", function (Request $request, Response $response, $args) {
        try {
			$field=$args['field'];
			$srch=$args['srch'];
			$db=getconn();
			$params = $request->getQueryParams();
			$branch=$params['branch'];
			$sql="select code id,concat(name,' ',ms.qty) text,ms.purchasecost,ms.packing,ms.cgst,ms.qty,ms.batchno,di.MRP,ms.pcsrate,ms.expdate from druginfo di join mstock ms on di.Code=ms.drugcode and branchid=$branch";
			if ($srch)
				$sql.=" where $field like '%$srch%'";
			$sql.=" limit 20";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$payload = json_encode(array('results'=>$data));
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
    });
	// END REST API for Transfer Entity
	
	//Begin REST API for mfrcomp entity
	$app->group("$burl/mfrcomp", function (Group $group) {
        $group->get('', function (Request $request, Response $response, $args) {
		try {
			$params = $request->getQueryParams();
			$filter='';
			foreach($params as $p=>$v) {
				if ($v)
					switch($p)
						{
						case 'id';
							$filter.="$p='$v'";break;	
						default;
							if ($filter) $filter.=" and ";
							$filter.="$p like '%$v%'";
							break;
						}
			}
			$db=getconn();
			$sql="select * from mfrcomp";
			if ($filter)
				$sql.=" where $filter";
			else $sql.=" limit 200";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$payload = json_encode($data);
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
        $group->get("/{id}", function (Request $request, Response $response, $args) {
		try {
			$db=getconn();
			$result=$db->query('select * from mfrcomp where id='.$args['id']);
			$data=$result->fetch(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->post('', function (Request $request, Response $response, $args) {
		try {
			//$body = $request->getBody();
			$drug = $request->getParsedBody();

			$sql = "INSERT INTO mfrcomp(id,name,address,contactno,shortname) 
				VALUES(:id,:name,:address,:contactno,:shortname)";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $drug['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":address", $drug['address']);
			$stmt->bindParam(":contactno", $drug['contactno']);
			$stmt->bindParam(":shortname", $drug['shortname']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;
			//$data=array("item"=>$drug);
			$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->put('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			$drug = $request->getParsedBody();
			$sql = "update mfrcomp set name=:name,address=:address,contactno=:contactno,
					shortname=:shortname where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);	
			$stmt->bindParam(":id", $args['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":address", $drug['address']);
			$stmt->bindParam(":contactno", $drug['contactno']);
			$stmt->bindParam(":shortname", $drug['shortname']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;$data=null;
			$data = array("status"=>"Ok","msg"=>$msg,"item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage(),"item"=>$sql);$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
				
		$group->delete('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			//$mfrcomp = json_decode($body);
			$sql = "delete from mfrcomp where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $args['id']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="deleted successfully"; else $msg="no deletion";
			$db = null;$status=201;
			$data = array("status"=>"Ok","msg"=>$msg);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
    });
	//End of REST API for MfrCOMP entity
	
	//Begin REST API for druginfo entity
	$app->group("$burl/druginfo", function (Group $group) {
        $group->get('', function (Request $request, Response $response, $args) {
		try {
			$params = $request->getQueryParams();
			$filter='';
			foreach($params as $p=>$v) {
				if ($v)
					switch($p)
						{
						case 'id';
							$filter.="$p='$v'";break;	
						default;
							if ($filter) $filter.=" and ";
							$filter.="$p like '%$v%'";
							break;
						}
			}
			$db=getconn();
			$sql="select * from druginfo";
			if ($filter)
				$sql.=" where $filter";
			else $sql.=" limit 200";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$payload = json_encode($data);
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
        $group->get("/{id}", function (Request $request, Response $response, $args) {
		try {
			$db=getconn();
			$result=$db->query('select * from druginfo where code='.$args['id']);
			$data=$result->fetch(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->post('', function (Request $request, Response $response, $args) {
		try {
			//$body = $request->getBody();
			$drug = $request->getParsedBody();
			/*
		  `Code` bigint NOT NULL,
		  `Name` varchar(45) DEFAULT NULL,
		  `Category` varchar(45) DEFAULT NULL,
		  `Descn` varchar(50) DEFAULT NULL,
		  `Type` varchar(20) DEFAULT NULL,
		  `Packing` varchar(10) DEFAULT NULL,
		  `Unit` varchar(12) DEFAULT NULL,
		  `MfgComp` bigint DEFAULT NULL,
		  `Salt` int DEFAULT NULL,
		  `MinOrdQty` int DEFAULT NULL,
		  `PurchaseRate` float DEFAULT NULL,
		  `MRP` float DEFAULT NULL,
		  `RateB` float DEFAULT NULL,
		  `RateC` float DEFAULT NULL,
		  `Discount` float DEFAULT NULL,
		  `MinStock` int DEFAULT NULL,
		  `MaxStock` int DEFAULT NULL,
		  `Narcotic` char(1) DEFAULT NULL,
		  `ScheduleH` char(1) DEFAULT NULL,
		  `ScheduleH1` char(1) DEFAULT NULL,
		  `AvlQty` bigint DEFAULT NULL,
		  `Location` varchar(10) DEFAULT NULL,
		  `gsttaxcat` int DEFAULT NULL,
		  `gsthsncode` varchar(20) DEFAULT NULL,
		  `cgst` float DEFAULT NULL,
		  `sgst` float DEFAULT NULL,
		  `igst` float DEFAULT NULL,
		  `ingredientid` bigint DEFAULT NULL,
			  Code,Name,Category,Descn,Type,Packing,Unit,MfgComp,Salt,MinOrdQty,PurchaseRate,
  MRP,RateB,RateC,Discount,MinStock,MaxStock,Narcotic,ScheduleH,ScheduleH1,AvlQty,
  Location,gsttaxcat,gsthsncode,cgst,sgst,igst,ingredientid
			$sql = "INSERT INTO druginfo(Code,Name,Category,Descn,Type,Packing,Unit,MfgComp,Salt,
			MinOrdQty,PurchaseRate,MRP,RateB,RateC,Discount,MinStock,MaxStock,Narcotic,ScheduleH,
			ScheduleH1,AvlQty,Location,gsttaxcat,gsthsncode,cgst,sgst,igst,ingredientid) 
				VALUES(:code,:name,:category,:descn,:type,:packing,:unit,:mfgcomp,:salt,:minordqty,
				:purchaserate,:mrp,:rateb,ratec,:discount,:minstock,:maxstock,:narcotic,:scheduleh,
				:scheduleh1,:avlqty,:location,:gsttaxcat,:gsthsncode,:cgst,:sgst,:igst,:ingredientid
				)";
			*/
			$sql = "INSERT INTO druginfo(Code,Name,Category,Descn,Type,Packing,Unit,MfgComp,Salt,MinOrdQty,PurchaseRate,MRP,RateB,RateC,Discount,MinStock,MaxStock,Narcotic,
			ScheduleH,ScheduleH1,AvlQty,Location,gsttaxcat,gsthsncode,cgst,sgst,igst,ingredientid
			) 
				VALUES(:code,:name,:category,:descn,:type,:packing,:unit,:mfgcomp,:salt,:MinOrdQty,:PurchaseRate,:MRP,:RateB,:RateC,:Discount,:MinStock,:MaxStock,:Narcotic,
			:ScheduleH,:ScheduleH1,:AvlQty,:Location,:gsttaxcat,:gsthsncode,:cgst,:sgst,:igst,:ingredientid
				)";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":code", $args['Code']);
			$stmt->bindParam(":name", $drug['Name']);
			$stmt->bindParam(":category", $drug['Category']);
			$stmt->bindParam(":descn", $drug['Descn']);
			$stmt->bindParam(":type", $drug['Type']);
			$stmt->bindParam(":packing", $drug['Packing']);
			$stmt->bindParam(":unit", $drug['Unit']);
			$stmt->bindParam(":mfgcomp", $drug['MfgComp']);
			$stmt->bindParam(":salt", $drug['Salt']);
			$stmt->bindParam(":MinOrdQty", $drug['MinOrdQty']);
			$stmt->bindParam(":PurchaseRate", $drug['PurchaseRate']);
			$stmt->bindParam(":MRP", $drug['MRP']);
			$stmt->bindParam(":RateB", $drug['RateB']);
			$stmt->bindParam(":RateC", $drug['RateC']);
			$stmt->bindParam(":Discount", $drug['Discount']);
			$stmt->bindParam(":MinStock", $drug['MinStock']);
			$stmt->bindParam(":MaxStock", $drug['MaxStock']);
			$stmt->bindParam(":Narcotic", $drug['Narcotic']);
			$stmt->bindParam(":ScheduleH", $drug['ScheduleH']);
			$stmt->bindParam(":ScheduleH1", $drug['ScheduleH1']);
			$stmt->bindParam(":AvlQty", $drug['AvlQty']);
			$stmt->bindParam(":Location", $drug['Location']);
			$stmt->bindParam(":gsttaxcat", $drug['gsttaxcat']);
			$stmt->bindParam(":gsthsncode", $drug['gsthsncode']);
			$stmt->bindParam(":cgst", $drug['cgst']);
			$stmt->bindParam(":sgst", $drug['sgst']);
			$stmt->bindParam(":igst", $drug['igst']);
			$stmt->bindParam(":ingredientid", $drug['ingredientid']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;
			$data=array("item"=>$drug);
			//$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$mfrcomp);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->put('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			//$mfrcomp = json_decode($body);
			$drug = $request->getParsedBody();
			$sql = "update druginfo set Name=:name,Category=:category,Descn=:descn,
					Type=:type,Packing=:packing,Unit=:unit,MfgComp=:mfgcomp,Salt=:salt,
					MinOrdQty=:MinOrdQty,PurchaseRate=:PurchaseRate,MRP=:MRP,RateB=:RateB,RateC=:RateC,
					Discount=:Discount,MinStock=:MinStock,MaxStock=:MaxStock,Narcotic=:Narcotic,ScheduleH=:ScheduleH,
					ScheduleH1=:ScheduleH1,AvlQty=:AvlQty,Location=:Location,gsttaxcat=:gsttaxcat,gsthsncode=:gsthsncode,
					cgst=:cgst,sgst=:sgst,igst=:igst,ingredientid=:ingredientid
					where code=:code";
			$db = getconn();
			$stmt = $db->prepare($sql);	
			$stmt->bindParam(":code", $drug['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":category", $drug['category']);
			$stmt->bindParam(":descn", $drug['descn']);
			$stmt->bindParam(":type", $drug['type']);
			$stmt->bindParam(":packing", $drug['packing']);
			$stmt->bindParam(":unit", $drug['unit']);
			$stmt->bindParam(":mfgcomp", $drug['mfgcomp']);
			$stmt->bindParam(":salt", $drug['salt']);
			$stmt->bindParam(":MinOrdQty", $drug['MinOrdQty']);
			$stmt->bindParam(":PurchaseRate", $drug['PurchaseRate']);
			$stmt->bindParam(":MRP", $drug['MRP']);
			$stmt->bindParam(":RateB", $drug['RateB']);
			$stmt->bindParam(":RateC", $drug['RateC']);
			$stmt->bindParam(":Discount", $drug['Discount']);
			$stmt->bindParam(":MinStock", $drug['MinStock']);
			$stmt->bindParam(":MaxStock", $drug['MaxStock']);
			$stmt->bindParam(":Narcotic", $drug['Narcotic']);
			$stmt->bindParam(":ScheduleH", $drug['ScheduleH']);
			$stmt->bindParam(":ScheduleH1", $drug['ScheduleH1']);
			$stmt->bindParam(":AvlQty", $drug['AvlQty']);
			$stmt->bindParam(":Location", $drug['Location']);
			$stmt->bindParam(":gsttaxcat", $drug['gsttaxcat']);
			$stmt->bindParam(":gsthsncode", $drug['gsthsncode']);
			$stmt->bindParam(":cgst", $drug['cgst']);
			$stmt->bindParam(":sgst", $drug['sgst']);
			$stmt->bindParam(":igst", $drug['igst']);
			$stmt->bindParam(":ingredientid", $drug['ingredientid']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;$data=null;
			//$data = array("status"=>"Ok","msg"=>"Updated sucessfully");
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage(),"item"=>$sql);$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
				
		$group->delete('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			//$mfrcomp = json_decode($body);
			$sql = "delete from druginfo where code=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $args['id']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;
			$data = null;//array("status"=>"Ok","msg"=>"Deleted sucessfully","item"=>$mfrcomp);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
    });
	//End of REST API for druginfo entity

	//Begin REST API for bank entity
	$app->group("$burl/bank", function (Group $group) {
        $group->get('', function (Request $request, Response $response, $args) {
		try {
			$params = $request->getQueryParams();
			$filter='';
			foreach($params as $p=>$v) {
				if ($v)
					switch($p)
						{
						case 'id';
							$filter.="$p='$v'";break;	
						default;
							if ($filter) $filter.=" and ";
							$filter.="$p like '%$v%'";
							break;
						}
			}
			$db=getconn();
			$sql="select * from bank";
			if ($filter)
				$sql.=" where $filter";
			else $sql.=" limit 200";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$payload = json_encode($data);
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
        $group->get("/{id}", function (Request $request, Response $response, $args) {
		try {
			$db=getconn();
			$result=$db->query('select * from bank where id='.$args['id']);
			$data=$result->fetch(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->post('', function (Request $request, Response $response, $args) {
		try {
			//$body = $request->getBody();
			$drug = $request->getParsedBody();

			$sql = "INSERT INTO bank(id,name,address) 
				VALUES(:id,:name,:address)";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $drug['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":address", $drug['address']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;
			//$data=array("item"=>$drug);
			$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->put('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			$drug = $request->getParsedBody();
			$sql = "update bank set name=:name,address=:address where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);	
			$stmt->bindParam(":id", $args['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":address", $drug['address']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;$data=null;
			$data = array("status"=>"Ok","msg"=>$msg,"item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage(),"item"=>$sql);$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
				
		$group->delete('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			//$bank = json_decode($body);
			$sql = "delete from bank where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $args['id']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="deleted successfully"; else $msg="no deletion";
			$db = null;$status=201;
			$data = array("status"=>"Ok","msg"=>$msg);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
    });
	//End of REST API for bank entity

	//Begin REST API for company entity
	$app->group("$burl/company", function (Group $group) {
        $group->get('', function (Request $request, Response $response, $args) {
		try {
			$params = $request->getQueryParams();
			$filter='';
			foreach($params as $p=>$v) {
				if ($v)
					switch($p)
						{
						case 'id';
							$filter.="$p='$v'";break;	
						default;
							if ($filter) $filter.=" and ";
							$filter.="$p like '%$v%'";
							break;
						}
			}
			$db=getconn();
			$sql="select * from company";
			if ($filter)
				$sql.=" where $filter";
			else $sql.=" limit 200";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$payload = json_encode($data);
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
        $group->get("/{id}", function (Request $request, Response $response, $args) {
		try {
			$db=getconn();
			$result=$db->query('select * from company where id='.$args['id']);
			$data=$result->fetch(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->post('', function (Request $request, Response $response, $args) {
		try {
			//$body = $request->getBody();
			$drug = $request->getParsedBody();

			$sql = "INSERT INTO company(id,name,address1,address2,statecode,branchid,contactno,email,faxno,countrycode,tinno,gstno,cdate,finsyear,fineyear,bankid,dlno,branchname) 
				VALUES(:id,:name,:address1,:address2,:statecode,:branchid,:contactno,:email,:faxno,:countrycode,:tinno,:gstno,:cdate,:finsyear,:fineyear,:bankid,:dlno,:branchname)";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $drug['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":address1", $drug['address1']);
			$stmt->bindParam(":address2", $drug['address2']);
			$stmt->bindParam(":statecode", $drug['statecode']);
			$stmt->bindParam(":branchid", $drug['branchid']);
			$stmt->bindParam(":contactno", $drug['contactno']);
			$stmt->bindParam(":email", $drug['email']);
			$stmt->bindParam(":faxno", $drug['faxno']);
			$stmt->bindParam(":countrycode", $drug['countrycode']);
			$stmt->bindParam(":tinno", $drug['tinno']);
			$stmt->bindParam(":gstno", $drug['gstno']);
			$stmt->bindParam(":cdate", $drug['cdate']);
			$stmt->bindParam(":finsyear", $drug['finsyear']);
			$stmt->bindParam(":fineyear", $drug['fineyear']);
			$stmt->bindParam(":bankid", $drug['bankid']);
			$stmt->bindParam(":dlno", $drug['dlno']);
			$stmt->bindParam(":branchname", $drug['branchname']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;
			//$data=array("item"=>$drug);
			$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->put('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			$drug = $request->getParsedBody();
			$sql = "update company set id=:id,name=:name,address1=:address1,address2=:address2,statecode=:statecode,branchid=:branchid,contactno=:contactno,email=:email,faxno=:faxno,countrycode=:countrycode,tinno=:tinno,gstno=:gstno,cdate=:cdate,finsyear=:finsyear,fineyear=:fineyear,bankid=:bankid,dlno=:dlno,branchname=:branchname where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);	
			$stmt->bindParam(":id", $drug['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":address1", $drug['address1']);
			$stmt->bindParam(":address2", $drug['address2']);
			$stmt->bindParam(":statecode", $drug['statecode']);
			$stmt->bindParam(":branchid", $drug['branchid']);
			$stmt->bindParam(":contactno", $drug['contactno']);
			$stmt->bindParam(":email", $drug['email']);
			$stmt->bindParam(":faxno", $drug['faxno']);
			$stmt->bindParam(":countrycode", $drug['countrycode']);
			$stmt->bindParam(":tinno", $drug['tinno']);
			$stmt->bindParam(":gstno", $drug['gstno']);
			$stmt->bindParam(":cdate", $drug['cdate']);
			$stmt->bindParam(":finsyear", $drug['finsyear']);
			$stmt->bindParam(":fineyear", $drug['fineyear']);
			$stmt->bindParam(":bankid", $drug['bankid']);
			$stmt->bindParam(":dlno", $drug['dlno']);
			$stmt->bindParam(":branchname", $drug['branchname']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;$data=null;
			$data = array("status"=>"Ok","msg"=>$msg,"item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage(),"item"=>$sql);$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
				
		$group->delete('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			//$company = json_decode($body);
			$sql = "delete from company where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $args['id']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="deleted successfully"; else $msg="no deletion";
			$db = null;$status=201;
			$data = array("status"=>"Ok","msg"=>$msg);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
    });
	//End of REST API for Company entity

	//Begin REST API for doctor entity
	$app->group("$burl/doctordet", function (Group $group) {
        $group->get('', function (Request $request, Response $response, $args) {
		try {
			$params = $request->getQueryParams();
			$filter='';
			foreach($params as $p=>$v) {
				if ($v)
					switch($p)
						{
						case 'id';
							$filter.="$p='$v'";break;	
						default;
							if ($filter) $filter.=" and ";
							$filter.="$p like '%$v%'";
							break;
						}
			}
			$db=getconn();
			$sql="select * from doctordet";
			if ($filter)
				$sql.=" where $filter";
			else $sql.=" limit 200";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$payload = json_encode($data);
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
        $group->get("/{id}", function (Request $request, Response $response, $args) {
		try {
			$db=getconn();
			$result=$db->query('select * from doctordet where id='.$args['id']);
			$data=$result->fetch(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->post('', function (Request $request, Response $response, $args) {
		try {
			//$body = $request->getBody();
			$drug = $request->getParsedBody();

			$sql = "INSERT INTO doctordet(id,name,contactno,emailid,regno) 
				VALUES(:id,:name,:contactno,:emailid,:regno)";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $drug['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":contactno", $drug['contactno']);
			$stmt->bindParam(":emailid", $drug['emailid']);
			$stmt->bindParam(":regno", $drug['regno']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;
			//$data=array("item"=>$drug);
			$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->put('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			$drug = $request->getParsedBody();
			$sql = "update doctordet set name=:name,contactno=:contactno,
					emailid=:emailid,regno=:regno where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);	
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $drug['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":contactno", $drug['contactno']);
			$stmt->bindParam(":emailid", $drug['emailid']);
			$stmt->bindParam(":regno", $drug['regno']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;$data=null;
			$data = array("status"=>"Ok","msg"=>$msg,"item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage(),"item"=>$sql);$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
				
		$group->delete('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			//$doctordet = json_decode($body);
			$sql = "delete from doctordet where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $args['id']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="deleted successfully"; else $msg="no deletion";
			$db = null;$status=201;
			$data = array("status"=>"Ok","msg"=>$msg);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
    });
	//End of REST API for doctor entity

	//Begin REST API for drug Ingredient entity
	$app->group("$burl/drugingr", function (Group $group) {
        $group->get('', function (Request $request, Response $response, $args) {
		try {
			$params = $request->getQueryParams();
			$filter='';
			foreach($params as $p=>$v) {
				if ($v)
					switch($p)
						{
						case 'id';
							$filter.="$p='$v'";break;	
						default;
							if ($filter) $filter.=" and ";
							$filter.="$p like '%$v%'";
							break;
						}
			}
			$db=getconn();
			$sql="select * from drugingr";
			if ($filter)
				$sql.=" where $filter";
			else $sql.=" limit 200";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$payload = json_encode($data);
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
        $group->get("/{id}", function (Request $request, Response $response, $args) {
		try {
			$db=getconn();
			$result=$db->query('select * from drugingr where id='.$args['id']);
			$data=$result->fetch(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->post('', function (Request $request, Response $response, $args) {
		try {
			//$body = $request->getBody();
			$drug = $request->getParsedBody();

			$sql = "INSERT INTO drugingr(id,name,ingredients) 
				VALUES(:id,:name,:ingredients)";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $drug['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":ingredients", $drug['ingredients']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;
			//$data=array("item"=>$drug);
			$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->put('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			$drug = $request->getParsedBody();
			$sql = "update drugingr set name=:name,ingredients=:ingredients where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);	
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $drug['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":ingredients", $drug['ingredients']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;$data=null;
			$data = array("status"=>"Ok","msg"=>$msg,"item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage(),"item"=>$sql);$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
				
		$group->delete('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			//$drugingr = json_decode($body);
			$sql = "delete from drugingr where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $args['id']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="deleted successfully"; else $msg="no deletion";
			$db = null;$status=201;
			$data = array("status"=>"Ok","msg"=>$msg);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
    });
	//End of REST API for drug Ingredient entity

	//Begin REST API for Patient Details entity
	$app->group("$burl/patientdet", function (Group $group) {
        $group->get('', function (Request $request, Response $response, $args) {
		try {
			$params = $request->getQueryParams();
			$filter='';
			foreach($params as $p=>$v) {
				if ($v)
					switch($p)
						{
						case 'id';
							$filter.="$p='$v'";break;	
						default;
							if ($filter) $filter.=" and ";
							$filter.="$p like '%$v%'";
							break;
						}
			}
			$db=getconn();
			$sql="select * from patientdet";
			if ($filter)
				$sql.=" where $filter";
			else $sql.=" limit 200";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$payload = json_encode($data);
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
        $group->get("/{id}", function (Request $request, Response $response, $args) {
		try {
			$db=getconn();
			$result=$db->query('select * from patientdet where id='.$args['id']);
			$data=$result->fetch(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->post('', function (Request $request, Response $response, $args) {
		try {
			//$body = $request->getBody();
			$drug = $request->getParsedBody();

			$sql = "INSERT INTO patientdet(id,name,contactno,emailid,address1,address2,city,state,country,pincode,ledgerid,spldiscabove,spldiscp) 
				VALUES(:id,:name,:contactno,:emailid,:address1,:address2,:city,:state,:country,:pincode,:ledgerid,:spldiscabove,:spldiscp)";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $drug['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":contactno", $drug['contactno']);
			$stmt->bindParam(":emailid", $drug['emailid']);
			$stmt->bindParam(":address1", $drug['address1']);
			$stmt->bindParam(":address2", $drug['address2']);
			$stmt->bindParam(":city", $drug['city']);
			$stmt->bindParam(":state", $drug['state']);
			$stmt->bindParam(":country", $drug['country']);
			$stmt->bindParam(":pincode", $drug['pincode']);
			$stmt->bindParam(":ledgerid", $drug['ledgerid']);
			$stmt->bindParam(":spldiscabove", $drug['spldiscabove']);
			$stmt->bindParam(":spldiscp", $drug['spldiscp']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;
			//$data=array("item"=>$drug);
			$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->put('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			$drug = $request->getParsedBody();
			$sql = "update patientdet set id=:id,name=:name,contactno=:contactno,emailid=:emailid,address1=:address1,address2=:address2,city=:city,state=:state,country=:country,pincode=:pincode,ledgerid=:ledgerid,spldiscabove=:spldiscabove,spldiscp=:spldiscp where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);	
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $drug['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":contactno", $drug['contactno']);
			$stmt->bindParam(":emailid", $drug['emailid']);
			$stmt->bindParam(":address1", $drug['address1']);
			$stmt->bindParam(":address2", $drug['address2']);
			$stmt->bindParam(":city", $drug['city']);
			$stmt->bindParam(":state", $drug['state']);
			$stmt->bindParam(":country", $drug['country']);
			$stmt->bindParam(":pincode", $drug['pincode']);
			$stmt->bindParam(":ledgerid", $drug['ledgerid']);
			$stmt->bindParam(":spldiscabove", $drug['spldiscabove']);
			$stmt->bindParam(":spldiscp", $drug['spldiscp']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;$data=null;
			$data = array("status"=>"Ok","msg"=>$msg,"item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage(),"item"=>$sql);$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
				
		$group->delete('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			//$patientdet = json_decode($body);
			$sql = "delete from patientdet where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $args['id']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="deleted successfully"; else $msg="no deletion";
			$db = null;$status=201;
			$data = array("status"=>"Ok","msg"=>$msg);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
    });
	//End of REST API for Patient Details entity

	//Begin REST API for Supplier entity
	$app->group("$burl/suppdet", function (Group $group) {
        $group->get('', function (Request $request, Response $response, $args) {
		try {
			$params = $request->getQueryParams();
			$filter='';
			foreach($params as $p=>$v) {
				if ($v)
					switch($p)
						{
						case 'id';
							$filter.="$p='$v'";break;	
						default;
							if ($filter) $filter.=" and ";
							$filter.="$p like '%$v%'";
							break;
						}
			}
			$db=getconn();
			$sql="select * from suppdet";
			if ($filter)
				$sql.=" where $filter";
			else $sql.=" limit 200";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$payload = json_encode($data);
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
        $group->get("/{id}", function (Request $request, Response $response, $args) {
		try {
			$db=getconn();
			$result=$db->query('select * from suppdet where id='.$args['id']);
			$data=$result->fetch(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->post('', function (Request $request, Response $response, $args) {
		try {
			//$body = $request->getBody();
			$suppdet = $request->getParsedBody();
			/*
		  `Code` bigint NOT NULL,
		  `Name` varchar(45) DEFAULT NULL,
		  `Category` varchar(45) DEFAULT NULL,
		  `Descn` varchar(50) DEFAULT NULL,
		  `Type` varchar(20) DEFAULT NULL,
		  `Packing` varchar(10) DEFAULT NULL,
		  `Unit` varchar(12) DEFAULT NULL,
		  `MfgComp` bigint DEFAULT NULL,
		  `Salt` int DEFAULT NULL,
		  `MinOrdQty` int DEFAULT NULL,
		  `PurchaseRate` float DEFAULT NULL,
		  `MRP` float DEFAULT NULL,
		  `RateB` float DEFAULT NULL,
		  `RateC` float DEFAULT NULL,
		  `Discount` float DEFAULT NULL,
		  `MinStock` int DEFAULT NULL,
		  `MaxStock` int DEFAULT NULL,
		  `Narcotic` char(1) DEFAULT NULL,
		  `ScheduleH` char(1) DEFAULT NULL,
		  `ScheduleH1` char(1) DEFAULT NULL,
		  `AvlQty` bigint DEFAULT NULL,
		  `Location` varchar(10) DEFAULT NULL,
		  `gsttaxcat` int DEFAULT NULL,
		  `gsthsncode` varchar(20) DEFAULT NULL,
		  `cgst` float DEFAULT NULL,
		  `sgst` float DEFAULT NULL,
		  `igst` float DEFAULT NULL,
		  `ingredientid` bigint DEFAULT NULL,
			  Code,Name,Category,Descn,Type,Packing,Unit,MfgComp,Salt,MinOrdQty,PurchaseRate,
  MRP,RateB,RateC,Discount,MinStock,MaxStock,Narcotic,ScheduleH,ScheduleH1,AvlQty,
  Location,gsttaxcat,gsthsncode,cgst,sgst,igst,ingredientid
			$sql = "INSERT INTO druginfo(Code,Name,Category,Descn,Type,Packing,Unit,MfgComp,Salt,
			MinOrdQty,PurchaseRate,MRP,RateB,RateC,Discount,MinStock,MaxStock,Narcotic,ScheduleH,
			ScheduleH1,AvlQty,Location,gsttaxcat,gsthsncode,cgst,sgst,igst,ingredientid) 
				VALUES(:code,:name,:category,:descn,:type,:packing,:unit,:mfgcomp,:salt,:minordqty,
				:purchaserate,:mrp,:rateb,ratec,:discount,:minstock,:maxstock,:narcotic,:scheduleh,
				:scheduleh1,:avlqty,:location,:gsttaxcat,:gsthsncode,:cgst,:sgst,:igst,:ingredientid
				)";
			*/
			$sql = "INSERT INTO suppdet(id,name,contactno,emailid,tinno,pan,address1,address2,city,state,country,pincode,dlno1,dlno2,GST,contactno2,contactno3,ledgerid) 
			VALUES(:id,:name,:contactno,:emailid,:tinno,:pan,:address1,:address2,:city,:state,:country,:pincode,:dlno1,:dlno2,:GST,:contactno2,:contactno3,:ledgerid)";
		$db = getconn();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":id", $suppdet['id']);
		$stmt->bindParam(":name", $suppdet['name']);
		$stmt->bindParam(":contactno", $suppdet['contactno']);
		$stmt->bindParam(":emailid", $suppdet['emailid']);
		$stmt->bindParam(":tinno", $suppdet['tinno']);
		$stmt->bindParam(":pan", $suppdet['pan']);
		$stmt->bindParam(":address1", $suppdet['address1']);
		$stmt->bindParam(":address2", $suppdet['address2']);
		$stmt->bindParam(":city", $suppdet['city']);
		$stmt->bindParam(":state", $suppdet['state']);
		$stmt->bindParam(":country", $suppdet['country']);
		$stmt->bindParam(":pincode", $suppdet['pincode']);
		$stmt->bindParam(":dlno1", $suppdet['dlno1']);
		$stmt->bindParam(":dlno2", $suppdet['dlno2']);
		$stmt->bindParam(":GST", $suppdet['GST']);
		$stmt->bindParam(":contactno2", $suppdet['contactno2']);
		$stmt->bindParam(":contactno3", $suppdet['contactno3']);
		$stmt->bindParam(":ledgerid", $suppdet['ledgerid']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;
			//$data=array("item"=>$drug);
			$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$suppdet);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->put('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			$suppdet = $request->getParsedBody();
			$sql = "update suppdet set name=:name,contactno=:contactno,emailid=:emailid,
					tinno=:tinno,pan=:pan,address1=:address1,address2=:address2,city=:city,
					state=:state,country=:country,pincode=:pincode,dlno1=:dlno1,dlno2=:dlno2,
					GST=:GST,contactno2=:contactno2,contactno3=:contactno3,ledgerid=:ledgerid
					where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);	
			$stmt->bindParam(":id", $suppdet['id']);
			$stmt->bindParam(":name", $suppdet['name']);
			$stmt->bindParam(":contactno", $suppdet['contactno']);
			$stmt->bindParam(":emailid", $suppdet['emailid']);
			$stmt->bindParam(":tinno", $suppdet['tinno']);
			$stmt->bindParam(":pan", $suppdet['pan']);
			$stmt->bindParam(":address1", $suppdet['address1']);
			$stmt->bindParam(":address2", $suppdet['address2']);
			$stmt->bindParam(":city", $suppdet['city']);
			$stmt->bindParam(":state", $suppdet['state']);
			$stmt->bindParam(":country", $suppdet['country']);
			$stmt->bindParam(":pincode", $suppdet['pincode']);
			$stmt->bindParam(":dlno1", $suppdet['dlno1']);
			$stmt->bindParam(":dlno2", $suppdet['dlno2']);
			$stmt->bindParam(":GST", $suppdet['GST']);
			$stmt->bindParam(":contactno2", $suppdet['contactno2']);
			$stmt->bindParam(":contactno3", $suppdet['contactno3']);
			$stmt->bindParam(":ledgerid", $suppdet['ledgerid']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;$data=null;
			$data = array("status"=>"Ok","msg"=>$msg,"item"=>$suppdet);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage(),"item"=>$sql);$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
				
		$group->delete('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			//$mfrcomp = json_decode($body);
			$sql = "delete from suppdet where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $args['id']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="deleted successfully"; else $msg="no deletion";
			$db = null;$status=201;
			$data = array("status"=>"Ok","msg"=>$msg);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status);
		});
	});
	//End of REST API for Supplier entity

	//Begin REST API for salt entity
	$app->group("$burl/salt", function (Group $group) {
        $group->get('', function (Request $request, Response $response, $args) {
		try {
			$params = $request->getQueryParams();
			$filter='';
			foreach($params as $p=>$v) {
				if ($v)
					switch($p)
						{
						case 'id';
							$filter.="$p='$v'";break;	
						default;
							if ($filter) $filter.=" and ";
							$filter.="$p like '%$v%'";
							break;
						}
			}
			$db=getconn();
			$sql="select * from salt";
			if ($filter)
				$sql.=" where $filter";
			else $sql.=" limit 200";
			$result=$db->query($sql);
			$data=$result->fetchAll(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$payload = json_encode($data);
			$response->getBody()->write($payload);
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
        $group->get("/{id}", function (Request $request, Response $response, $args) {
		try {
			$db=getconn();
			$result=$db->query('select * from salt where id='.$args['id']);
			$data=$result->fetch(PDO::FETCH_ASSOC);
			$status=200;
			} catch(Exception $e) {
				$data[]=array("errmsg"=>$e->getMessage());$status=400;
			}
			$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->post('', function (Request $request, Response $response, $args) {
		try {
			//$body = $request->getBody();
			$drug = $request->getParsedBody();
			/*
		  
			*/
			$sql = "INSERT INTO salt(id,name,dosage,sideeffects) 
				VALUES(:id,:name,:dosage,:sideeffects)";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $drug['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":dosage", $drug['dosage']);
			$stmt->bindParam(":sideeffects", $drug['sideeffects']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;
			//$data=array("item"=>$drug);
			$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
		
		$group->put('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			$drug = $request->getParsedBody();
			$sql = "update salt set name=:name,dosage=:dosage,sideeffects=:sideeffects where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);	
			$stmt->bindParam(":id", $args['id']);
			$stmt->bindParam(":name", $drug['name']);
			$stmt->bindParam(":dosage", $drug['dosage']);
			$stmt->bindParam(":sideeffects", $drug['sideeffects']);
			//$stmt->bindParam(":minordqty", $drug['minordqty']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			$db = null;$status=201;$data=null;
			$data = array("status"=>"Ok","msg"=>$msg,"item"=>$drug);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage(),"item"=>$sql);$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
				
		$group->delete('/{id}', function (Request $request, Response $response, $args) {
		try {
			$body = $request->getBody();
			//$salt = json_decode($body);
			$sql = "delete from salt where id=:id";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $args['id']);
			$stmt->execute();
			if ($stmt->rowCount()>0) $msg="deleted successfully"; else $msg="no deletion";
			$db = null;$status=201;
			$data = array("status"=>"Ok","msg"=>$msg);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status); 
		});
    });
	//End of REST API for salt entity

	//Begin REST API for transferHistoryView entity
	$app->group("$burl/transferHistView", function (Group $group) {

		$group->get("/{id}", function (Request $request, Response $response, $args) {
			try {
				$db = getconn();
				$result = $db->query('select di.Name, ts.qty, ts.batchno, ts.expdate,ts.mrp, di.Category from transferstk ts join druginfo di on ts.drugcode = di.Code where ts.transferid = ' . $args['id']);
				$data = $result->fetchAll(PDO::FETCH_ASSOC);
				$status = 200;
			} catch (Exception $e) {
				$data = array("errmsg" => $e->getMessage());
				$status = 400;
			}
			$response->getBody()->write(json_encode($data));
			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus($status);
		});
		
    });
	//End of REST API for transferHistoryView entity

	//Begin REST API for Sale Order Transfer entity
	$app->group("$burl/sotransfer", function (Group $group) {
		$group->post('', function (Request $request, Response $response, $args) {
			try {
				// Get the parameters from the request
				$so = $request->getParsedBody();
				$branch = $so['branch'];
				$fromdt = $so['fromdt'];
				$todt = $so['todt'];
	
				// Perform the select query using the branch ID, start date, and end date parameters
				$db = getconn();
				$stmt = $db->prepare('SELECT  billno,date,patientid,name FROM saleregbill srb JOIN patientdet pd ON srb.patientid=pd.id WHERE branchid = :branch AND date BETWEEN :fromdt AND :todt');
				$stmt->bindValue(':branch', $branch);
				$stmt->bindValue(':fromdt', $fromdt);
				$stmt->bindValue(':todt', $todt);
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
				// Create the response data
				$data = array(
					"status" => "Ok",
					"msg" => "Query executed successfully",
					"result" => $result
				);
	
				$status = 200;
			} catch(Exception $e) {
				$data = array(
					"status" => "Error",
					"msg" => $e->getMessage()
				);
	
				$status = 500;
			}
	
			$response->getBody()->write(json_encode($data));
			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus($status);
		});
	});	
	//End of REST API for Sale Order Transfer entity

	//Begin REST API for Sale order Transfer View entity
	$app->group("$burl/sotransferView", function (Group $group) {

		$group->get("/{id}", function (Request $request, Response $response, $args) {
			try {
				$db = getconn();
				$result = $db->query('select di.Name,srd.qty,srd.batchno,srd.price,srd.expdate,srd.cgstp,srd.amount from saleregprod srd join druginfo di on srd.prodcode = di.Code where srd.billno = ' . $args['id']);
				$data = $result->fetchAll(PDO::FETCH_ASSOC);
				$status = 200;
			} catch (Exception $e) {
				$data = array("errmsg" => $e->getMessage());
				$status = 400;
			}
			$response->getBody()->write(json_encode($data));
			return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus($status);
		});
		
    });
	//End of REST API for Sale order Transfer View entity

	// Begin REST API for Purchase Import Entity
	$app->post("$burl/purimport", function (Request $request, Response $response) {
		try {
			//$body = $request->getBody();
			$inv = $request->getParsedBody();
			
			
			$sql = "INSERT INTO purreggrn(invoiceno,invoicedate,invoiceamt,suppid,discount,netamt) 
			VALUES(:invoiceno,:invoicedate,:invoiceamt,:suppid,:discount,0)";
			$db = getconn();
			// $db->beginTransaction();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":invoiceno", $inv['invoiceno']);
			$stmt->bindParam(":invoicedate", $inv['invoicedate']);
			$stmt->bindParam(":invoiceamt", $inv['invoiceamt']);
			$stmt->bindParam(":suppid", $inv['suppid']);
			$stmt->bindParam(":discount", $inv['discount']);
			$stmt->execute();

			// Get the last inserted ID for the GRN
			$grnno = $db->lastInsertId();

			$sql = "INSERT INTO purregprod(grnno,prodcode,qty,free,batchno,purrate,mrp,expdate,cgstp,cgstamt,packing,discount,total) 
					VALUES(:grnno,:prodcode,:qty,:free,:batchno,:purrate,:mrp,:expdate,:cgstp,0,:packing,:discount,:total)";
			$db = getconn();
			
			$stmt = $db->prepare($sql);
			
			foreach($inv['items'] as $item) {
				$stmt->bindParam(":prodcode", $item['prodcode']);
				$stmt->bindParam(":qty", $item['qty']);
				$stmt->bindParam(":free", $item['free']);
				$stmt->bindParam(":batchno", $item['batchno']);
				$stmt->bindParam(":purrate", $item['purrate']);
				$stmt->bindParam(":mrp", $item['mrp']);
				$stmt->bindParam(":expdate", $item['expdate']);
				$stmt->bindParam(":cgstp", $item['cgstp']);
				$stmt->bindParam(":packing", $item['packing']);
				$stmt->bindParam(":discount", $item['discount']);
				$stmt->bindParam(":total", $item['total']);
				$stmt->bindParam(":grnno", $grnno);
				$stmt->execute();
			}
				
			$db->exec("UPDATE purreggrn SET netamt='".$inv['invoiceamt']."' where grnno='$grnno'");
			// $db->commit();
			// if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
			// $db = null;$status=201;
			
			$status=200;
			$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$inv);
		} catch(Exception $e) {
			$data=array("status"=>"Error","msg"=>$e->getMessage());$status=200;
		}
		$response->getBody()->write(json_encode($data));
			return $response
					->withHeader('Content-Type', 'application/json')
					->withStatus($status);
	});
	// End REST API for Purchase Import Entity
};
