<?php

class MySQL_class {
	public $dbName, $user, $password, $host, $id, $rows, $a_rows, $result;
	
	function __construct($user, $password, $host, $dbName){
		$this->user = $user;
		$this->password = $password;
		$this->host = $host;
		$this->dbName = $dbName;
	}
	
	function Connect(){
		$this->id = mysqli_connect($this->host, $this->user, $this->password,$this->dbName) or
		die ("<error>error_connect: ".mysqli_error( $this->host)."</error>");
	}
	
	function SelectDB () {
		mysqli_select_db($this->id, $this->dbName) or
            die("<error>error_select_db: ".mysqli_error($this->id)."</error>");
    }

	function Query ($query) {
        $this->result = @mysqli_query($this->id,$query) or
            die("<error>error_query (Query): ".mysqli_error($this->id)."</error>");

        $this->rows = @mysqli_num_rows($this->result);
        $this->a_rows = @mysqli_affected_rows($this->id);

        return $this->result;
    }
	
	function QueryRow ($query) {
        $this->result = @mysqli_query($this->id,$query) or
            die("<error>error_query (QueryRow): ".mysqli_error($this->id)."</error>");

        //mysqli_num_rows(result); Returns the number of rows in the result-set
		$this->rows = @mysqli_num_rows($this->result);
        //mysqli_affected_rows(connection); Returns number of affected rows in the query-result
		$this->a_rows = @mysqli_affected_rows($this->id);
		//mysqli_fetch_array(result,resulttype); Returns an array of strings that corresponds to the fetched row. NULL if there are no more rows in result-set
        $this->data = @mysqli_fetch_array($this->result);

    	   return($this->data);
    }
	
	function QueryItem ($query) {
        $this->result = @mysqli_query($this->id,$query) or
            die("<error>error_query (QueryItem): ".mysqli_error($this->id)."</error>");

        $this->rows = @mysqli_num_rows($this->result);
        $this->a_rows = @mysqli_affected_rows($this->id);
        $this->data = @mysqli_fetch_array($this->result);

    	   return($this->data[0]);
    }
	
	function Fetch ($row) {
        //mysqli_data_seek(result,offset); The mysqli_data_seek() function adjusts the result pointer to a specific row in the result-set.
		@mysqli_data_seek($this->result, $row) or
            die("<error>error_data_seek: ".mysqli_error($this->id)."</error>");

        $this->data = @mysqli_fetch_array($this->result);
    }
	
	function Insert ($query) {
        $this->result = @mysqli_query($this->id,$query) or
            die("<error>error_query (Insert): ".mysqli_error($this->id)."</error>");

        $this->a_rows = @mysqli_affected_rows($this->id);
    }
	
	function Update ($query) {
        $this->result = @mysqli_query($this->id,$query) or
            die("<error>error_query (Update): ".mysqli_error($this->id)."</error>");

        $this->a_rows = @mysqli_affected_rows($this->id);
    }
	
	function Delete ($query) {
        $this->result = @mysqli_query($this->id,$query) or
            die("<error>error_query (Delete): ".mysqli_error($this->id)."</error>");

        $this->a_rows = @mysqli_affected_rows($this->id);
    }

    function InsertID () {
        //mysqli_insert_id(connection);  returns the id (generated with AUTO_INCREMENT) used in the last query
		return mysqli_insert_id($this->id);
    }
}
?>