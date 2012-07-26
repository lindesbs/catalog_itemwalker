<?php 

class ModuleCatalogItemWalkerHooks extends Frontend 
{
	
	
	public function parseCatalogHook($arrCatalog, $objTemplate, $objCatalog)
	{
		
		if ($objCatalog instanceof ModuleCatalogList)
		{
			
			$arrGet = array_keys($_GET);
			
			unset($arrGet[array_search("items", $arrGet)]);
			$_SESSION['catalog_itemwalker_get'] = $arrGet; 
		}
		
		return $arrCatalog;
	}
}

