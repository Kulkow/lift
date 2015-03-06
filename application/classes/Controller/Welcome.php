<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller_Layout {

	public function before()
	{
	   parent::before();
	}
       
    public function action_index()
	{
	   $this->template->content = View::factory('auth/registr/success');
	}
    
    public function action_ms()
	{
        
//        exit();
        $host="85.234.37.68:51433";
        $user="ReadBD";
        $pwd="ReadBonus@34";
        $db_name = "Bonus";
        
        $w = "H-ADM-RDP\SQLEXPRESS";
       
        $link = mssql_connect($host,$user,$pwd);
        if (!$link) {
            die('Something went wrong while connecting to MSSQL');
        }
        echo 'CONECT';
        
        mssql_select_db($db_name,$link);
        $insert = "INSERT INTO BD_bonus (DEBKRED,ID,DOC,DATE,Active,keys,sumBonus,Filial,operation) VALUES (1,12,'4554', '15.10.2013', 1 , 45566, 50, 'A', 2)"; 
        $select = 'Select * FROM BD_bonus';
        
        $select = "Select * FROM BD_bonus WHERE DATE > '20130914' AND DATE < '20131023'";
        $version = mssql_query($select);
         
        $row = mssql_fetch_array($version);
        print_r($row);
         
        /*
        $bonus = ORM::factory('bonus')->find_all();
        foreach($bonus as $b)
        {
          print_r($b->as_array());   
        }*/
        
        /*
        $db = Database::instance('default');
         $db->query(Database::SELECT, 'SELECT * FROM BD_Bonus');
         print_r($db);
        
         $db = Database::instance('mssql');
         $db->query(Database::SELECT, 'SELECT * FROM BD_bonus');
         print_r($db);
         */
        // print_r($db->execute());
        // print_r($db->current());
         
         /*$card = DB::query(Database::SELECT, 'SELECT `ID` FROM `BD_Bonus` WHERE `ID`=:ID LIMIT 1')
                ->param(':ID', '457899878');
           $rows = $card->execute();
        print_r($rows);*/   
         //$rows = $db->execute();
       //  $row = $rows->current();
        
        //print_r($row);
        $this->template->content = View::factory('index');
	}

} // End Welcome
