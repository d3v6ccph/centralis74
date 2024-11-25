ALTER TABLE `physical_inventory_contents`
    ADD COLUMN `status` VARCHAR(10) NOT NULL DEFAULT 'valid' COMMENT 'valid or invalid' AFTER `moving_variance`;
ALTER TABLE `physical_inventory_contents`
    ADD COLUMN `status_remarks` VARCHAR(255) NULL AFTER `status`;
ALTER TABLE `physical_inventory`
    CHANGE COLUMN `status` `status` INT(11) NULL DEFAULT '0' COMMENT '0=pending, 1=verfied, 2=cancelled, 3=invalidated' AFTER `remarks`;

