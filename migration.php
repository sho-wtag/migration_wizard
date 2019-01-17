<?php
// Parse ini file
$ini_array = parse_ini_file("migrate.ini", true);

// Check input data
if (isset($_POST['name'])) {
	$name = $_POST['name'];
} else {
	echo "Cannot be accessed direct. Exit running.";
	exit;
}

ini_set('max_execution_time', 0); // to get unlimited php script execution time

session_start();
$_SESSION['total'] = 1;
$_SESSION['progress'] = 0;
session_write_close();

// Open source database
$link_src = mysqli_connect($ini_array['source']['hostname'], $ini_array['source']['username'], $ini_array['source']['password'], $ini_array['source']['database']) or die(mysqli_connect_error());

// Open destination database
$link_dst = mysqli_connect($ini_array['destination']['hostname'], $ini_array['destination']['username'], $ini_array['destination']['password'], $ini_array['destination']['database']) or die(mysqli_connect_error());

// General class to generate SQL statement
class QueryBuilder {
	private $tbname = "";
	private $sql = "";
	private $fields = "";
	private $values = "";

	function __construct() {

	}

	public function getSql() {
		$this->sql = "INSERT INTO " . $this->tbname . "(" . $this->fields . ") VALUES (" . $this->values . ")";
		return $this->sql;
	}

	public function setTbname($tbname) {
		$this->tbname = $tbname;
	}

	public function addField($field, $value) {
		if (strlen($this->fields) > 0) {
			$this->fields = $this->fields . "," . $field;
			$this->values = $this->values . "," . $value;
		} else {
			$this->fields = $field;
			$this->values = $value;
		}
	}
}

// vtiger_crmentity
if ($name == "crmentity") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_crmentity`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_crmentity
				ORDER BY crmid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_crmentity");

			$crmid = $row[0];
			if (isset($crmid) && strlen($crmid) > 0)
				$qb->addField("crmid", $crmid);

			$smcreatorid = $row[1];
			if (isset($smcreatorid) && strlen($smcreatorid) > 0)
				$qb->addField("smcreatorid", $smcreatorid);

			$smownerid = $row[2];
			if (isset($smownerid) && strlen($smownerid) > 0)
				$qb->addField("smownerid", $smownerid);

			$modifiedby = $row[3];
			if (isset($modifiedby) && strlen($modifiedby) > 0)
				$qb->addField("modifiedby", $modifiedby);

			$setype = $row[4];
			if (isset($setype) && strlen($setype) > 0)
				$qb->addField("setype", "'" . $setype . "'");

			$description = str_replace('\'', '\'\'', $row[5]);
			if (isset($description) && strlen($description) > 0)
				$qb->addField("description", "'" . $description . "'");

			$createdtime = $row[6];
			if (isset($createdtime) && strlen($createdtime) > 0)
				$qb->addField("createdtime", "'" . $createdtime . "'");

			$modifiedtime = $row[7];
			if (isset($modifiedtime) && strlen($modifiedtime) > 0)
				$qb->addField("modifiedtime", "'" . $modifiedtime . "'");

			$viewedtime = $row[8];
			if (isset($viewedtime) && strlen($viewedtime) > 0)
				$qb->addField("viewedtime", "'" . $viewedtime . "'");

			$status = $row[9];
			if (isset($status) && strlen($status) > 0)
				$qb->addField("status", "'" . $status . "'");

			$version = $row[10];
			if (isset($version) && strlen($version) > 0)
				$qb->addField("version", $version);

			$presence = $row[11];
			if (isset($presence) && strlen($presence) > 0)
				$qb->addField("presence", $presence);

			$deleted = $row[12];
			if (isset($deleted) && strlen($deleted) > 0)
				$qb->addField("deleted", $deleted);

			$sql_dst = $qb->getSql();
			var_dump($sql_dst);
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_account
if ($name == "account") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_account`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_account
				ORDER BY accountid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_account");

			$accountid = $row[0];
			if (isset($accountid) && strlen($accountid) > 0)
				$qb->addField("accountid", $accountid);

			$accountname = $row[1];
			if (isset($accountname) && strlen($accountname) > 0)
				$qb->addField("accountname", "'" . $accountname . "'");

			$parentid = $row[2];
			if (isset($parentid) && strlen($parentid) > 0)
				$qb->addField("parentid", $parentid);

			$account_type = $row[3];
			if (isset($account_type) && strlen($account_type) > 0)
				$qb->addField("account_type", "'" . $account_type . "'");

			$industry = $row[4];
			if (isset($industry) && strlen($industry) > 0)
				$qb->addField("industry", "'" . $industry . "'");

			$annualrevenue = $row[5];
			if (isset($annualrevenue) && strlen($annualrevenue) > 0)
				$qb->addField("annualrevenue", $annualrevenue);

			$rating = $row[6];
			if (isset($rating) && strlen($rating) > 0)
				$qb->addField("rating", "'" . $rating . "'");

			$ownership = $row[7];
			if (isset($ownership) && strlen($ownership) > 0)
				$qb->addField("ownership", "'" . $ownership . "'");

			$siccode = $row[8];
			if (isset($siccode) && strlen($siccode) > 0)
				$qb->addField("siccode", "'" . $siccode . "'");

			$tickersymbol = $row[9];
			if (isset($tickersymbol) && strlen($tickersymbol) > 0)
				$qb->addField("tickersymbol", "'" . $tickersymbol . "'");

			$phone = $row[10];
			if (isset($phone) && strlen($phone) > 0)
				$qb->addField("phone", "'" . $phone . "'");

			$otherphone = $row[11];
			if (isset($otherphone) && strlen($otherphone) > 0)
				$qb->addField("otherphone", "'" . $otherphone . "'");

			$email1 = $row[12];
			if (isset($email1) && strlen($email1) > 0)
				$qb->addField("email1", "'" . $email1 . "'");

			$email2 = $row[13];
			if (isset($email2) && strlen($email2) > 0)
				$qb->addField("email2", "'" . $email2 . "'");

			$website = $row[14];
			if (isset($website) && strlen($website) > 0)
				$qb->addField("website", "'" . $website . "'");

			$fax = $row[15];
			if (isset($fax) && strlen($fax) > 0)
				$qb->addField("fax", "'" . $fax . "'");

			$employees = $row[16];
			if (isset($employees) && strlen($employees) > 0)
				$qb->addField("employees", $employees);

			$emailoptout = $row[17];
			if (isset($emailoptout) && strlen($emailoptout) > 0)
				$qb->addField("emailoptout", "'" . $emailoptout . "'");

			$notify_owner = $row[18];
			if (isset($notify_owner) && strlen($notify_owner) > 0)
				$qb->addField("notify_owner", "'" . $notify_owner . "'");

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_accountbillads
if ($name == "accountbillads") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_accountbillads`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_accountbillads
				ORDER BY accountaddressid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_accountbillads");

			$accountaddressid = $row[0];
			if (isset($accountaddressid) && strlen($accountaddressid) > 0)
				$qb->addField("accountaddressid", $accountaddressid);

			$bill_city = $row[1];
			if (isset($bill_city) && strlen($bill_city) > 0)
				$qb->addField("bill_city", "'" . $bill_city . "'");

			$bill_code = $row[2];
			if (isset($bill_code) && strlen($bill_code) > 0)
				$qb->addField("bill_code", "'" . $bill_code . "'");

			$bill_country = $row[3];
			if (isset($bill_country) && strlen($bill_country) > 0)
				$qb->addField("bill_country", "'" . $bill_country . "'");

			$bill_state = $row[4];
			if (isset($bill_state) && strlen($bill_state) > 0)
				$qb->addField("bill_state", "'" . $bill_state . "'");

			$bill_street = $row[5];
			if (isset($bill_street) && strlen($bill_street) > 0)
				$qb->addField("bill_street", "'" . $bill_street . "'");

			$bill_pobox = $row[6];
			if (isset($bill_pobox) && strlen($bill_pobox) > 0)
				$qb->addField("bill_pobox", "'" . $bill_pobox . "'");



			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_accountscf
if ($name == "accountscf") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_accountscf`") or die(mysqli_error($link_dst));
	$result_dst = $link_dst->query("SHOW COLUMNS FROM `vtiger_accountscf`") or die(mysqli_error($link_dst));
	$columns = array();
	while ($row = $result_dst->fetch_row()) {
		if ($row[0] != 'accountid') {
			$link_dst->query("ALTER TABLE vtiger_accountscf DROP COLUMN `" . $row[0] . "`") or die(mysqli_error($link_dst));
		}
	}
	$result_src = $link_src->query("SHOW COLUMNS FROM `vtiger_accountscf`") or die(mysqli_error($link_src));
	while ($row = $result_src->fetch_row()) {
		if ($row[0] != 'accountid') {
			$buffer = "ALTER TABLE vtiger_accountscf ADD `" . $row[0] . "` " . $row[1];
			if ($row[2] == 'YES')
				$buffer .= " NULL";
			else
				$buffer .= " NOT NULL";
			if (isset($row[4]))
				$buffer .= (" DEFAULT " . $row[4]);
			else
				$buffer .= " DEFAULT NULL";
			$link_dst->query($buffer) or die(mysqli_error($link_dst));
			array_push($columns, $row);
		}
	}

	$sql_src = "SELECT *
				FROM vtiger_accountscf
				ORDER BY accountid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_accountscf");

			$accountid = $row[0];
			if (isset($accountid) && strlen($accountid) > 0)
				$qb->addField("accountid", $accountid);

			$index = 0;
			foreach ($columns as $col) {
				$coltype = strtoupper($col[1]);
				$index++;
				$value = $row[$index];
				if (isset($value) && strlen($value) > 0) {
					if (substr($coltype, 0, 4) == "CHAR" ||
						substr($coltype, 0, 7) == "VARCHAR" ||
						substr($coltype, 0, 8) == "TINYTEXT" ||
						substr($coltype, 0, 4) == "TEXT" ||
						substr($coltype, 0, 10) == "MEDIUMTEXT" ||
						substr($coltype, 0, 8) == "LONGTEXT" ||
						substr($coltype, 0, 4) == "DATE" ||
						substr($coltype, 0, 4) == "TIME" ||
						substr($coltype, 0, 8) == "DATETIME" ||
						substr($coltype, 0, 9) == "TIMESTAMP" ||
						substr($coltype, 0, 7) == "DECIMAL")
						$qb->addField($col[0], "'" . $value . "'");
					else
						$qb->addField($col[0], $value);
				}
			}

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_accountshipads
if ($name == "accountshipads") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_accountshipads`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_accountshipads
				ORDER BY accountaddressid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_accountshipads");

			$accountaddressid = $row[0];
			if (isset($accountaddressid) && strlen($accountaddressid) > 0)
				$qb->addField("accountaddressid", $accountaddressid);

			$ship_city = $row[1];
			if (isset($ship_city) && strlen($ship_city) > 0)
				$qb->addField("ship_city", "'" . $ship_city . "'");

			$ship_code = $row[2];
			if (isset($ship_code) && strlen($ship_code) > 0)
				$qb->addField("ship_code", "'" . $ship_code . "'");

			$ship_country = $row[3];
			if (isset($ship_country) && strlen($ship_country) > 0)
				$qb->addField("ship_country", "'" . $ship_country . "'");

			$ship_state = $row[4];
			if (isset($ship_state) && strlen($ship_state) > 0)
				$qb->addField("ship_state", "'" . $ship_state . "'");

			$ship_pobox = $row[5];
			if (isset($ship_pobox) && strlen($ship_pobox) > 0)
				$qb->addField("ship_pobox", "'" . $ship_pobox . "'");

			$ship_street = $row[6];
			if (isset($ship_street) && strlen($ship_street) > 0)
				$qb->addField("ship_street", "'" . $ship_street . "'");

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_accounttype
if ($name == "accounttype") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_accounttype`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_accounttype
				ORDER BY accounttypeid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_accounttype");

			$accounttypeid = $row[0];
			if (isset($accounttypeid) && strlen($accounttypeid) > 0)
				$qb->addField("accounttypeid", $accounttypeid);

			$accounttype = $row[1];
			if (isset($accounttype) && strlen($accounttype) > 0)
				$qb->addField("accounttype", "'" . $accounttype . "'");

			$presence = $row[2];
			if (isset($presence) && strlen($presence) > 0)
				$qb->addField("presence", $presence);

			$picklist_valueid = $row[3];
			if (isset($picklist_valueid) && strlen($picklist_valueid) > 0)
				$qb->addField("picklist_valueid", $picklist_valueid);

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_contactaddress
if ($name == "contactaddress") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_accountshipads`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_contactaddress
				ORDER BY contactaddressid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_contactaddress");

			$contactaddressid = $row[0];
			if (isset($contactaddressid) && strlen($contactaddressid) > 0)
				$qb->addField("contactaddressid", $contactaddressid);

			$mailingcity = $row[1];
			if (isset($mailingcity) && strlen($mailingcity) > 0)
				$qb->addField("mailingcity", "'" . $mailingcity . "'");

			$mailingstreet = $row[2];
			if (isset($mailingstreet) && strlen($mailingstreet) > 0)
				$qb->addField("mailingstreet", "'" . $mailingstreet . "'");

			$mailingcountry = $row[3];
			if (isset($mailingcountry) && strlen($mailingcountry) > 0)
				$qb->addField("mailingcountry", "'" . $mailingcountry . "'");

			$othercountry = $row[4];
			if (isset($othercountry) && strlen($othercountry) > 0)
				$qb->addField("othercountry", "'" . $othercountry . "'");

			$mailingstate = $row[5];
			if (isset($mailingstate) && strlen($mailingstate) > 0)
				$qb->addField("mailingstate", "'" . $mailingstate . "'");

			$mailingpobox = $row[6];
			if (isset($mailingpobox) && strlen($mailingpobox) > 0)
				$qb->addField("mailingpobox", "'" . $mailingpobox . "'");

			$othercity = $row[7];
			if (isset($othercity) && strlen($othercity) > 0)
				$qb->addField("othercity", "'" . $othercity . "'");

			$otherstate = $row[8];
			if (isset($otherstate) && strlen($otherstate) > 0)
				$qb->addField("otherstate", "'" . $otherstate . "'");

			$mailingzip = $row[9];
			if (isset($mailingzip) && strlen($mailingzip) > 0)
				$qb->addField("mailingzip", "'" . $mailingzip . "'");

			$otherzip = $row[10];
			if (isset($otherzip) && strlen($otherzip) > 0)
				$qb->addField("otherzip", "'" . $otherzip . "'");

			$otherstreet = $row[11];
			if (isset($otherstreet) && strlen($otherstreet) > 0)
				$qb->addField("otherstreet", "'" . $otherstreet . "'");

			$otherpobox = $row[12];
			if (isset($otherpobox) && strlen($otherpobox) > 0)
				$qb->addField("otherpobox", "'" . $otherpobox . "'");

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_contactdetails
if ($name == "contactdetails") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_contactdetails`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_contactdetails
				ORDER BY contactid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_contactdetails");

			$contactid = $row[0];
			if (isset($contactid) && strlen($contactid) > 0)
				$qb->addField("contactid", $contactid);

			$accountid = $row[1];
			if (isset($accountid) && strlen($accountid) > 0)
				$qb->addField("accountid", $accountid);

			$salutation = $row[2];
			if (isset($salutation) && strlen($salutation) > 0)
				$qb->addField("salutation", "'" . $salutation . "'");

			$firstname = $row[3];
			if (isset($firstname) && strlen($firstname) > 0)
				$qb->addField("firstname", "'" . $firstname . "'");

			$lastname = $row[4];
			if (isset($lastname) && strlen($lastname) > 0)
				$qb->addField("lastname", "'" . $lastname . "'");

			$email = $row[5];
			if (isset($email) && strlen($email) > 0)
				$qb->addField("email", "'" . $email . "'");

			$phone = $row[6];
			if (isset($phone) && strlen($phone) > 0)
				$qb->addField("phone", "'" . $phone . "'");

			$mobile = $row[7];
			if (isset($mobile) && strlen($mobile) > 0)
				$qb->addField("mobile", "'" . $mobile . "'");

			$title = $row[8];
			if (isset($title) && strlen($title) > 0)
				$qb->addField("title", "'" . $title . "'");

			$department = $row[9];
			if (isset($department) && strlen($department) > 0)
				$qb->addField("department", "'" . $department . "'");

			$fax = $row[10];
			if (isset($fax) && strlen($fax) > 0)
				$qb->addField("fax", "'" . $fax . "'");

			$reportsto = $row[11];
			if (isset($reportsto) && strlen($reportsto) > 0)
				$qb->addField("reportsto", "'" . $reportsto . "'");

			$training = $row[12];
			if (isset($training) && strlen($training) > 0)
				$qb->addField("training", "'" . $training . "'");

			$usertype = $row[13];
			if (isset($usertype) && strlen($usertype) > 0)
				$qb->addField("usertype", "'" . $usertype . "'");

			$contacttype = $row[14];
			if (isset($contacttype) && strlen($contacttype) > 0)
				$qb->addField("contacttype", "'" . $contacttype . "'");

			$otheremail = $row[15];
			if (isset($otheremail) && strlen($otheremail) > 0)
				$qb->addField("otheremail", "'" . $otheremail . "'");

			$yahooid = $row[16];
			if (isset($yahooid) && strlen($yahooid) > 0)
				$qb->addField("yahooid", "'" . $yahooid . "'");

			$donotcall = $row[17];
			if (isset($donotcall) && strlen($donotcall) > 0)
				$qb->addField("donotcall", "'" . $donotcall . "'");

			$emailoptout = $row[18];
			if (isset($emailoptout) && strlen($emailoptout) > 0)
				$qb->addField("emailoptout", "'" . $emailoptout . "'");

			$imagename = $row[19];
			if (isset($imagename) && strlen($imagename) > 0)
				$qb->addField("imagename", "'" . $imagename . "'");

			$reference = $row[20];
			if (isset($reference) && strlen($reference) > 0)
				$qb->addField("reference", "'" . $reference . "'");

			$notify_owner = $row[21];
			if (isset($notify_owner) && strlen($notify_owner) > 0)
				$qb->addField("notify_owner", "'" . $notify_owner . "'");

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_contactscf
if ($name == "contactscf") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_contactscf`") or die(mysqli_error($link_dst));
	$result_dst = $link_dst->query("SHOW COLUMNS FROM `vtiger_contactscf`") or die(mysqli_error($link_dst));
	$columns = array();
	while ($row = $result_dst->fetch_row()) {
		if ($row[0] != 'contactid') {
			$link_dst->query("ALTER TABLE vtiger_contactscf DROP COLUMN `" . $row[0] . "`") or die(mysqli_error($link_dst));
		}
	}
	$result_src = $link_src->query("SHOW COLUMNS FROM `vtiger_contactscf`") or die(mysqli_error($link_src));
	while ($row = $result_src->fetch_row()) {
		if ($row[0] != 'contactid') {
			$buffer = "ALTER TABLE vtiger_contactscf ADD `" . $row[0] . "` " . $row[1];
			if ($row[2] == 'YES')
				$buffer .= " NULL";
			else
				$buffer .= " NOT NULL";
			if (isset($row[4]))
				$buffer .= (" DEFAULT " . $row[4]);
			else
				$buffer .= " DEFAULT NULL";
			$link_dst->query($buffer) or die(mysqli_error($link_dst)) or die(mysqli_error($link_dst));
			array_push($columns, $row);
		}
	}

	$sql_src = "SELECT *
				FROM vtiger_contactscf
				ORDER BY contactid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_contactscf");

			$accountid = $row[0];
			if (isset($accountid) && strlen($accountid) > 0)
				$qb->addField("contactid", $accountid);

			$index = 0;
			foreach ($columns as $col) {
				$coltype = strtoupper($col[1]);
				$index++;
				$value = $row[$index];
				if (isset($value) && strlen($value) > 0) {
					if (substr($coltype, 0, 4) == "CHAR" ||
						substr($coltype, 0, 7) == "VARCHAR" ||
						substr($coltype, 0, 8) == "TINYTEXT" ||
						substr($coltype, 0, 4) == "TEXT" ||
						substr($coltype, 0, 10) == "MEDIUMTEXT" ||
						substr($coltype, 0, 8) == "LONGTEXT" ||
						substr($coltype, 0, 4) == "DATE" ||
						substr($coltype, 0, 4) == "TIME" ||
						substr($coltype, 0, 8) == "DATETIME" ||
						substr($coltype, 0, 9) == "TIMESTAMP" ||
						substr($coltype, 0, 7) == "DECIMAL")
						$qb->addField($col[0], "'" . $value . "'");
					else
						$qb->addField($col[0], $value);
				}
			}

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_contactsubdetails
if ($name == "contactsubdetails") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_contactsubdetails`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_contactsubdetails
				ORDER BY contactsubscriptionid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_contactsubdetails");

			$contactsubscriptionid = $row[0];
			if (isset($contactsubscriptionid) && strlen($contactsubscriptionid) > 0)
				$qb->addField("contactsubscriptionid", $contactsubscriptionid);

			$homephone = $row[1];
			if (isset($homephone) && strlen($homephone) > 0)
				$qb->addField("homephone", "'" . $homephone . "'");

			$otherphone = $row[2];
			if (isset($otherphone) && strlen($otherphone) > 0)
				$qb->addField("otherphone", "'" . $otherphone . "'");

			$assistant = $row[3];
			if (isset($assistant) && strlen($assistant) > 0)
				$qb->addField("assistant", "'" . $assistant . "'");

			$assistantphone = $row[4];
			if (isset($assistantphone) && strlen($assistantphone) > 0)
				$qb->addField("assistantphone", "'" . $assistantphone . "'");

			$birthday = $row[5];
			if (isset($birthday) && strlen($birthday) > 0)
				$qb->addField("birthday", "'" . $birthday . "'");

			$laststayintouchrequest = $row[6];
			if (isset($laststayintouchrequest) && strlen($laststayintouchrequest) > 0)
				$qb->addField("laststayintouchrequest", $laststayintouchrequest);

			$laststayintouchsavedate = $row[7];
			if (isset($laststayintouchsavedate) && strlen($laststayintouchsavedate) > 0)
				$qb->addField("laststayintouchsavedate", $laststayintouchsavedate);

			$leadsource = $row[8];
			if (isset($leadsource) && strlen($leadsource) > 0)
				$qb->addField("leadsource", "'" . $leadsource . "'");

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_productcategory
if ($name == "productcategory") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_productcategory`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_productcategory
				ORDER BY productcategoryid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_productcategory");

			$productcategoryid = $row[0];
			if (isset($productcategoryid) && strlen($productcategoryid) > 0)
				$qb->addField("productcategoryid", $productcategoryid);

			$productcategory = $row[1];
			if (isset($productcategory) && strlen($productcategory) > 0)
				$qb->addField("productcategory", "'" . $productcategory . "'");

			$presence = $row[2];
			if (isset($presence) && strlen($presence) > 0)
				$qb->addField("presence", $presence);

			$picklist_valueid = $row[3];
			if (isset($picklist_valueid) && strlen($picklist_valueid) > 0)
				$qb->addField("picklist_valueid", $picklist_valueid);

			$qb->addField("sortorderid", $row_index + 1);


			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_productcf
if ($name == "productcf") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_productcf`") or die(mysqli_error($link_dst));
	$result_dst = $link_dst->query("SHOW COLUMNS FROM `vtiger_productcf`") or die(mysqli_error($link_dst));
	$columns = array();
	while ($row = $result_dst->fetch_row()) {
		if ($row[0] != 'productid') {
			$link_dst->query("ALTER TABLE vtiger_productcf DROP COLUMN `" . $row[0] . "`") or die(mysqli_error($link_dst));
		}
	}
	$result_src = $link_src->query("SHOW COLUMNS FROM `vtiger_productcf`") or die(mysqli_error($link_src));
	while ($row = $result_src->fetch_row()) {
		if ($row[0] != 'productid') {
			$buffer = "ALTER TABLE vtiger_productcf ADD `" . $row[0] . "` " . $row[1];
			if ($row[2] == 'YES')
				$buffer .= " NULL";
			else
				$buffer .= " NOT NULL";
			if (isset($row[4]))
				$buffer .= (" DEFAULT " . $row[4]);
			else
				$buffer .= " DEFAULT NULL";
			$link_dst->query($buffer) or die(mysqli_error($link_dst));
			array_push($columns, $row);
		}
	}

	$sql_src = "SELECT *
				FROM vtiger_productcf
				ORDER BY productid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_productcf");

			$accountid = $row[0];
			if (isset($accountid) && strlen($accountid) > 0)
				$qb->addField("productid", $accountid);

			$index = 0;
			foreach ($columns as $col) {
				$coltype = strtoupper($col[1]);
				$index++;
				$value = $row[$index];
				if (isset($value) && strlen($value) > 0) {
					if (substr($coltype, 0, 4) == "CHAR" ||
						substr($coltype, 0, 7) == "VARCHAR" ||
						substr($coltype, 0, 8) == "TINYTEXT" ||
						substr($coltype, 0, 4) == "TEXT" ||
						substr($coltype, 0, 10) == "MEDIUMTEXT" ||
						substr($coltype, 0, 8) == "LONGTEXT" ||
						substr($coltype, 0, 4) == "DATE" ||
						substr($coltype, 0, 4) == "TIME" ||
						substr($coltype, 0, 8) == "DATETIME" ||
						substr($coltype, 0, 9) == "TIMESTAMP" ||
						substr($coltype, 0, 7) == "DECIMAL")
						$qb->addField($col[0], "'" . $value . "'");
					else
						$qb->addField($col[0], $value);
				}
			}

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_products
if ($name == "products") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_products`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_products
				ORDER BY productid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_products");

			$productid = $row[0];
			if (isset($productid) && strlen($productid) > 0)
				$qb->addField("productid", $productid);

			$productname = $row[1];
			if (isset($productname) && strlen($productname) > 0)
				$qb->addField("productname", "'" . $productname . "'");

			$productcode = $row[2];
			if (isset($productcode) && strlen($productcode) > 0)
				$qb->addField("productcode", "'" . $productcode . "'");

			$productcategory = $row[3];
			if (isset($productcategory) && strlen($productcategory) > 0)
				$qb->addField("productcategory", "'" . $productcategory . "'");

			$manufacturer = $row[4];
			if (isset($manufacturer) && strlen($manufacturer) > 0)
				$qb->addField("manufacturer", "'" . $manufacturer . "'");

			$qty_per_unit = $row[6];
			if (isset($qty_per_unit) && strlen($qty_per_unit) > 0)
				$qb->addField("qty_per_unit", "'" . $qty_per_unit . "'");

			$unit_price = $row[7];
			if (isset($unit_price) && strlen($unit_price) > 0)
				$qb->addField("unit_price", "'" . $unit_price . "'");

			$weight = $row[8];
			if (isset($weight) && strlen($weight) > 0)
				$qb->addField("weight", "'" . $weight . "'");

			$pack_size = $row[9];
			if (isset($pack_size) && strlen($pack_size) > 0)
				$qb->addField("pack_size", $pack_size);

			$sales_start_date = $row[10];
			if (isset($sales_start_date) && strlen($sales_start_date) > 0)
				$qb->addField("sales_start_date", "'" . $sales_start_date . "'");

			$sales_end_date = $row[11];
			if (isset($sales_end_date) && strlen($sales_end_date) > 0)
				$qb->addField("sales_end_date", "'" . $sales_end_date . "'");

			$start_date = $row[12];
			if (isset($start_date) && strlen($start_date) > 0)
				$qb->addField("start_date", "'" . $start_date . "'");

			$expiry_date = $row[13];
			if (isset($expiry_date) && strlen($expiry_date) > 0)
				$qb->addField("expiry_date", "'" . $expiry_date . "'");

			$cost_factor = $row[14];
			if (isset($cost_factor) && strlen($cost_factor) > 0)
				$qb->addField("cost_factor", $cost_factor);

			$commissionrate = $row[15];
			if (isset($commissionrate) && strlen($commissionrate) > 0)
				$qb->addField("commissionrate", "'" . $commissionrate . "'");

			$commissionmethod = $row[16];
			if (isset($commissionmethod) && strlen($commissionmethod) > 0)
				$qb->addField("commissionmethod", "'" . $commissionmethod . "'");

			$discontinued = $row[17];
			if (isset($discontinued) && strlen($discontinued) > 0)
				$qb->addField("discontinued", $discontinued);

			$usageunit = $row[18];
			if (isset($usageunit) && strlen($usageunit) > 0)
				$qb->addField("usageunit", "'" . $usageunit . "'");

			$reorderlevel = $row[21];
			if (isset($reorderlevel) && strlen($reorderlevel) > 0)
				$qb->addField("reorderlevel", $reorderlevel);

			$website = $row[22];
			if (isset($website) && strlen($website) > 0)
				$qb->addField("website", "'" . $website . "'");

			$taxclass = $row[23];
			if (isset($taxclass) && strlen($taxclass) > 0)
				$qb->addField("taxclass", "'" . $taxclass . "'");

			$mfr_part_no = $row[24];
			if (isset($mfr_part_no) && strlen($mfr_part_no) > 0)
				$qb->addField("mfr_part_no", "'" . $mfr_part_no . "'");

			$vendor_part_no = $row[25];
			if (isset($vendor_part_no) && strlen($vendor_part_no) > 0)
				$qb->addField("vendor_part_no", "'" . $vendor_part_no . "'");

			$serialno = $row[26];
			if (isset($serialno) && strlen($serialno) > 0)
				$qb->addField("serialno", "'" . $serialno . "'");

			$qtyinstock = $row[27];
			if (isset($qtyinstock) && strlen($qtyinstock) > 0)
				$qb->addField("qtyinstock", $qtyinstock);

			$productsheet = $row[28];
			if (isset($productsheet) && strlen($productsheet) > 0)
				$qb->addField("productsheet", "'" . $productsheet . "'");

			$qtyindemand = $row[29];
			if (isset($qtyindemand) && strlen($qtyindemand) > 0)
				$qb->addField("qtyindemand", $qtyindemand);

			$glacct = $row[30];
			if (isset($glacct) && strlen($glacct) > 0)
				$qb->addField("glacct", "'" . $glacct . "'");

			$vendor_id = $row[31];
			if (isset($vendor_id) && strlen($vendor_id) > 0)
				$qb->addField("vendor_id", $vendor_id);

			$imagename = $row[32];
			if (isset($imagename) && strlen($imagename) > 0)
				$qb->addField("imagename", "'" . $imagename . "'");

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_notes
if ($name == "notes") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_notes`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_notes
				ORDER BY notesid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_notes");

			$notesid = $row[0];
			if (isset($notesid) && strlen($notesid) > 0)
				$qb->addField("notesid", $notesid);

			$title = $row[2];
			if (isset($title) && strlen($title) > 0)
				$qb->addField("title", "'" . $title . "'");

			$filename = $row[3];
			if (isset($filename) && strlen($filename) > 0)
				$qb->addField("filename", "'" . $filename . "'");

			$notecontent = $row[4];
			if (isset($notecontent) && strlen($notecontent) > 0)
				$qb->addField("notecontent", "'" . $notecontent . "'");

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_user2role
if ($name == "user2role") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_user2role`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_user2role
				ORDER BY userid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_user2role");

			$userid = $row[0];
			if (isset($userid) && strlen($userid) > 0)
				$qb->addField("userid", $userid);

			$roleid = $row[1];
			if (isset($roleid) && strlen($roleid) > 0)
				$qb->addField("roleid", "'" . $roleid . "'");

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_users
if ($name == "users") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));

	$columns = array();
	$result_src = $link_src->query("SHOW COLUMNS FROM `vtiger_users`") or die(mysqli_error($link_src));
	while ($row = $result_src->fetch_row()) {
		array_push($columns, $row);
	}

	$sql_src = "SELECT *
				FROM vtiger_users
				ORDER BY id";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	$sql_dst = "DELETE
				FROM vtiger_users
				WHERE user_name != 'admin'";
	$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			if ($row[1] == "admin")
				continue;
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_users");

			foreach ($columns as $key => $col) {
				if ($col[0] == "defhomeview" || $col[0] == "tagcloud_view" || $col[0] == "homeorder")
					continue;
				$fieldvalue = $row[$key];
				if (isset($fieldvalue) && strlen($fieldvalue) > 0) {
					$coltype = strtoupper($col[1]);
					if (substr($coltype, 0, 4) == "CHAR" ||
						substr($coltype, 0, 7) == "VARCHAR" ||
						substr($coltype, 0, 8) == "TINYTEXT" ||
						substr($coltype, 0, 4) == "TEXT" ||
						substr($coltype, 0, 10) == "MEDIUMTEXT" ||
						substr($coltype, 0, 8) == "LONGTEXT" ||
						substr($coltype, 0, 4) == "DATE" ||
						substr($coltype, 0, 4) == "TIME" ||
						substr($coltype, 0, 8) == "DATETIME" ||
						substr($coltype, 0, 9) == "TIMESTAMP" ||
						substr($coltype, 0, 7) == "DECIMAL")
						$qb->addField($col[0], "'" . $fieldvalue . "'");
					else
						$qb->addField($col[0], $fieldvalue);
				}
			}

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_cvadvfilter
if ($name == "cvadvfilter") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_cvadvfilter`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_cvadvfilter
				ORDER BY cvid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_cvadvfilter");

			$cvid = $row[0];
			if (isset($cvid) && strlen($cvid) > 0)
				$qb->addField("cvid", $cvid);

			$columnindex = $row[1];
			if (isset($columnindex) && strlen($columnindex) > 0)
				$qb->addField("columnindex", $columnindex);

			$columnname = $row[2];
			if (isset($columnname) && strlen($columnname) > 0)
				$qb->addField("columnname", "'" . $columnname . "'");

			$comparator = $row[3];
			if (isset($comparator) && strlen($comparator) > 0)
				$qb->addField("comparator", "'" . $comparator . "'");

			$value = $row[4];
			if (isset($value) && strlen($value) > 0)
				$qb->addField("value", "'" . $value . "'");

			$qb->addField("groupid", "1");

			$qb->addField("column_condition", "'and'");

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_cvcolumnlist
if ($name == "cvcolumnlist") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_cvcolumnlist`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_cvcolumnlist
				ORDER BY cvid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_cvcolumnlist");

			$cvid = $row[0];
			if (isset($cvid) && strlen($cvid) > 0)
				$qb->addField("cvid", $cvid);

			$columnindex = $row[1];
			if (isset($columnindex) && strlen($columnindex) > 0)
				$qb->addField("columnindex", $columnindex);

			$columnname = $row[2];
			if (isset($columnname) && strlen($columnname) > 0)
				$qb->addField("columnname", "'" . $columnname . "'");

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_cvstdfilter
if ($name == "cvstdfilter") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_cvstdfilter`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_cvstdfilter
				ORDER BY cvid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_cvstdfilter");

			$cvid = $row[0];
			if (isset($cvid) && strlen($cvid) > 0)
				$qb->addField("cvid", $cvid);

			$columnname = $row[1];
			if (isset($columnname) && strlen($columnname) > 0)
				$qb->addField("columnname", "'" . $columnname . "'");

			$stdfilter = $row[1];
			if (isset($stdfilter) && strlen($stdfilter) > 0)
				$qb->addField("stdfilter", "'" . $stdfilter . "'");

			$startdate = $row[1];
			if (isset($startdate) && strlen($startdate) > 0)
				$qb->addField("startdate", "'" . $startdate . "'");

			$enddate = $row[1];
			if (isset($enddate) && strlen($enddate) > 0)
				$qb->addField("enddate", "'" . $enddate . "'");

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// vtiger_customview
if ($name == "customview") {
	$link_dst->query("SET foreign_key_checks = 0") or die(mysqli_error($link_dst));
	$link_dst->query("TRUNCATE `vtiger_customview`") or die(mysqli_error($link_dst));

	$sql_src = "SELECT *
				FROM vtiger_customview
				ORDER BY cvid";
	$result_src = $link_src->query($sql_src) or die(mysqli_error($link_src));
	if ($result_src->num_rows > 0) {
		$rows_total = $result_src->num_rows;
		$row_index = 0;
		while ($row = $result_src->fetch_row()) {
			$qb = new QueryBuilder();
			$qb->setTbname("vtiger_customview");

			$cvid = $row[0];
			if (isset($cvid) && strlen($cvid) > 0)
				$qb->addField("cvid", $cvid);

			$viewname = $row[1];
			if (isset($viewname) && strlen($viewname) > 0)
				$qb->addField("viewname", "'" . $viewname . "'");

			$setdefault = $row[2];
			if (isset($setdefault) && strlen($setdefault) > 0)
				$qb->addField("setdefault", $setdefault);

			$setmetrics = $row[3];
			if (isset($setmetrics) && strlen($setmetrics) > 0)
				$qb->addField("setmetrics", $setmetrics);

			$entitytype = $row[4];
			if (isset($entitytype) && strlen($entitytype) > 0)
				$qb->addField("entitytype", "'" . $entitytype . "'");

			$sql_dst = $qb->getSql();
			$result_dst = $link_dst->query($sql_dst) or die(mysqli_error($link_dst));
			$row_index++;

			session_start();
			$_SESSION['total'] = $rows_total;
			$_SESSION['progress'] = $row_index;
			session_write_close();
		}

		$result_src->close();
	}
	$link_dst->query("SET foreign_key_checks = 1") or die(mysqli_error($link_dst));
}

// Close connections
echo "Migration completed.";
mysqli_close($link_src);
mysqli_close($link_dst);
?>