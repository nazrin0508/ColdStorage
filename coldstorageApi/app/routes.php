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
	$burl='/ColdStorage/coldstorageApi'; //Define base directory
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

//Begin REST API for village entity
$app->group("$burl/village", function (Group $group) {
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
		$sql="select * from village";
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
			$db = getconn();
			$stmt = $db->prepare('SELECT * FROM village WHERE id = :id');
			$stmt->bindValue(':id', $args['id'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_ASSOC);
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
	
	$group->post('', function (Request $request, Response $response, $args) {
	try {
		//$body = $request->getBody();
		$users = $request->getParsedBody();

		$sql = "INSERT INTO village(villageName, post, anchal, district) 
		VALUES(:txt_village_name, :txt_post, :txt_anchal, :txt_district)";
		$db = getconn();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":txt_village_name", $users['village_name']);
		$stmt->bindParam(":txt_post", $users['post']);
		$stmt->bindParam(":txt_anchal", $users['anchal']);
		$stmt->bindParam(":txt_district", $users['district']);
		$stmt->execute();

		if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
		$db = null;$status=201;

		//$data=array("item"=>$users);
		$data = array("status"=>"Ok","msg"=>"Inserted sucessfully","item"=>$users);
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
		$db = getconn();
		$users = $request->getParsedBody();
		$sql = "UPDATE village SET villageName=:village_name, post=:post, anchal=:anchal, district=:district WHERE id=:id";	
		$stmt = $db->prepare($sql);	
		$stmt->bindParam(":id", $users['village_id']);

		$stmt->bindParam(":village_name", $users['village_name']);
		$stmt->bindParam(":post", $users['post']);
		$stmt->bindParam(":anchal", $users['anchal']);
		$stmt->bindParam(":district", $users['district']);
		$stmt->execute();
		if ($stmt->rowCount()>0) $msg="success"; else $msg="no update";
		$db = null;$status=201;$data=null;
		$data = array("status"=>"Ok","msg"=>$msg,"item"=>$users);
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
			$sql = "DELETE FROM village WHERE id = :id";
			$db = getconn();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $args['id']);
			$stmt->execute();
	
			$rowCount = $stmt->rowCount();
			$msg = ($rowCount > 0) ? "Deleted successfully" : "No deletion";
			
			$db = null;
			$status = 201;
			$data = array("status" => "Ok", "msg" => $msg);
		} catch (Exception $e) {
			$data = array("status" => "Error", "msg" => $e->getMessage());
			$status = 200;
		}
	
		$response->getBody()->write(json_encode($data));
		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus($status);
	});
	
});
//End of REST API for village entity

	

	
};
