<?php 
class ModelModuleSignup extends Model {
    
   

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
     	$this->language->load('module/signup');
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
		
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "signupkw LIMIT 1");		
		return $query->row;
	}
	
        public function getModData() {
		
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "signupkw_attributes LIMIT 1");		
		return $query->row;
	}
	public function editSetting($data) {
		

		$this->db->query("update " . DB_PREFIX . "signupkw_attributes  set 
                     f_name_show = " . (isset($data['f_name_show']) ? (int)$data['f_name_show'] : 0) . 
                       ", f_name_req = " . (isset($data['f_name_req']) ? (int)$data['f_name_req'] : 0) . 
", l_name_show = " . (isset($data['l_name_show']) ? (int)$data['l_name_show'] : 0) . 
", l_name_req = " . (isset($data['l_name_req']) ? (int)$data['l_name_req'] : 0) . 
", mob_show = " . (isset($data['mob_show']) ? (int)$data['mob_show'] : 0) . 
", mob_req = " . (isset($data['mob_req']) ? (int)$data['mob_req'] : 0) . 
", fax_show = " . (isset($data['fax_show']) ? (int)$data['fax_show'] : 0) . 
", fax_req = " . (isset($data['fax_req']) ? (int)$data['fax_req'] : 0) . 
", company_show = " . (isset($data['company_show']) ? (int)$data['company_show'] : 0) . 
", company_req = " . (isset($data['company_req']) ? (int)$data['company_req'] : 0) . 
", companyId_show = " . (isset($data['companyId_show']) ? (int)$data['companyId_show'] : 0) . 
", companyId_req = " . (isset($data['companyId_req']) ? (int)$data['companyId_req'] : 0) . 
", address1_show = " . (isset($data['address1_show']) ? (int)$data['address1_show'] : 0) . 
", address1_req = " . (isset($data['address1_req']) ? (int)$data['address1_req'] : 0) . 
", address2_show = " . (isset($data['address2_show']) ? (int)$data['address2_show'] : 0) . 
", address2_req = " . (isset($data['address2_req']) ? (int)$data['address2_req'] : 0) . 
", city_show = " . (isset($data['city_show']) ? (int)$data['city_show'] : 0) . 
", city_req = " . (isset($data['city_req']) ? (int)$data['city_req'] : 0) . 
", pin_show = " . (isset($data['pin_show']) ? (int)$data['pin_show'] : 0) . 
", pin_req = " . (isset($data['pin_req']) ? (int)$data['pin_req'] : 0) . 
", state_show = " . (isset($data['state_show']) ? (int)$data['state_show'] : 0) . 
", state_req = " . (isset($data['state_req']) ? (int)$data['state_req'] : 0) . 
", country_show = " . (isset($data['country_show']) ? (int)$data['country_show'] : 0) . 
", country_req = " . (isset($data['country_req']) ? (int)$data['country_req'] : 0) . 
", passconf_show = " . (isset($data['passconf_show']) ? (int)$data['passconf_show'] : 0) . 
", passconf_req = " . (isset($data['passconf_req']) ? (int)$data['passconf_req'] : 0) . 
", subsribe_show = " . (isset($data['subsribe_show']) ? (int)$data['subsribe_show'] : 0) . 
", subsribe_req = " . (isset($data['subsribe_req']) ? (int)$data['subsribe_req'] : 0).
", show_address = " . (isset($data['show_address']) ? (int)$data['show_address'] : 0).		


", mob_numeric = " . (isset($data['mob_numeric']) ? (int)$data['mob_numeric'] : 0).
", fax_numeric = " . (isset($data['fax_numeric']) ? (int)$data['fax_numeric'] : 0).
", pin_numeric = " . (isset($data['pin_numeric']) ? (int)$data['pin_numeric'] : 0).
", companyId_numeric = " . (isset($data['companyId_numeric']) ? (int)$data['companyId_numeric'] : 0).
", mob_masking = " . (isset($data['mob_masking']) ? (int)$data['mob_masking'] : 0).
", pin_masking = " . (isset($data['pin_masking']) ? (int)$data['pin_masking'] : 0).

", f_name_show_edit = " . (isset($data['f_name_show_edit']) ? (int)$data['f_name_show_edit'] : 0) . 
", f_name_req_edit = " . (isset($data['f_name_req_edit']) ? (int)$data['f_name_req_edit'] : 0) . 
", l_name_show_edit = " . (isset($data['l_name_show_edit']) ? (int)$data['l_name_show_edit'] : 0) . 
", l_name_req_edit = " . (isset($data['l_name_req_edit']) ? (int)$data['l_name_req_edit'] : 0) . 
", mob_show_edit = " . (isset($data['mob_show_edit']) ? (int)$data['mob_show_edit'] : 0) . 
", mob_req_edit = " . (isset($data['mob_req_edit']) ? (int)$data['mob_req_edit'] : 0) . 
", fax_show_edit = " . (isset($data['fax_show_edit']) ? (int)$data['fax_show_edit'] : 0) . 
", fax_req_edit = " . (isset($data['fax_req_edit']) ? (int)$data['fax_req_edit'] : 0) . 
", company_show_edit = " . (isset($data['company_show_edit']) ? (int)$data['company_show_edit'] : 0) . 
", company_req_edit = " . (isset($data['company_req_edit']) ? (int)$data['company_req_edit'] : 0) . 
", companyId_show_edit = " . (isset($data['companyId_show_edit']) ? (int)$data['companyId_show_edit'] : 0) . 
", companyId_req_edit = " . (isset($data['companyId_req_edit']) ? (int)$data['companyId_req_edit'] : 0) . 
", address1_show_edit = " . (isset($data['address1_show_edit']) ? (int)$data['address1_show_edit'] : 0) . 
", address1_req_edit = " . (isset($data['address1_req_edit']) ? (int)$data['address1_req_edit'] : 0) . 
", address2_show_edit = " . (isset($data['address2_show_edit']) ? (int)$data['address2_show_edit'] : 0) . 
", address2_req_edit = " . (isset($data['address2_req_edit']) ? (int)$data['address2_req_edit'] : 0) . 
", city_show_edit = " . (isset($data['city_show_edit']) ? (int)$data['city_show_edit'] : 0) . 
", city_req_edit = " . (isset($data['city_req_edit']) ? (int)$data['city_req_edit'] : 0) . 
", pin_show_edit = " . (isset($data['pin_show_edit']) ? (int)$data['pin_show_edit'] : 0) . 
", pin_req_edit = " . (isset($data['pin_req_edit']) ? (int)$data['pin_req_edit'] : 0) . 
", state_show_edit = " . (isset($data['state_show_edit']) ? (int)$data['state_show_edit'] : 0) . 
", state_req_edit = " . (isset($data['state_req_edit']) ? (int)$data['state_req_edit'] : 0) . 
", country_show_edit = " . (isset($data['country_show_edit']) ? (int)$data['country_show_edit'] : 0) . 
", country_req_edit = " . (isset($data['country_req_edit']) ? (int)$data['country_req_edit'] : 0) .

", f_name_show_checkout = " . (isset($data['f_name_show_checkout']) ? (int)$data['f_name_show_checkout'] : 0) . 
", f_name_req_checkout = " . (isset($data['f_name_req_checkout']) ? (int)$data['f_name_req_checkout'] : 0) . 
", l_name_show_checkout = " . (isset($data['l_name_show_checkout']) ? (int)$data['l_name_show_checkout'] : 0) . 
", l_name_req_checkout = " . (isset($data['l_name_req_checkout']) ? (int)$data['l_name_req_checkout'] : 0) . 
", mob_show_checkout = " . (isset($data['mob_show_checkout']) ? (int)$data['mob_show_checkout'] : 0) . 
", mob_req_checkout = " . (isset($data['mob_req_checkout']) ? (int)$data['mob_req_checkout'] : 0) . 
", fax_show_checkout = " . (isset($data['fax_show_checkout']) ? (int)$data['fax_show_checkout'] : 0) . 
", fax_req_checkout = " . (isset($data['fax_req_checkout']) ? (int)$data['fax_req_checkout'] : 0) . 
", company_show_checkout = " . (isset($data['company_show_checkout']) ? (int)$data['company_show_checkout'] : 0) . 
", company_req_checkout = " . (isset($data['company_req_checkout']) ? (int)$data['company_req_checkout'] : 0) . 
", companyId_show_checkout = " . (isset($data['companyId_show_checkout']) ? (int)$data['companyId_show_checkout'] : 0) . 
", companyId_req_checkout = " . (isset($data['companyId_req_checkout']) ? (int)$data['companyId_req_checkout'] : 0) . 
", address1_show_checkout = " . (isset($data['address1_show_checkout']) ? (int)$data['address1_show_checkout'] : 0) . 
", address1_req_checkout = " . (isset($data['address1_req_checkout']) ? (int)$data['address1_req_checkout'] : 0) . 
", address2_show_checkout = " . (isset($data['address2_show_checkout']) ? (int)$data['address2_show_checkout'] : 0) . 
", address2_req_checkout = " . (isset($data['address2_req_checkout']) ? (int)$data['address2_req_checkout'] : 0) . 
", city_show_checkout = " . (isset($data['city_show_checkout']) ? (int)$data['city_show_checkout'] : 0) . 
", city_req_checkout = " . (isset($data['city_req_checkout']) ? (int)$data['city_req_checkout'] : 0) . 
", pin_show_checkout = " . (isset($data['pin_show_checkout']) ? (int)$data['pin_show_checkout'] : 0) . 
", pin_req_checkout = " . (isset($data['pin_req_checkout']) ? (int)$data['pin_req_checkout'] : 0) . 
", state_show_checkout = " . (isset($data['state_show_checkout']) ? (int)$data['state_show_checkout'] : 0) . 
", state_req_checkout = " . (isset($data['state_req_checkout']) ? (int)$data['state_req_checkout'] : 0) . 
", country_show_checkout = " . (isset($data['country_show_checkout']) ? (int)$data['country_show_checkout'] : 0) . 
", country_req_checkout = " . (isset($data['country_req_checkout']) ? (int)$data['country_req_checkout'] : 0).
		
" , mob_min = '" . $this->db->escape($data['mob_min']) .
"', mob_max = '" . $this->db->escape($data['mob_max']) .
"', mob_fix = '" . $this->db->escape($data['mob_fix']) .
"', pass_min = '" . $this->db->escape($data['pass_min']) .
"', pass_max = '" . $this->db->escape($data['pass_max']) .
"', pass_fix = '" . $this->db->escape($data['pass_fix']) .
"' , def_country = " . $this->db->escape($data['country_id']) .
", def_state = " . $this->db->escape($data['zone_id']) .

" , f_name_sort = '" . $this->db->escape($data['f_name_sort']) .
"', l_name_sort = '" . $this->db->escape($data['l_name_sort']) .
"', email_sort = '" . $this->db->escape($data['email_sort']) .
"', mob_sort = '" . $this->db->escape($data['mob_sort']) .
"', fax_sort = '" . $this->db->escape($data['fax_sort']) .
"', company_sort = '" . $this->db->escape($data['company_sort']) .	
"', cgroup_sort = '" . $this->db->escape($data['cgroup_sort']) .
"', companyId_sort = '" . $this->db->escape($data['companyId_sort']) .
"', taxId_sort = '" . $this->db->escape($data['taxId_sort']) .
"', address1_sort = '" . $this->db->escape($data['address1_sort']) .
"', address2_sort = '" . $this->db->escape($data['address2_sort']) .
"', city_sort = '" . $this->db->escape($data['city_sort']) .	
"', pin_sort = '" . $this->db->escape($data['pin_sort']) .
"', state_sort = '" . $this->db->escape($data['state_sort']) .
"', country_sort = '" . $this->db->escape($data['country_sort']) .
"', pass_sort = '" . $this->db->escape($data['pass_sort']) .
"', passconf_sort = '" . $this->db->escape($data['passconf_sort']) .
"', subscribe_sort = '" . $this->db->escape($data['subscribe_sort']) .
"'"				
 /*", f_name_cstm = '" . $this->db->escape($data['f_name_cstm']) .
"', l_name_cstm = '" . $this->db->escape($data['l_name_cstm']) .
"', mob_cstm = '" . $this->db->escape($data['mob_cstm']) .
"', fax_cstm = '" . $this->db->escape($data['fax_cstm']) .
"', company_cstm = '" . $this->db->escape($data['company_cstm']) .
"', companyId_cstm = '" . $this->db->escape($data['companyId_cstm']) .
"', address1_cstm = '" . $this->db->escape($data['address1_cstm']) .
"', address2_cstm = '" . $this->db->escape($data['address2_cstm']) .
"', city_cstm = '" . $this->db->escape($data['city_cstm']) .
"', pin_cstm = '" . $this->db->escape($data['pin_cstm']) .
"', state_cstm = '" . $this->db->escape($data['state_cstm']) .
"', country_cstm = '" . $this->db->escape($data['country_cstm']) .
"', passconf_cstm = '" . $this->db->escape($data['passconf_cstm']) .
"', subsribe_cstm = '" . $this->db->escape($data['subsribe_cstm']) .
*/
);
                
   $this->db->query("update " . DB_PREFIX . "signupkw  set
    enablemod = " . (isset($data['mod_enable']) ? (int)$data['mod_enable'] : 0).
       " , single_box = " . (isset($data['single_box']) ? (int)$data['single_box'] : 0)
 );
}
	
	
}
?>