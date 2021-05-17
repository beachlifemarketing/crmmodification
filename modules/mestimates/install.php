<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();
if (!$CI->db->table_exists(db_prefix() . 'mestimates')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'mestimates` (
   `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `contact_id` int NOT NULL,
  `job_number` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `customer_group_id` int NULL DEFAULT NULL,
  `claim_number` varbinary(100) NULL DEFAULT NULL,
  `date` date NULL DEFAULT NULL,
  `lic_number` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pymt_option` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `estimate_time` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sub_total` decimal(20, 2) NULL DEFAULT NULL,
  `discount_percent` float(10, 2) NULL DEFAULT NULL,
  `total` decimal(20, 2) NULL DEFAULT NULL,
  `paid_by_customer_percent` float(10, 2) NULL DEFAULT NULL,
  `balance_due` decimal(20, 2) NULL DEFAULT NULL,
  `status` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `due_date` date NULL DEFAULT NULL,
  `group_id` int NULL DEFAULT NULL,
  `notified` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `paid_by_customer_text` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `staff_id` int NULL DEFAULT NULL,
  `representative` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `balance_due_val` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `balance_due_text` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `discount_val` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `discount` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `adminnote` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `template_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `project_id` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
      )  ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';'
    );
}

if (!$CI->db->table_exists(db_prefix() . 'mestimates_detail')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'mestimates_detail` (
    `id` int NOT NULL AUTO_INCREMENT,
  `mestimate_id` int NOT NULL,
  `contact_id` int NOT NULL,
  `area` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `size` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `description` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `qty` int NULL DEFAULT NULL,
  `qty_unit` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `px` int NULL DEFAULT NULL,
  `px_unit` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `duration` int NULL DEFAULT NULL,
  `duration_unit` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `amount` decimal(20, 2) NULL DEFAULT NULL,
  `status` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
      )  ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';'
    );
}


if (!$CI->db->table_exists(db_prefix() . 'mestimate_files')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'mestimate_files` (
    `id` int NOT NULL AUTO_INCREMENT,
  `file_name` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subject` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `filetype` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `dateadded` datetime NOT NULL,
  `last_activity` datetime NULL DEFAULT NULL,
  `mestimate_id` int NOT NULL,
  `visible_to_customer` tinyint(1) NULL DEFAULT 0,
  `staffid` int NOT NULL,
  `contact_id` int NOT NULL DEFAULT 0,
  `external` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `external_link` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `thumbnail_link` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
      )  ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';'
    );
}


if (!$CI->db->table_exists(db_prefix() . 'mestimate_file')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'mestimate_file` (
    `mestimate_id` int NOT NULL,
  `file_id` int NOT NULL
      )  ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';'
    );
}