<?php
class ModelAccountSignup extends Model {

	 

	public function install() {
             $sql = " SHOW TABLES LIKE '".DB_PREFIX."signupkw'";
		$query = $this->db->query( $sql );
               if( count($query->rows) <=0 ){ 
			$sql = array();
                        $sql[]  = "create table if not exists ".DB_PREFIX."signupkw (
                                   enablemod tinyint(0) not null default 0,
                                     single_box tinyint(0) not null default 0". 
                        //version update everytime                                     
                                     // kw_version_mod tinyint(4) not null default 1                                 
                                   ")";
			$sql[]  = "create table if not exists ".DB_PREFIX."signupkw_attributes(
                                    f_name_show tinyint(0) not null default 1 ,f_name_req tinyint(0)  not null default 1,f_name_cstm varchar(255) not null default '',
                                    l_name_show tinyint(0) not null default 1 ,l_name_req tinyint(0)  not null default 1,l_name_cstm varchar(255) not null default '',
                                    mob_show tinyint(0) not null default 1 ,mob_req tinyint(0)  not null default 1,mob_cstm varchar(255) not null default '',
                                    fax_show tinyint(0) not null default 1 ,fax_req tinyint(0)  not null default 1,fax_cstm varchar(255) not null default '',
                                    company_show tinyint(0) not null default 1 ,company_req tinyint(0)  not null default 1,company_cstm varchar(255) not null default '',
                                    companyId_show tinyint(0) not null default 1 ,companyId_req tinyint(0)  not null default 1,companyId_cstm varchar(255) not null default '',
                                    address1_show tinyint(0) not null default 1 ,address1_req tinyint(0)  not null default 1,address1_cstm varchar(255) not null default '',
                                    address2_show tinyint(0) not null default 1 ,address2_req tinyint(0)  not null default 1,address2_cstm varchar(255) not null default '',
                                    city_show tinyint(0) not null default 1 ,city_req tinyint(0)  not null default 1,city_cstm varchar(255) not null default '',
                                    pin_show tinyint(0) not null default 1 ,pin_req tinyint(0)  not null default 1,pin_cstm varchar(255) not null default '',
                                    state_show tinyint(0) not null default 1 ,state_req tinyint(0)  not null default 1,state_cstm varchar(255) not null default '',
                                    country_show tinyint(0) not null default 1 ,country_req tinyint(0)  not null default 1,country_cstm varchar(255) not null default '',
                                    passconf_show tinyint(0) not null default 1 ,passconf_req tinyint(0)  not null default 1,passconf_cstm varchar(255) not null default '',
                                    subsribe_show tinyint(0) not null default 1 ,subsribe_req tinyint(0)  not null default 1,subsribe_cstm varchar(255) not null default '',
									f_name_show_edit tinyint(0) not null default 1 ,f_name_req_edit tinyint(0)  not null default 1,
									l_name_show_edit tinyint(0) not null default 1 ,l_name_req_edit tinyint(0)  not null default 1,
                                    mob_show_edit tinyint(0) not null default 1 ,mob_req_edit tinyint(0)  not null default 1,
                                    fax_show_edit tinyint(0) not null default 1 ,fax_req_edit tinyint(0)  not null default 1,
                                    company_show_edit tinyint(0) not null default 1 ,company_req_edit tinyint(0)  not null default 1,
                                    companyId_show_edit tinyint(0) not null default 1 ,companyId_req_edit tinyint(0)  not null default 1,
                                    address1_show_edit tinyint(0) not null default 1 ,address1_req_edit tinyint(0)  not null default 1,
                                    address2_show_edit tinyint(0) not null default 1 ,address2_req_edit tinyint(0)  not null default 1,
                                    city_show_edit tinyint(0) not null default 1 ,city_req_edit tinyint(0)  not null default 1,
                                    pin_show_edit tinyint(0) not null default 1 ,pin_req_edit tinyint(0)  not null default 1,
                                    state_show_edit tinyint(0) not null default 1 ,state_req_edit tinyint(0)  not null default 1,
                                    country_show_edit tinyint(0) not null default 1 ,country_req_edit tinyint(0)  not null default 1,
                                    passconf_show_edit tinyint(0) not null default 1 ,passconf_req_edit tinyint(0)  not null default 1,
                                    subsribe_show_edit tinyint(0) not null default 1 ,subsribe_req_edit tinyint(0)  not null default 1,
									f_name_show_checkout tinyint(0) not null default 1 ,f_name_req_checkout tinyint(0)  not null default 1,
									l_name_show_checkout tinyint(0) not null default 1 ,l_name_req_checkout tinyint(0)  not null default 1,
                                    mob_show_checkout tinyint(0) not null default 1 ,mob_req_checkout tinyint(0)  not null default 1,
                                    fax_show_checkout tinyint(0) not null default 1 ,fax_req_checkout tinyint(0)  not null default 1,
                                    company_show_checkout tinyint(0) not null default 1 ,company_req_checkout tinyint(0)  not null default 1,
                                    companyId_show_checkout tinyint(0) not null default 1 ,companyId_req_checkout tinyint(0)  not null default 1,
                                    address1_show_checkout tinyint(0) not null default 1 ,address1_req_checkout tinyint(0)  not null default 1,
                                    address2_show_checkout tinyint(0) not null default 1 ,address2_req_checkout tinyint(0)  not null default 1,
                                    city_show_checkout tinyint(0) not null default 1 ,city_req_checkout tinyint(0)  not null default 1,
                                    pin_show_checkout tinyint(0) not null default 1 ,pin_req_checkout tinyint(0)  not null default 1,
                                    state_show_checkout tinyint(0) not null default 1 ,state_req_checkout tinyint(0)  not null default 1,
                                    country_show_checkout tinyint(0) not null default 1 ,country_req_checkout tinyint(0)  not null default 1,
                                    passconf_show_checkout tinyint(0) not null default 1 ,passconf_req_checkout tinyint(0)  not null default 1,
                                    subsribe_show_checkout tinyint(0) not null default 1 ,subsribe_req_checkout tinyint(0)  not null default 1,
                                    mob_min int  not null default 0,mob_max int  not null default 0,mob_fix int  not null default 0,
                                    pass_min int  not null default 0,pass_max int  not null default 0,pass_fix int  not null default 0,
                                     mob_numeric tinyint(0) not null default 0,mob_masking tinyint(0) not null default 0,
                                     companyId_numeric tinyint(0) not null default 0,
                                     fax_numeric tinyint(0) not null default 0,
                                     pin_numeric tinyint(0) not null default 0,pin_masking tinyint(0) not null default 0,
                                     def_state  int  not null default 0 ,
                                     def_country  int  not null default 0,
                                     show_address tinyint(0) not null default 0
                                                                          
                                    )	";
                        $sql[]  = "insert into ".DB_PREFIX."signupkw set enablemod =0";
                        $sql[]  = "insert into ".DB_PREFIX."signupkw_attributes set f_name_show =1";
                        
                        foreach( $sql as $q ){
				$query = $this->db->query( $q );
			}
                        
               }
               
               $this->upgrade();
     }
	 
 private function upgrade(){
     	//$this->language->load('module/signup');
     	$result = $this->db->query("Select * FROM ".DB_PREFIX."signupkw ");
		if(!isset($result->row['kw_version_mod'])){
			 $this->db->query('Alter table '.DB_PREFIX.'signupkw ADD COLUMN
				kw_version_mod tinyint(4) not null default 1
			' );
		}
		$query = $this->db->query('select kw_version_mod from '.DB_PREFIX.'signupkw LIMIT 1' );
		 if($query->row['kw_version_mod']<2){
			   	$this->db->query('ALTER TABLE '.DB_PREFIX.'signupkw_attributes ADD COLUMN
			   	 (f_name_sort tinyint(3) not null default 10 ,
									 l_name_sort tinyint(3) not null default 15 ,
									email_sort tinyint(3) not null default 20 ,
									mob_sort tinyint(3) not null default 25 ,
									fax_sort tinyint(3) not null default 30 ,
									company_sort tinyint(3) not null default 35 ,
									cgroup_sort tinyint(3) not null default 40 ,
									companyId_sort tinyint(3) not null default 45 ,
									taxId_sort tinyint(3) not null default 50 ,
									address1_sort tinyint(3) not null default 55 ,
									address2_sort tinyint(3) not null default 60 ,
									city_sort tinyint(3) not null default 65 ,
									pin_sort tinyint(3) not null default 80 ,
									state_sort tinyint(3) not null default 85 ,
									country_sort tinyint(3) not null default 90 ,
									pass_sort tinyint(3) not null default 95 ,
									passconf_sort tinyint(3) not null default 96 ,
									subscribe_sort tinyint(3) not null default 97) 
			   	');
			   	
			   $this->db->query('Update  '.DB_PREFIX.'signupkw Set kw_version_mod=2');
		 }
     }
	 
	 
	public function isActiveMod() {
		$this->install();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "signupkw LIMIT 1");
		return $query->row;
	}

	public function getModData() {


		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "signupkw_attributes LIMIT 1");
		return $query->row;
	}

}
?>