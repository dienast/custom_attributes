CREATE TABLE /*TABLE_PREFIX*/t_item_custom_attr_groups (
	pk_i_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	s_name VARCHAR(255) NULL,
	s_heading VARCHAR(255) NULL,
	s_order_type VARCHAR(10) NULL,
	PRIMARY KEY (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_custom_attr_categories (
	fk_i_group_id INT(10) UNSIGNED NOT NULL,
	fk_i_category_id INT(10) UNSIGNED NOT NULL,
	PRIMARY KEY (fk_i_group_id, fk_i_category_id),
	FOREIGN KEY (fk_i_group_id) REFERENCES /*TABLE_PREFIX*/t_item_custom_attr_groups (pk_i_id),
	FOREIGN KEY (fk_i_category_id) REFERENCES /*TABLE_PREFIX*/t_category (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_custom_attr_meta (
	fk_i_group_id INT(10) UNSIGNED NOT NULL,
	fk_i_field_id INT(10) UNSIGNED NOT NULL,
	PRIMARY KEY (fk_i_group_id, fk_i_field_id),
	FOREIGN KEY (fk_i_group_id) REFERENCES /*TABLE_PREFIX*/t_item_custom_attr_groups (pk_i_id),
	FOREIGN KEY (fk_i_field_id) REFERENCES /*TABLE_PREFIX*/t_item_custom_attr_fields (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

