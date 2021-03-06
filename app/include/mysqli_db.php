<?php
	/*
		This class contains all the database related functions which is required to run a query
	*/
	class dbfunctions
	{
		var $dbconnection;
		var $ressel;
		
		/*
			This functions is a constructor of the class and will be executed on the object creation of this class.
			Arguments: -
		*/
		
		function dbfunctions()
		{
			$this->dbconnection = mysqli_connect(DATABASE_SERVER,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
			if(!$this->dbconnection){
				die("Error in establishing connection: ".mysqli_error());
				} else {
				// $dblink = mysql_select_db(,$dbconnection);
			}
		}
		
		/*
			This functions is used for the select query.
			@$tablename = Table name
			@fieldlist = Field list
			@where = Where clause
			@orderby = Order by clause
			@groupby = Group by clause
			@limit = Limit for the query
			@echoquery = Print query for debugging.
		*/
		
		function SelectQuery($tablename, $fieldlist, $where = '', $orderby = '', $groupby = '', $limit='', $echoquery = 0)
		{
			$qrysel = "SELECT $fieldlist FROM $tablename ".($where!=""?" WHERE $where":"")." $groupby $orderby $limit";
			if($echoquery==1){ echo $qrysel; }
			$this->ressel = mysqli_query($this->dbconnection,$qrysel);
		}
		
		function Query($query)
		{
			
			$this->ressel = mysqli_query($this->dbconnection,$query);
			
		}
		
		function SimpleSelectQuery($selqry)
		{
			$qrysel = $selqry;
			$this->ressel = mysqli_query($this->dbconnection,$qrysel);
		}
		
		/*
			This functions is used for get the number of rows on the current query.
		*/
		function getNumRows()
		{
			$totalnumberofrows = mysqli_num_rows($this->ressel);
			return $totalnumberofrows;
		}
		
		/*
			This functions is used for get the number of rows on the current query.
		*/
		function getFetchArray()
		{
			$returnarray = mysqli_fetch_array($this->ressel);
			return $returnarray;
		}
		
		/*
			This functions is used for get the number of rows on the current query.
		*/
		function getAffectedRows()
		{
			$affectedrows = mysqli_affected_rows($this->ressel);
			return $affectedrows;
		}
		
		/*
			This functions is used for get the number of rows on the current query.
			@tablename = Table name
			@fieldsarray = Fields array with key and values - key = fieldname - value = value
		*/
		function InsertQuery($tablename, $fieldsarray)
		{
			$qryins = "Insert into $tablename ";
			
			$allkeys = "(";
			$allvalues = "(";
			
			foreach($fieldsarray as $key => $value)
			{
				$allkeys .= $key.",";
				$allvalues .= "'".addslashes($value)."',";
			}
			$allkeys = substr($allkeys,0,-1).")";
			$allvalues = substr($allvalues,0,-1).")";
			
			$qryins .= $allkeys." VALUES ".$allvalues;
			mysqli_query($this->dbconnection,$qryins) or die(mysqli_error());
		}
		
		/*
			This functions is used for get the last inserted id from the insert query.
		*/
		function getLastInsertedId()
		{
			$lastinsertid = mysqli_insert_id();
			return $lastinsertid;
		}
		
		/*
			This functions is used for get the number of rows on the current query.
			@tablename = Table name
			@fieldsarray = Fields array with key and values - key = fieldname - value = value
		*/
		function UpdateQuery($tablename, $fieldsarray, $where = '')
		{
			$qryupd = "Update $tablename set ";
			
			foreach($fieldsarray as $key => $value)
			{
				$qryupd .= $key."='".mysqli_real_escape_string($value)."',";
			}
			
			$qryupd = substr($qryupd,0,-1)." ".($where!=""?"Where $where":"");
			mysqli_query($this->dbconnection,$qryupd) or die(mysqli_error());
		}
		
		function db_safe()
		{
			$arguments =  func_get_args();
			$returnquery = $arguments[0];
			for($i = 1; $i < count($arguments); $i++){
				$returnquery = str_replace("%".$i,mysqli_real_escape_string($arguments[$i]),$returnquery);
			}
			return $returnquery;
		}
		
		function __destruct() {
			@mysqli_close();    
		}
		function DeleteQuery($tablename,$where='') 
		{
			$this->sql	= "DELETE FROM  ".$tablename;
			
			if($where != '')
			{
				$this->sql .=" WHERE ".$where." ";
			}
			//echo $this->sql;echo"<br>";exit;
			$this->result	= mysqli_query($this->dbconnection,$this->sql);
			return $this->result;
		}
		
	}
?>