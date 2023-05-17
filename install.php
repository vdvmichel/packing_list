<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . '_project_packing_list')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . '_project_packing_list`
      (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `project_id` INT(11) NOT NULL,
        `item_id` INT(11) NOT NULL,
        `description` MEDIUMTEXT NOT NULL,
        `long_description` MEDIUMTEXT NULL,
        `qty` DECIMAL(15,2) NOT NULL,
        `rate` DECIMAL(15,2) NOT NULL,
        `unit` VARCHAR(40) NULL DEFAULT NULL,
        `item_order` INT(11) NULL DEFAULT NULL,

        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}