<?php


//Knowledgebase
class kbdb
{
   function __construct($config) {
       $this->config=$config;
   }
   
	public function search($q){
		$conn = mysqli_connect($this->config['host'], $this->config['dbuser'],$this->config['dbpass']);
		mysqli_select_db($this->config['database'],$conn);
		$db_result = mysqli_query($q,$conn);
		while ($row=@mysqli_fetch_assoc($db_result)) {
				$rows[]=$row;
		}
		return $rows;
	}
}

//AD Connector
class actived
{
	function __construct($config) {
       $this->config=$config;
   	}
	
	public function login($user,$pass){
		if ($ds=ldap_connect($this->config['bindhost'],$this->config['bindport'])){
			ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
			if($this->config['sasl']) {
				if ($r = ldap_sasl_bind($ds, NULL, $pass, 'DIGEST-MD5', NULL, $user)) {
					$ret = $this->search($user, $ds);
				} else {
					$ret['error'] = "Bad username or password";
				}
			}else{
				if ($r = ldap_bind($ds, $user, $pass)) {
					$ret = $this->search($user, $ds);
				} else {
					$ret['error'] = "Bad username or password";
				}
			}
			ldap_close($ds);
    	}else{
			$ret['error']="Unable to connect to LDAP server";
    	}
		return $ret;
	}
	
	public function search($user,$ds){
		$filter=$this->config['usernameattribute']."=$user";
		$srch=@ldap_search($ds,$this->config['baseou'] ,$filter);
		$info = @ldap_get_entries($ds,$srch);
		if(count($info)){
			foreach($this->config['attributes'] as $attrib){
				$userinfo[$attrib['name']]=$info[0][$attrib['ldapname']][0];

			}
			return $userinfo;
		}else{
			$ret['error']="Unable to locate user $filter";
			return $ret;
		}
	}
}







