<?php
	class Tatvic_Uaee_Model_Observer extends Mage_Core_Model_Observer 
	{
			
		public function __construct() {
			$this->analyse = Mage::getSingleton('tatvic_uaee/analyse');
			$store_id = Mage::app()->getStore();
			$brand_code = "";
			static $position = 0;
			$prev_page = "";
			$curr_page = "";	
			$JSONobj = "";
			$flag = false;
		}
		public function adminSystemConfigChangedSection()
		{
		
			if(Mage::getStoreConfigFlag('tatvic_uaee/general/enable')){
				
				$in_model = Mage::getStoreConfig('tatvic_uaee/general/installMail',Mage::app()->getStore());
				if($in_model == 0){
					$tvcDataUpdate = new Mage_Core_Model_Config();
					$tvcDataUpdate->saveConfig('tatvic_uaee/general/installMail', 1, 'default', 0);
					$tvcDataUpdate->saveConfig('tatvic_uaee/general/uninstallMail', 0, 'default', 0);
					$this->MageInfoConfig();
					
				}
			}
			else{
				
				$un_model = Mage::getStoreConfig('tatvic_uaee/general/uninstallMail',Mage::app()->getStore());
				if($un_model==0){
					$tvcDataUpdate = new Mage_Core_Model_Config();
					$tvcDataUpdate->saveConfig('tatvic_uaee/general/installMail', 0, 'default', 0);
					$tvcDataUpdate->saveConfig('tatvic_uaee/general/uninstallMail', 1, 'default', 0);
					$this->MageInfoConfigDisable();
					
				}
				
			}
			//	exit;
		}
		public function MageInfoConfigDisable(){
			$newTimeStamp = date('Y-m-d H:i:s');
			$email = Mage::getStoreConfig('tatvic_uaee/general/email_id');
			$email = $email."-".$newTimeStamp;
			$domain = Mage::getBaseUrl (Mage_Core_Model_Store::URL_TYPE_WEB);
			$this->send_email_to_tatvic($email, $domain);
			
		}
		public function MageInfoConfig(){
				$email = Mage::getStoreConfig('tatvic_uaee/general/email_id');
				$domain = Mage::getBaseUrl (Mage_Core_Model_Store::URL_TYPE_WEB);
				$this->send_email_to_tatvic($email, $domain);
		}
		public function send_email_to_tatvic($email, $domain_name) {
		   //set POST variables
		   $url = "http://dev.tatvic.com/leadgen/woocommerce-plugin/store_email/";
		   $fields = array(
			   "email" => urlencode($email),
			   "domain_name" => urlencode($domain_name),
			   "store_type" => "Magento"
		   );
		   
		   foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');

					$ch = curl_init();
				
				//set the url, number of POST vars, POST data
				curl_setopt($ch,CURLOPT_URL, $url);
				curl_setopt($ch,CURLOPT_POST, count($fields));
				curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
				
				//execute post
				$result = curl_exec($ch);

				//close connection
				curl_close($ch);
		}
		public function tatvicGetBrandAttr(){
			if($this->brand_code == ""){
			
				$this->brand_code = Mage::getStoreConfig('tatvic_uaee/ecommerce/brand');
				return $this->brand_code;
			}
			else{
				return $this->brand_code;
			}
		}
		public function tatvicProductLoadAfter(Varien_Event_Observer $observer){
			$validPages = array('index','category','result');
			if(in_array($this->tatvic_page(),$validPages)){
			?>
			<script>
				
					<?php $this->tatvic_JSON($this->tatvic_page()); 
						$this->flag = true;
					?>
					
			</script>
			<?php
			}
				else{
					$this->flag = false;
				}
				$product = $observer->getData('product');
				$brand_value = "";
				$t_brand = $product->getResource()->getAttribute($this->tatvicGetBrandAttr());
				if($t_brand){
						$brand_value = addslashes(trim($t_brand ->getFrontend()->getValue($product)));
				}
				if($brand_value === "No"){
						$brand_value = "";
				}
				if($this->flag){
					$this->analyse->tatvic_collectionJSON($product,$brand_value,++$this->position,$this->JSONobj);
				}
		}
		
		public function tatvicProductList(Varien_Event_Observer $observer)
		{
			$collection = $observer->getEvent()->getCollection();
			
				foreach ($collection as $product)
				{
					$temp = Mage::getModel('catalog/product')->load($product->getId());
				}
		}
		public function tatvic_JSON($page){
		if($page == 'category'){
			?>
					var catProductList = catProductList || {};	
			<?php
			$this->JSONobj = 'catProductList';
		}
		if($page == 'result')	{
			?>
				
					var catalogSearch = catalogSearch || {};
				
			<?php
			$this->JSONobj = 'catalogSearch';
		}
		if($page == 'product'){
			?>
				
					var productObject = productObject || {};
				
			<?php
			$this->JSONobj = 'productObject';
		}
		
		if($page == 'index'){
			?>
			
				var homeObject = homeObject || {};
			
			<?php
			$this->JSONobj = 'homeObject';
		}
		
	}
	public function tatvic_page(){
		$page = Mage::app()->getRequest()->getControllerName();
		return $page;	
	}

	// Extra codes..
	
	
	
}	
?>