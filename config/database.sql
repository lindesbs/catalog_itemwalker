
CREATE TABLE `tl_module` (
  `catalog_itemwalker_use_categories` varchar(1) NOT NULL default '',
  `catalog_itemwalker_category` text NULL,
  `catalog_itemwalker_category_sort` varchar(64) NOT NULL default '',
  `catalog_itemwalker_autolabel_separator_type` varchar(16) NOT NULL default '',
  `catalog_itemwalker_autolabel_separator` varchar(255) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

