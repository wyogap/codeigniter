ALTER TABLE `bpkad`.`dbo_crud_columns` 
ADD COLUMN `allow_sort` SMALLINT(6) NOT NULL DEFAULT 1 AFTER `subtable_fkey_column`,
ADD COLUMN `default_sort_no` INT NOT NULL DEFAULT 0 AFTER `allow_sort`,
ADD COLUMN `default_sort_asc` SMALLINT NOT NULL DEFAULT 1 AFTER `default_sort_no`;
