
CREATE TABLE `dbo_api_limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `count` int(10) NOT NULL,
  `hour_started` int(11) NOT NULL,
  `api_key` varchar(40) NOT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100;

CREATE TABLE `dbo_api_levels` (
  `level` int(2) NOT NULL,
  `label` varchar(200) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`level`)
) ENGINE=InnoDB;

CREATE TABLE `dbo_api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
  `ip_addresses` text,
  `created_by` bigint(20) NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100;

CREATE TABLE `dbo_api_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(40) NOT NULL DEFAULT '',
  `all_access` tinyint(1) NOT NULL DEFAULT '0',
  `controller` varchar(50) NOT NULL DEFAULT '',
  `created_by` bigint(20) NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100;

CREATE TABLE `dbo_api_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` varchar(1) NOT NULL,
  `response_code` smallint(3) DEFAULT '0',
  `created_by` bigint(20) NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100;

INSERT INTO `dbo_api_levels` (`level`, `label`) VALUES ('0', 'Default');
INSERT INTO `dbo_api_levels` (`level`, `label`, `role_id`) VALUES ('99', 'Admin', '2');
INSERT INTO `dbo_api_levels` (`level`, `label`, `role_id`) VALUES ('10', 'User', '99');

ALTER TABLE `dbo_crud_columns` 
ADD COLUMN `allow_search` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `filter_options_array`;

INSERT INTO `dbo_crud_columns` (`id`, `table_id`, `name`, `column_name`, `order_no`, `visible`, `label`, `css`, `column_type`, `data_priority`, `options_array`, `options_data_model`, `foreign_key`, `reference_table_name`, `reference_key_column`, `reference_lookup_column`, `reference_soft_delete`, `reference_where_clause`, `allow_insert`, `allow_edit`, `edit_field`, `edit_label`, `edit_type`, `edit_css`, `edit_compulsory`, `edit_info`, `edit_attr_array`, `edit_bubble`, `edit_onchange_js`, `edit_options_array`, `allow_filter`, `filter_label`, `filter_type`, `filter_css`, `filter_attr_array`, `filter_onchange_js`, `filter_options_array`, `allow_search`, `created_on`, `updated_on`, `updated_by`, `is_deleted`) VALUES (NULL, '9', 'allow_search', '', '30', '1', 'Search', '', 'tcg_toggle', '5', '', '', '0', '', '', '', '1', '', '1', '1', '', '', 'tcg_toggle', '', '0', '', '', '0', '', '', '0', '', '', '', '', '', '', '0', '2021-03-17 20:17:56', '2021-03-17 16:53:41', '990', '0');

INSERT INTO `dbo_crud_tables` (`id`, `name`, `table_name`, `key_column`, `lookup_column`, `ajax`, `initial_load`, `row_id_column`, `row_select_column`, `editor`, `allow_add`, `allow_edit`, `allow_delete`, `edit_row_action`, `delete_row_action`, `allow_export`, `allow_import`, `filter`, `soft_delete`, `limit_selection`, `page_size`, `created_on`, `updated_on`, `is_deleted`) VALUES (NULL, 'API Keys', 'dbo_api_keys', 'id', NULL, '', '1', '0', '0', '1', '1', '1', '1', '0', '0', '0', '0', '1', '1', '0', '25', '2021-04-17 12:14:50', '2021-04-17 12:14:50', '0');
INSERT INTO `dbo_crud_tables` (`name`, `table_name`, `key_column`, `ajax`, `initial_load`, `row_id_column`, `row_select_column`, `editor`, `allow_add`, `allow_edit`, `allow_delete`, `edit_row_action`, `delete_row_action`, `allow_export`, `allow_import`, `filter`, `soft_delete`, `limit_selection`, `page_size`, `created_on`, `updated_on`, `is_deleted`) VALUES ('API Access', 'dbo_api_access', 'id', '', '1', '0', '0', '1', '1', '1', '1', '0', '0', '0', '0', '1', '1', '0', '25', '2021-04-17 12:14:50', '2021-04-17 12:14:50', '0');
INSERT INTO `dbo_crud_tables` (`id`, `name`, `table_name`, `editable_table_name`, `key_column`, `lookup_column`, `ajax`, `initial_load`, `row_id_column`, `row_select_column`, `editor`, `allow_add`, `allow_edit`, `allow_delete`, `add_custom_js`, `edit_custom_js`, `delete_custom_js`, `edit_row_action`, `delete_row_action`, `allow_export`, `export_custom_js`, `allow_import`, `import_custom_js`, `filter`, `soft_delete`, `data_model`, `xxxpage_name`, `xxxpage_title`, `xxxpage_icon`, `xxxpage_type`, `xxxpage_key`, `xxxheader_view`, `xxxfooter_view`, `xxxcustom_view`, `where_clause`, `orderby_clause`, `limit_selection`, `page_size`, `created_on`, `created_by`, `updated_on`, `updated_by`, `is_deleted`) VALUES (NULL, 'API Limits', 'dbo_api_limits', '', 'id', '', '', '1', '0', '0', '1', '1', '1', '1', '', '', '', '0', '0', '0', '', '0', '', '1', '1', '', '', '', '', '', '', '', '', '', '', '', '0', '25', '2021-04-17 12:14:50', '', '2021-04-17 12:14:50', '', '0');
INSERT INTO `dbo_crud_tables` (`id`, `name`, `table_name`, `editable_table_name`, `key_column`, `lookup_column`, `ajax`, `initial_load`, `row_id_column`, `row_select_column`, `editor`, `allow_add`, `allow_edit`, `allow_delete`, `add_custom_js`, `edit_custom_js`, `delete_custom_js`, `edit_row_action`, `delete_row_action`, `allow_export`, `export_custom_js`, `allow_import`, `import_custom_js`, `filter`, `soft_delete`, `data_model`, `xxxpage_name`, `xxxpage_title`, `xxxpage_icon`, `xxxpage_type`, `xxxpage_key`, `xxxheader_view`, `xxxfooter_view`, `xxxcustom_view`, `where_clause`, `orderby_clause`, `limit_selection`, `page_size`, `created_on`, `created_by`, `updated_on`, `updated_by`, `is_deleted`) VALUES (NULL, 'API Levels', 'dbo_api_levels', '', 'level', 'label', '', '1', '0', '0', '1', '1', '1', '1', '', '', '', '0', '0', '0', '', '0', '', '1', '1', '', '', '', '', '', '', '', '', '', '', '', '0', '25', '2021-04-17 12:14:50', '', '2021-04-17 12:14:50', '', '0');
INSERT INTO `dbo_crud_tables` (`id`, `name`, `table_name`, `editable_table_name`, `key_column`, `lookup_column`, `ajax`, `initial_load`, `row_id_column`, `row_select_column`, `editor`, `allow_add`, `allow_edit`, `allow_delete`, `add_custom_js`, `edit_custom_js`, `delete_custom_js`, `edit_row_action`, `delete_row_action`, `allow_export`, `export_custom_js`, `allow_import`, `import_custom_js`, `filter`, `soft_delete`, `data_model`, `xxxpage_name`, `xxxpage_title`, `xxxpage_icon`, `xxxpage_type`, `xxxpage_key`, `xxxheader_view`, `xxxfooter_view`, `xxxcustom_view`, `where_clause`, `orderby_clause`, `limit_selection`, `page_size`, `created_on`, `created_by`, `updated_on`, `updated_by`, `is_deleted`) VALUES (NULL, 'API Logs', 'dbo_api_logs', '', 'id', '', '', '1', '0', '0', '1', '0', '0', '1', '', '', '', '0', '0', '0', '', '0', '', '1', '1', '', '', '', '', '', '', '', '', '', '', '', '1000', '25', '2021-04-17 12:14:50', '', '2021-04-17 12:14:50', '', '0');

ALTER TABLE `dbo_crud_tables` 
ADD COLUMN `search` SMALLINT(6) NOT NULL DEFAULT 0 AFTER `filter`;

INSERT INTO `dbo_crud_columns` (`id`, `table_id`, `name`, `column_name`, `order_no`, `visible`, `label`, `css`, `column_type`, `data_priority`, `options_array`, `options_data_model`, `foreign_key`, `reference_table_name`, `reference_key_column`, `reference_lookup_column`, `reference_soft_delete`, `reference_where_clause`, `allow_insert`, `allow_edit`, `edit_field`, `edit_label`, `edit_type`, `edit_css`, `edit_compulsory`, `edit_info`, `edit_attr_array`, `edit_bubble`, `edit_onchange_js`, `edit_options_array`, `allow_filter`, `filter_label`, `filter_type`, `filter_css`, `filter_attr_array`, `filter_onchange_js`, `filter_options_array`, `allow_search`, `created_on`, `updated_on`, `updated_by`, `is_deleted`) VALUES (NULL, '8', 'search', '', '11', '1', 'Search', '', 'tcg_toggle', '-1', '', '', '0', '', '', '', '1', '', '1', '1', '', '', 'tcg_toggle', '', '0', '', '', '0', '', '', '0', '', '', '', '', '', '', '0', '2021-03-17 20:17:56', '2021-03-19 12:07:38', '990', '0');

ALTER TABLE `dbo_crud_columns` 
ADD COLUMN `filter_invalid_value` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `filter_options_array`;

INSERT INTO `dbo_crud_columns` (`id`, `table_id`, `name`, `column_name`, `order_no`, `visible`, `label`, `css`, `column_type`, `data_priority`, `options_array`, `options_data_model`, `foreign_key`, `reference_table_name`, `reference_key_column`, `reference_lookup_column`, `reference_soft_delete`, `reference_where_clause`, `allow_insert`, `allow_edit`, `edit_field`, `edit_label`, `edit_type`, `edit_css`, `edit_compulsory`, `edit_info`, `edit_attr_array`, `edit_bubble`, `edit_onchange_js`, `edit_options_array`, `allow_filter`, `filter_label`, `filter_type`, `filter_css`, `filter_attr_array`, `filter_onchange_js`, `filter_options_array`, `allow_search`, `created_on`, `updated_on`, `updated_by`, `is_deleted`) VALUES (NULL, '9', 'filter_invalid_value`', '', '37', '1', 'Filter Invalid Value', '', 'tcg_toggle', '-1', '', '', '0', '', '', '', '1', '', '1', '1', '', '', 'tcg_toggle', '', '0', '', '', '0', '', '', '0', '', '', '', '', '', '', '0', '2021-03-17 20:17:56', '2021-03-17 16:53:41', '990', '0');
