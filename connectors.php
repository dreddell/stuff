<?php


//Knowledgebase
class kbdb
{
   function __construct($config) {
       $this->config=$config;
   }
   
	public function search($q){
		$conn = mysql_connect($this->config['host'], $this->config['dbuser'],$this->config['dbpass']);
		mysql_select_db($this->config['database'],$conn);
		$db_result = mysql_query($q,$conn);
		while ($row=@mysql_fetch_assoc($db_result)) {
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
		if ($ds=ldap_connect($this->config['bindhost'])){
			ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
             if ($r=@ldap_bind($ds,$user,$pass)){;
				$ret=$this->search($user,$ds);
    		}else{
    			$ret="Bad username or password";
    		}
    	}else{
    		$ret="Unable to connect to LDAP server";
    	}
		return $ret;
	}
	
	public function search($user,$ds){
		$filter="samaccountname=$user";
		$srch=@ldap_search($ds,$this->config['baseou'] ,$filter);
		$info = @ldap_get_entries($ds,$srch);
		if(count($info)){
			$userinfo['username']=$info[0][$this->config['usernameattribute']][0];
			$userinfo['fname']=$info[0]['givenname'][0];
			$userinfo['lname']=$info[0]['sn'][0];
			$userinfo['email']=$info[0][$this->config['emailattribute']][0];
			return $userinfo;
		}else{
			return "Unable to locate user";
		}
	}
	
	
	
}




