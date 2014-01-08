<?php
/**
 * @files public/action.php
 */

error_reporting(E_ALL & E_NOTICE);
ini_set('display_errors',1);

session_start();

include "../application/config.php";
include "../application/db_connect.php";
include "../application/function.php";

$strMethod = (isset($_REQUEST['method'])) ? $_REQUEST['method'] : null;

switch($strMethod) {
	case 'member_add':
		if ((isset($_SESSION['cloudnsru'])) & (isset($_SESSION['cloudnsru']['member_id']))) {
			echo json_encode(array('status' => 11));
			exit();
		}

		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : null;
		$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;
		if ($password == null) {
			$password = password_generate(7, 4);
		}
		$password_md5 = md5($password);

		// Check username
		if ($username == null) {
			exit(json_encode(array('status' => 13)));
		}

		// Check exists username
		$result = pg_query($link, "SELECT COUNT(*) FROM users WHERE username = '{$username}' AND admin = 'no';");
		if (!$result) {
			exit(json_encode(array('status' => 55)));
		}
		$row = pg_fetch_row($result);

		if ($row[0] > 0) {
			exit(json_encode(array('status' => 12)));
		}

		// All right
		$res = pg_query($link, "INSERT INTO users (username,password,admin) VALUES ('{$username}', '{$password_md5}', 'no')  RETURNING id;");
		$row = pg_fetch_row($res);

		if ($row[0] > 0) {
			exit(json_encode(array('status' => 0, 'results' => $row[0])));
		}
	break;
	case 'member_auth':
	case 'member_login':
		if ((isset($_SESSION['cloudnsru'])) & (isset($_SESSION['cloudnsru']['member_id']))) {
			exit(json_encode(array('status'=>11)));
		}

		$username = (isset($_REQUEST['username'])) ? $_REQUEST['username'] : null;
		$password = (isset($_REQUEST['password'])) ? $_REQUEST['password'] : null;
		$password_md5 = md5($password);

		$result = pg_query($link, "SELECT COUNT(*) FROM users WHERE username = '{$username}' AND password = '{$password_md5}' AND admin = 'no';");
		if (!$result) {
			exit(json_encode(array('status' => 56)));
		}
		$row = pg_fetch_row($result);

		if ($row[0] == 0) {
			exit(json_encode(array('status' => 10)));
		}

		$row = pg_fetch_row(pg_query($link, "SELECT id FROM users WHERE username = '{$username}' AND password = '{$password_md5}' AND admin = 'no';"));

		$_SESSION['cloudnsru']['member_id'] = $row[0];
		$_SESSION['cloudnsru']['username'] = $username;

		exit(json_encode(array('status'=>0)));
	break;
	case 'member_logout':
		$_SESSION['cloudnsru']['member_id'] = null;
		$_SESSION['cloudnsru']['username'] = null;

		unset($_SESSION['cloudnsru']['member_id']);
		unset($_SESSION['cloudnsru']['username']);
		unset($_SESSION['cloudnsru']);

		exit(json_encode(array('status'=>0)));
	break;
	case 'member_get':
		if ((!isset($_SESSION['cloudnsru'])) & (!isset($_SESSION['cloudnsru']['member_id']))) {
			exit(json_encode(array('status'=>10)));
		}
	break;
	case 'member_update':
		if ((!isset($_SESSION['cloudnsru'])) & (!isset($_SESSION['cloudnsru']['member_id']))) {
			exit(json_encode(array('status'=>10)));
		}
	break;
	case 'member_detele':
		if ((!isset($_SESSION['cloudnsru'])) & (!isset($_SESSION['cloudnsru']['member_id']))) {
			exit(json_encode(array('status'=>10)));
		}

		//delete from users where id = 7;
	break;

	case 'zone_add':
		if ((!isset($_SESSION['cloudnsru'])) & (!isset($_SESSION['cloudnsru']['member_id']))) {
			exit(json_encode(array('status'=>10)));
		}

		$name = (isset($_REQUEST['name'])) ? $_REQUEST['name'] : null;
		$owner = $_SESSION['cloudnsru']['member_id'];

		// check exists zone
		$res = pg_query($link, "SELECT COUNT(*) FROM zones WHERE name = '{$name}' AND owner = '{$owner}'");
		$row = pg_fetch_row($res);

		if ($row[0] > 0) {
			exit(json_encode(array('status'=>20)));
		}

		$pri_dns = "ns1.cloudns.ru";
		$sec_dns = "ns2.cloudns.ru";

		$serial = date("ymdhi");

		$res = pg_query($link, "INSERT INTO zones (name,pri_dns,sec_dns,serial,owner) VALUES ('{$name}','{$pri_dns}','{$sec_dns}','{$serial}','{$owner}') RETURNING id");
		$row = pg_fetch_row($res);

		if ($row[0] > 0) {
			exit(json_encode(array('errno'=>0,'results'=>$row[0])));
		}
	break;
	case 'zone_get':
		if ((!isset($_SESSION['cloudnsru'])) & (!isset($_SESSION['cloudnsru']['member_id']))) {
			exit(json_encode(array('errno'=>10)));
		}

		$owner = $_SESSION['cloudnsru']['member_id'];

		$res = pg_query($link, "SELECT * FROM zones WHERE owner = '{$owner}'");
		$row['total'] = pg_num_rows($res);
		$row['errno'] = 0;
		$i = 0;
		while($rows = pg_fetch_row($res)){
			$row['results'][$i]['id'] = $rows[0];
			$row['results'][$i]['name'] = $rows[1];
			$i++;
		};

		exit(json_encode($row));
	break;
	case 'zone_delete':
		if ((!isset($_SESSION['cloudnsru'])) & (!isset($_SESSION['cloudnsru']['member_id']))) {
			exit(json_encode(array('status'=>10)));
		}

		$owner = $_SESSION['cloudnsru']['member_id'];
		$zone = (isset($_REQUEST['zone'])) ? $_REQUEST['zone'] : null;

		// Get zone ID
		$res = pg_query($link, "SELECT id FROM zones WHERE name = '{$zone}' AND owner = '{$owner}'");
		$num = pg_num_rows($res);
		
		if ($num == 0) {
			exit(json_encode(array('status'=>21)));
		}
		
		$row = pg_fetch_row($res);
		$zone_id = $row[0];

		pg_query("DELETE FROM zones WHERE id = '{$zone_id}'");
		pg_query("DELETE FROM records WHERE zone '{$zone_id}')");
		
		exit(json_encode(array('status'=>0)));
	break;

	case 'record_a_add':
		if ((!isset($_SESSION['cloudnsru'])) & (!isset($_SESSION['cloudnsru']['member_id']))) {
			exit(json_encode(array('status'=>10)));
		}

		$owner = $_SESSION['cloudnsru']['member_id'];
		$zone = (isset($_REQUEST['zone'])) ? $_REQUEST['zone'] : null;
		$host = (isset($_REQUEST['host'])) ? $_REQUEST['host'] : null;
		$type = "A";
		$destination = (isset($_REQUEST['destination'])) ? $_REQUEST['destination'] : null;

		// Get zone ID
		$res = pg_query($link, "SELECT id FROM zones WHERE name = '{$zone}' AND owner = '{$owner}'");
		$num = pg_num_rows($res);
		
		if ($num == 0) {
			exit(json_encode(array('status' => 21)));
		}

		$row = pg_fetch_row($res);
		$zone_id = $row[0];

		if ($zone == null) {
			exit(json_encode(array('status' => 51)));
		}

		if ($host == null) {
			exit(json_encode(array('status' => 50)));
		}

		if ($destination == null) {
			exit(json_encode(array('status' => 52)));
		}

		$res = pg_query($link, "INSERT INTO records (zone,host,type,destination) VALUES ('{$zone_id}','{$host}','{$type}','{$destination}') RETURNING id");
		$row = pg_fetch_row($res);

		if ($row[0] > 0) {
			exit(json_encode(array('status' => 0, 'results' => $row[0])));
		}
	break;

	case 'record_add':
		if ((!isset($_SESSION['cloudnsru'])) & (!isset($_SESSION['cloudnsru']['member_id']))) {
			exit(json_encode(array('status' => 10)));
		}

		$owner = $_SESSION['cloudnsru']['member_id'];
		$zone = (isset($_REQUEST['zone'])) ? $_REQUEST['zone'] : null;
		$host = (isset($_REQUEST['host'])) ? $_REQUEST['host'] : null;
		$type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : null;
		$pri = (isset($_REQUEST['pri'])) ? $_REQUEST['pri'] : null;
		$destination = (isset($_REQUEST['destination'])) ? $_REQUEST['destination'] : null;

		if ($host == null) {
			exit(json_encode(array('status' => 50)));
		}

		if ($zone == null) {
			exit(json_encode(array('status' => 51)));
		}

		if ($type == null) {
			exit(json_encode(array('status' => 53)));
		}

		if ($destination == null) {
			exit(json_encode(array('status' => 52)));
		}

		switch ($type) {
			case 'A':
				$res = pg_query($link, "INSERT INTO records (zone,host,type,destination) VALUES ('{$zone}','{$host}','{$type}','{$destination}') RETURNING id");
			break;
			case 'NS':
				$res = pg_query($link, "INSERT INTO records (zone,host,type,destination) VALUES ('{$zone}','{$host}','{$type}','{$destination}') RETURNING id");
			break;
			case 'CNAME':
				$res = pg_query($link, "INSERT INTO records (zone,host,type,destination) VALUES ('{$zone}','{$host}','{$type}','{$destination}') RETURNING id");
			break;
			case 'PTR':
				$res = pg_query($link, "INSERT INTO records (zone,host,type,destination) VALUES ('{$zone}','{$host}','{$type}','{$destination}') RETURNING id");
			break;
			case 'MX':
				$res = pg_query($link, "INSERT INTO records (zone,host,type,pri,destination) VALUES ('{$zone}','{$host}','{$type}','{$pri}','{$destination}') RETURNING id");
			break;
			case 'AAAA':
				$res = pg_query($link, "INSERT INTO records (zone,host,type,destination) VALUES ('{$zone}','{$host}','{$type}','{$destination}') RETURNING id");
			break;
			case 'TXT':
				$res = pg_query($link, "INSERT INTO records (zone,host,type,destination) VALUES ('{$zone}','{$host}','{$type}','{$destination}') RETURNING id");
			break;
			case 'SRV':
				$res = pg_query($link, "INSERT INTO records (zone,host,type,destination) VALUES ('{$zone}','{$host}','{$type}','{$destination}') RETURNING id");
			break;
		};

		$row = pg_fetch_row($res);

		if ($row[0] > 0) {
			exit(json_encode(array('status' => 0, 'results' => $row[0])));
		}
	break;
	case 'record_get':
		if ((!isset($_SESSION['cloudnsru'])) & (!isset($_SESSION['cloudnsru']['member_id']))) {
			exit(json_encode(array('status'=>10)));
		}

		$owner = $_SESSION['cloudnsru']['member_id'];
		$zone = (isset($_REQUEST['zone'])) ? $_REQUEST['zone'] : null;

		// Get zone ID
		$res = pg_query($link, "SELECT id FROM zones WHERE name = '{$zone}' AND owner = '{$owner}'");
		$num = pg_num_rows($res);
		
		if ($num == 0) {
			exit(json_encode(array('status'=>21)));
		}
		
		$row = pg_fetch_row($res);
		$zone_id = $row[0];

		$res = pg_query($link, "SELECT * FROM records WHERE zone = '{$zone_id}'");

		$result['total'] = pg_num_rows($res);
		$result['status'] = 0;
		$i = 0;
		while($rows = pg_fetch_row($res)){
			$result['results'][$i]['id'] = $rows[0];
			$result['results'][$i]['host'] = $rows[2];
			$result['results'][$i]['type'] = $rows[3];
			$result['results'][$i]['priority'] = $rows[4];
			$result['results'][$i]['destination'] = $rows[5];
			$i++;
		};

		exit(json_encode($result));
	break;
	case 'record_update':
		if ((!isset($_SESSION['cloudnsru'])) & (!isset($_SESSION['cloudnsru']['member_id']))) {
			exit(json_encode(array('status' => 10)));
		}
	break;
	case 'record_delete':
		if ((!isset($_SESSION['cloudnsru'])) & (!isset($_SESSION['cloudnsru']['member_id']))) {
			exit(json_encode(array('status'=>10)));
		}

		$owner = $_SESSION['cloudnsru']['member_id'];
		$zone = (isset($_REQUEST['zone'])) ? $_REQUEST['zone'] : null;
		$record = (isset($_REQUEST['record'])) ? $_REQUEST['record'] : null;

		// Get zone ID
		$res = pg_query($link, "SELECT id FROM zones WHERE name = '{$zone}' AND owner = '{$owner}'");
		$num = pg_num_rows($res);
		
		if ($num == 0) {
			exit(json_encode(array('status'=>21)));
		}
		
		$row = pg_fetch_row($res);
		$zone_id = $row[0];

		// TODO: check exists record
		pg_query($link, "DELETE FROM records WHERE id = '{$record}' AND zone = '{$zone_id}'");

		exit(json_encode(array('status' => 0)));
	break;
	default:
		exit(json_encode(array('status' => 54)));
	break;
}
