<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

class ModuleCatalogItemWalker extends ModuleCatalog
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_catalog_itemwalker';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### CATALOG ITEM WALKER ###';

			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;
			return $objTemplate->parse();
		}

		// Fallback template
		if (!strlen($this->catalog_layout))
			$this->catalog_layout = $this->strTemplate;

		$this->strTemplate = $this->catalog_layout;

		return parent::generate();
	}
	

	
	protected function compile()
	{
		$filterurl = $this->parseFilterUrl(array());
		
		$strOrderClause = '';
		$strWhereClause = '';
		
		$arrVisibleFields = deserialize($this->catalog_visible);
		$objCatalogName = $this->Database->prepare("SELECT tableName,aliasField FROM tl_catalog_types WHERE id=".$this->catalog)->limit(1)->execute();
				
				
		$arrFetchFields = array();
		if ($this->catalog_itemwalker_use_categories)
			$arrFetchFields = deserialize($this->catalog_itemwalker_category);
		else
			$arrFetchFields = $_SESSION['catalog_itemwalker_get'];
		
		
		if (count($arrFetchFields)>0)
		{
			$strDependedItem = $this->Database->prepare("SELECT ".implode(",",$arrFetchFields)." FROM ".$objCatalogName->tableName." WHERE ".$objCatalogName->aliasField."=%s")->limit(1)->execute($filterurl['current']['items']);
			
			$arrDep = $strDependedItem->fetchAllAssoc();
			
			$arrDepData = array();
			
			foreach ($arrDep[0] as $key=>$value)
			{
				$arrDepData[] = sprintf("%s=%s",$key,(is_string($value)) ? "'".$value."'" : $value);
				
			}
			
			$strWhereClause = "WHERE ".implode(" AND ",$arrDepData);
			$strOrderClause = "ORDER BY ".implode(",",array_keys($arrDep[0]))." ".$this->catalog_itemwalker_category_sort;
		}
		
		$objCatalogStmt = $this->Database->prepare("SELECT id,pid,".$objCatalogName->aliasField.",".implode(',',$arrVisibleFields)." 
								FROM ".$objCatalogName->tableName." 
								".$strWhereClause." 
								".$strOrderClause)->execute(); 

		$arrItems = array();
		
		$previousItem = '';
		$nextItem = '';
		$actualItem = '';
		$firstItem = '';
		$lastItem = '';
		
		$arrJumpToItems = array();
		
		
		$arrValueFields = $this->generateCatalog($objCatalogStmt);
		
		$strAliasField = $objCatalogName->aliasField;
		
		foreach ($arrValueFields as $dataField)
		{
			$arrJumpToItems['last'] = $dataField['url'];
			
			if (strlen($firstItem)==0)
			{
				$firstItem = $dataField['url'];
				$arrJumpToItems['first'] = $dataField['url'];
			}			
			
			if ((strlen($actualItem)>0) && (strlen($nextItem)==0))
			{		
				$nextItem = $dataField['url'];
				$arrJumpToItems['next'] = $dataField['url'];
			}	
			
			if ($dataField['data'][$strAliasField]['value']==$filterurl['current']['items'])
			{
				$actualItem = $dataField['url'];		
				$arrJumpToItems['actual'] = $dataField['url'];
			}
			 
			if (strlen($actualItem)==0)
			{
				$previousItem = $dataField['url'];
				$arrJumpToItems['previous'] = $dataField['url'];
			}			
			
			$arrInfo = array();
			
			unset($dataField['data'][$strAliasField]); 
			foreach ($dataField['data'] as $key=>$value)
			{
				$arrInfo[] = $value['value'];
				
		
			}
			
			$strAutoLabel ='';
			
			
			if ($this->catalog_itemwalker_autolabel_separator_type=="dynamic")
			{
				$strAutoLabel =vsprintf($this->catalog_itemwalker_autolabel_separator,$arrInfo);
			}
			
			
			if (($this->catalog_itemwalker_autolabel_separator_type=="static") || (!$this->catalog_itemwalker_use_categories))
			{
				$strSeparator = $this->catalog_itemwalker_autolabel_separator;
				
				if (strlen($strSeparator)==0)
					$strSeparator = ' ';
				
				$strAutoLabel =implode($strSeparator,$arrInfo);
			}
			
			$arrItems[$dataField['url']] = array(
				'itemField' => $dataField['url'],
				'jumpUrl' => $dataField['url'],
				'field' => $dataField['data'],
				'autoLabel' =>  $strAutoLabel 
				);
		}
		
		$this->Template->jumpToItems =$arrJumpToItems; 
		$this->Template->allItems = $arrItems;
	}
	


}

?>