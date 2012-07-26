<?php 

$GLOBALS['FE_MOD']['catalog']['catalog_itemwalker']	= 'ModuleCatalogItemWalker';
$GLOBALS['TL_HOOKS']['parseCatalog'][] = array('ModuleCatalogItemWalkerHooks','parseCatalogHook');
