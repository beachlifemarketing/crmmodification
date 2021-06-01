<?php

defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
if (!$CI->db->table_exists(db_prefix() . 'manufactures')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'manufactures` (
    `id` int NOT NULL AUTO_INCREMENT,
  `staff_id` int NULL DEFAULT NULL,
  `contract_type` int NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` enum(\'active\',\'inactive\') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
      )  ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';'
	);
}


if (!$CI->db->table_exists(db_prefix() . 'products')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `order_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `product_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `manufacturer_id` int NULL DEFAULT NULL,
  `quantity` int NULL DEFAULT NULL,
  `date_create` date NULL DEFAULT NULL,
  `product_status` enum(\'active\',\'inactive\') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`product_id`) USING BTREE
      )  ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';'
	);
}


if (!$CI->db->table_exists(db_prefix() . 'products_files')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'product_files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `file_name` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subject` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `filetype` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `dateadded` datetime(0) NOT NULL,
  `last_activity` datetime(0) NULL DEFAULT NULL,
  `product_id` int NOT NULL,
  `visible_to_customer` tinyint(1) NULL DEFAULT 0,
  `staffid` int NOT NULL,
  `external` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `external_link` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `thumbnail_link` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
      )  ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';'
	);
}


if (!$CI->db->table_exists(db_prefix() . 'orders')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . 'orders` (
    `order_id` int NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `order_client_id` int NOT NULL,
  `order_quantity` int NOT NULL DEFAULT 0,
  `order_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `order_product_id` int NOT NULL,
  `create_order_date` date NULL DEFAULT NULL,
  `due_order_date` date NULL DEFAULT NULL,
  `order_status` enum(\'inprocessing\',\'cancel\',\'completed\') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT \'inprocessing\',
  PRIMARY KEY (`order_id`) USING BTREE
      )  ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';'
	);
}
