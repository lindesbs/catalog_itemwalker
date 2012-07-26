<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');


$GLOBALS['TL_DCA']['tl_module']['palettes']['catalog_itemwalker']     = 
	'{title_legend},name,headline,type;{config_legend},catalog,'.
	'catalog_itemwalker_use_categories,catalog_visible,catalog_itemwalker_autolabel_separator_type,catalog_itemwalker_autolabel_separator;catalog_jumpTo;'.
	'{template_legend:hide},catalog_template,catalog_layout;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'catalog_itemwalker_use_categories';

$GLOBALS['TL_DCA']['tl_module']['subpalettes']['catalog_itemwalker_use_categories'] = 'catalog_itemwalker_category,catalog_itemwalker_category_sort';
	
	
	
$GLOBALS['TL_DCA']['tl_module']['fields']['catalog_itemwalker_use_categories'] = array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['catalog_itemwalker_use_categories'],
		'exclude'                 => true,
		'inputType'               => 'checkbox',
		'eval'                    => array('mandatory'=> true,'submitOnChange'=>true)
	);
	
	
$GLOBALS['TL_DCA']['tl_module']['fields']['catalog_itemwalker_category'] = array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['catalog_itemwalker_category'],
		'exclude'                 => true,
		'inputType'               => 'checkbox',
		'options_callback'        => array('tl_module_catalog_itemwalker', 'getCategoryFields'),
		'eval'                    => array('mandatory'=> true,'multiple'=>true)
	);
	
	
	
$GLOBALS['TL_DCA']['tl_module']['fields']['catalog_itemwalker_category_sort'] = array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['catalog_itemwalker_category_sort'],
		'exclude'                 => true,
		'inputType'               => 'select',
		'options'	=> array('ASC','DESC'),
		'eval'                    => array('mandatory'=> true,'submitOnChange'=>true)
	);
	

	
$GLOBALS['TL_DCA']['tl_module']['fields']['catalog_itemwalker_autolabel_separator_type'] = array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['catalog_itemwalker_autolabel_separator_type'],
		'exclude'                 => true,
		'inputType'               => 'select',
		'options'				=> array('static','dynamic'),
		'reference'				=> &$GLOBALS['TL_LANG']['tl_module']['catalog_itemwalker_autolabel_separator_type'],
		'eval'                    => array('mandatory'=> true)
	);
	
	
$GLOBALS['TL_DCA']['tl_module']['fields']['catalog_itemwalker_autolabel_separator'] = array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['catalog_itemwalker_autolabel_separator'],
		'exclude'                 => true,
		'inputType'               => 'text',
		'eval'                    => array()
	);
	
	
	
	
	
class tl_module_catalog_itemwalker extends Backend
{
	/**
	 * Get all filter fields and return them as array
	 * @return array
	 */
	public function getCategoryFields(DataContainer $dc)
	{
		$arrSortingBar=array();
		$arrSortingBarFinal=array();
		
		$sqlSortingFields = $this->Database->prepare('SELECT * FROM tl_catalog_fields WHERE pid=?')
				->execute($dc->activeRecord->catalog);
				
				
		
		while ($sqlSortingFields->next())
		{
			$arrSortingBarFinal[$sqlSortingFields->colName] = $sqlSortingFields->colName.'::['.$sqlSortingFields->description.']';
		}
				
		return $arrSortingBarFinal;
		
	}
	
	
}
	