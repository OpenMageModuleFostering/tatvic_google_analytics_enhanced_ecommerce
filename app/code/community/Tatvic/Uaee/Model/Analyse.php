<?php

class Tatvic_Uaee_Model_Analyse{

	
	 public function __construct() {
        $this->helper = Mage::helper('tatvic_uaee');
	
    }
	
	public function tatvic_categoryList($categories){
		foreach ($categories as $cat_id) {
						   $category = Mage::getModel('catalog/category')->load($cat_id) ;
						   $category_list.=$category->getName().","; 
					}
		return addslashes(trim($category_list,","));
	}
	
	public function tatvic_collectionJSON($product,$product_brand,$position,$JSONobj){
			?><script>
			
			<?php echo $JSONobj; ?>['<?php echo $product->getProductUrl(); ?>']={
				'id':'<?php echo $product->getId(); ?>',
				'sku':'<?php echo addslashes($product->getSku()); ?>',
				'Name':'<?php echo addslashes($product->getName()); ?>',
				'categories':'<?php echo addslashes($this->tatvic_categoryList($product->getCategoryIds())); ?>',
				'brand':'<?php echo addslashes($product_brand); ?>',
				'price':'<?php echo round($product->getFinalPrice(),2); ?>',
				'position':'<?php echo $position; ?>',
				'url2':'<?php echo rtrim(Mage::getUrl($product->getUrlPath()),'/')?>'
			};
				
					
			</script>
			<?php
	}

}

?>