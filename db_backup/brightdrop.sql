/*
SQLyog Community v12.4.2 (64 bit)
MySQL - 10.1.13-MariaDB : Database - brightdrop
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`brightdrop` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `brightdrop`;

/*Table structure for table `account_managers` */

DROP TABLE IF EXISTS `account_managers`;

CREATE TABLE `account_managers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clients` text COLLATE utf8_unicode_ci,
  `workers` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` text COLLATE utf8_unicode_ci,
  `admins_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `account_managers` */

insert  into `account_managers`(`id`,`user_id`,`status`,`first_name`,`last_name`,`email`,`phone_number`,`address1`,`address2`,`city`,`state`,`zip`,`clients`,`workers`,`created_at`,`updated_at`,`deleted_at`,`admins_id`) values 
(1,2,NULL,'Account ','Manager','account@brightdrop.com','54656','24',NULL,'LK','FL','435562',NULL,NULL,'2019-03-03 13:01:48','0000-00-00 00:00:00',NULL,1);

/*Table structure for table `adjustments` */

DROP TABLE IF EXISTS `adjustments`;

CREATE TABLE `adjustments` (
  `id` int(10) DEFAULT NULL,
  `client_id` int(10) DEFAULT NULL,
  `worker_id` int(10) DEFAULT NULL,
  `reference_number` int(20) DEFAULT NULL,
  `date_submitted` date DEFAULT NULL,
  `payto` year(4) DEFAULT NULL,
  `paytoclient` int(10) DEFAULT NULL,
  `paytoworker` int(10) DEFAULT NULL,
  `billto` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billtoclient` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billtoworker` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adjustment_date` date DEFAULT NULL,
  `adjustment_total_hours` float(5,2) DEFAULT NULL,
  `other_description` text COLLATE utf8_unicode_ci,
  `other_amount` float(10,2) DEFAULT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_currency` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `internal_notes` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `adjustments` */

/*Table structure for table `admins` */

DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `admins` */

insert  into `admins`(`id`,`user_id`,`status`,`first_name`,`last_name`,`email`,`phone`,`address1`,`address2`,`city`,`state`,`zip`,`created_at`,`updated_at`,`deleted_at`) values 
(1,5,'active','Admin','Brightdrop','admin@brightdrop.com','35326623535','32ABR',NULL,'ID','FL','54633','2019-03-03 16:38:40',NULL,NULL),
(2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `cash_advances` */

DROP TABLE IF EXISTS `cash_advances`;

CREATE TABLE `cash_advances` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) DEFAULT NULL,
  `worker_id` int(10) DEFAULT NULL,
  `payment_method` enum('weem','wu') COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_description` text COLLATE utf8_unicode_ci,
  `open_cash_advances` text COLLATE utf8_unicode_ci,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cash_advances` */

/*Table structure for table `clients` */

DROP TABLE IF EXISTS `clients`;

CREATE TABLE `clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `workers_ids` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','past','potential') COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address1` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address2` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_foreign` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_development` int(10) DEFAULT NULL,
  `account_manager` int(10) DEFAULT NULL,
  `industry` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job_function` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_generated_by` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `direct_contact_business_accountmanager` int(10) DEFAULT NULL,
  `billing_cycle_next_end_date` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `billing_cycle_next_end_date_adder` int(10) DEFAULT NULL,
  `overtime_pay_provided` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT NULL,
  `overtime_percent` int(3) DEFAULT NULL,
  `invoice_method` enum('automatically','manual') COLLATE utf8_unicode_ci DEFAULT NULL,
  `ACH_discount_participation` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method` enum('client_process_ach','internal_process_ach','internal_process_cc') COLLATE utf8_unicode_ci DEFAULT NULL,
  `pto_infomation` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT NULL,
  `who_pays_pto` enum('brightdrop','client') COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_pto_days` int(10) DEFAULT NULL,
  `holiday_shedule_offered` enum('yes_paid','yes_unpaid','no_holiday') COLLATE utf8_unicode_ci DEFAULT NULL,
  `who_pays_holiday` enum('brightdrop','client','na') COLLATE utf8_unicode_ci DEFAULT NULL,
  `holidays` text COLLATE utf8_unicode_ci,
  `contacts` text COLLATE utf8_unicode_ci,
  `workers` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `country_other` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_managers_id` int(10) DEFAULT NULL,
  `industry_other` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job_function_other` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `direct_contact_internal_payroll_admin` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `marketing_program_other` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_referral` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_generated_other` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lunchtime_billable` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lunchtime_billable_max_minutes` int(10) DEFAULT NULL,
  `breaktime_billable` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `breaktime_billable_max_minutes` int(10) DEFAULT NULL,
  `internal_processor` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admins_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `clients` */

insert  into `clients`(`id`,`workers_ids`,`status`,`client_name`,`website`,`phone`,`country`,`address1`,`address2`,`address_foreign`,`city`,`state`,`zip`,`business_development`,`account_manager`,`industry`,`job_function`,`lead_generated_by`,`direct_contact_business_accountmanager`,`billing_cycle_next_end_date`,`billing_cycle_next_end_date_adder`,`overtime_pay_provided`,`overtime_percent`,`invoice_method`,`ACH_discount_participation`,`payment_method`,`pto_infomation`,`who_pays_pto`,`default_pto_days`,`holiday_shedule_offered`,`who_pays_holiday`,`holidays`,`contacts`,`workers`,`created_at`,`updated_at`,`deleted_at`,`country_other`,`account_managers_id`,`industry_other`,`job_function_other`,`direct_contact_internal_payroll_admin`,`marketing_program_other`,`client_referral`,`lead_generated_other`,`lunchtime_billable`,`lunchtime_billable_max_minutes`,`breaktime_billable`,`breaktime_billable_max_minutes`,`internal_processor`,`admins_id`) values 
(16,'1,2,16,17','active','Moto Soft Ltd','http://motosoft.com','435-353-2344','US','1243 ave',NULL,NULL,'LK','FL','432345',1,1,'Accounting/Bookkeeping','Answering Service','Direct Contact - Business Development',1,'2019-03-08',10,'yes',1,'automatically','yes','client_process_ach','yes','brightdrop',3,'yes_paid','brightdrop',NULL,NULL,NULL,'2019-03-07 03:57:15',NULL,NULL,NULL,1,NULL,NULL,'p1',NULL,'0',NULL,'yes',30,'yes',30,'Quickbooks Online',1),
(17,'1,2,16,17','active','Jet stream','http://jetstream.com','345-3456-456','US','34hd','345',NULL,'ert','et','34534',1,1,'Accounting/Bookkeeping','Answering Service','Direct Contact - Business Development',1,'2019-03-18',8,'yes',1,'automatically','yes','client_process_ach','yes','brightdrop',1,'yes_paid','brightdrop',NULL,NULL,NULL,'2019-03-07 03:57:18',NULL,NULL,NULL,1,NULL,NULL,'p1',NULL,'1',NULL,'yes',60,'yes',60,'Quickbooks Online',1),
(18,'1,2,16,17','active','Ironrock soft','http://ironrock.com','593-234-3422','US','332 AVE',NULL,NULL,'LAKE','FL','33245',1,1,'Accounting/Bookkeeping','Answering Service','Direct Contact - Business Development',1,'2019-03-14',5,'yes',1,'automatically','yes','client_process_ach','yes','brightdrop',5,'yes_paid','brightdrop',NULL,NULL,NULL,'2019-03-07 03:59:08',NULL,NULL,NULL,1,NULL,NULL,'p1',NULL,'0',NULL,'yes',30,'yes',26,'Quickbooks Online',NULL),
(19,'2,16,17,1','active','People Social Inc','mypeople.com','4534-234-2342','US','4532',NULL,NULL,'Washington','WT','363452',1,1,'Accounting/Bookkeeping','Answering Service','Direct Contact - Business Development',1,'2019-03-23',4,'yes',1,'automatically','yes','client_process_ach','yes','brightdrop',5,'yes_paid','brightdrop',NULL,NULL,NULL,'2019-03-07 03:57:22',NULL,NULL,NULL,1,NULL,NULL,'p1',NULL,'1',NULL,'yes',30,'yes',30,'Quickbooks Online',NULL),
(20,'1,2','active','Web super LCC','websuper.com','555555','Other',NULL,NULL,'532 Putikov Moscow',NULL,NULL,NULL,1,1,'Accounting/Bookkeeping','Cold Calling','Direct Contact - Business Development',1,NULL,10,'yes',30,'automatically','yes','client_process_ach','yes','brightdrop',10,'yes_paid','brightdrop',NULL,NULL,NULL,'2019-03-06 17:05:01',NULL,NULL,'Russia',1,NULL,NULL,'p1','Email campaign','0',NULL,'yes',60,'yes',60,'Quickbooks Online',NULL);

/*Table structure for table `contacts` */

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `clients_id` int(10) DEFAULT NULL,
  `account_managers_id` int(10) DEFAULT NULL,
  `admins_id` int(10) DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `same_as_client` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address1` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address2` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `country_other` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `full_address_other` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timesheet_able_to_approve` varchar(10) COLLATE utf8_unicode_ci DEFAULT 'No',
  `timesheet_view_only` varchar(10) COLLATE utf8_unicode_ci DEFAULT 'Yes',
  `receives_copy_invoice` varchar(10) COLLATE utf8_unicode_ci DEFAULT 'No',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `contacts` */

insert  into `contacts`(`id`,`user_id`,`clients_id`,`account_managers_id`,`admins_id`,`status`,`first_name`,`last_name`,`client_name`,`email`,`phone`,`same_as_client`,`country`,`address1`,`address2`,`address`,`city`,`state`,`zip`,`created_at`,`updated_at`,`deleted_at`,`country_other`,`full_address_other`,`timesheet_able_to_approve`,`timesheet_view_only`,`receives_copy_invoice`) values 
(2,31,18,1,1,'active','Joshoa','Watson',NULL,'josh@ironrock.com','953-456-7763','no','US','543 AVE Houes 4',NULL,NULL,'Lake','FL','43452','2019-03-07 04:40:06',NULL,NULL,NULL,NULL,'No','No','No'),
(7,33,0,1,1,'active','Megan','White',NULL,'megan@ironrock.com','3242632234','yes','US','34hd','345',NULL,'ert','et','34534','2019-03-07 04:40:06',NULL,NULL,NULL,NULL,'Yes','Yes','Yes'),
(8,34,16,1,1,'active','sfdfsdfsd','sdfsdfsd',NULL,'sdfsdf@gffdg.com','234324324','yes','US','1243 ave',NULL,NULL,'LK','FL','432345','2019-03-06 13:36:13',NULL,'2019-03-05 20:25:53',NULL,NULL,'No','Yes','No'),
(9,3,16,1,1,'active','Main','Contact',NULL,'contact@brightdrop.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2019-03-07 05:14:48',NULL,NULL,NULL,NULL,'Yes','Yes','Yes');

/*Table structure for table `global` */

DROP TABLE IF EXISTS `global`;

CREATE TABLE `global` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `php_usd` float(8,2) DEFAULT NULL,
  `mxn_usd` float(8,2) DEFAULT NULL,
  `company_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `values` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `global` */

/*Table structure for table `holiday_default` */

DROP TABLE IF EXISTS `holiday_default`;

CREATE TABLE `holiday_default` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admins_id` int(10) DEFAULT NULL,
  `holiday_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `holiday_date` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `year` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `holiday_default` */

insert  into `holiday_default`(`id`,`admins_id`,`holiday_name`,`holiday_date`,`created_at`,`updated_at`,`deleted_at`,`year`,`status`) values 
(5,1,'New Years Day','2019-01-01','2019-03-03 15:04:28',NULL,NULL,'2019',NULL),
(6,1,'New Years day','2020-01-01','2019-03-03 13:57:49',NULL,NULL,'2020',NULL),
(8,1,'Good Friday','2020-04-10','2019-03-03 15:17:17',NULL,NULL,'2020',NULL),
(9,1,'Good Friday','2019-04-19','2019-03-03 15:16:54',NULL,NULL,'2019',NULL),
(11,1,'US Memorial Day','2019-05-25','2019-03-03 15:19:01',NULL,NULL,'2019',NULL),
(12,1,'US Memorial Day','2020-05-25','2019-03-03 15:19:26',NULL,NULL,'2020',NULL),
(13,1,'US Independence Day','2019-07-04','2019-03-03 15:21:39',NULL,NULL,'2019',NULL),
(14,1,'US Independence Day','2020-07-03','2019-03-03 15:23:25',NULL,NULL,'2020',NULL);

/*Table structure for table `holiday_schedule` */

DROP TABLE IF EXISTS `holiday_schedule`;

CREATE TABLE `holiday_schedule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `clients_id` int(10) DEFAULT NULL,
  `holiday_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `holiday_date` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `year` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `holiday_schedule` */

insert  into `holiday_schedule`(`id`,`clients_id`,`holiday_name`,`holiday_date`,`created_at`,`updated_at`,`deleted_at`,`year`,`status`) values 
(11,19,'New Years Day','2019-01-01','2019-03-03 18:42:37',NULL,NULL,'2019',NULL),
(12,19,'Good Friday','2019-03-20','2019-03-04 05:19:18',NULL,NULL,'2019',NULL),
(13,19,'US Memorial Day','2019-05-25','2019-03-03 18:42:38',NULL,NULL,'2019',NULL),
(14,19,'US Independence Day','2019-07-04','2019-03-03 18:42:39',NULL,NULL,'2019',NULL),
(15,19,'US Labor Day','2019-09-02','2019-03-03 18:42:43',NULL,NULL,'2019',NULL),
(16,19,'New Years day','2020-01-01','2019-03-03 18:42:48',NULL,NULL,'2020',NULL),
(17,19,'Good Friday','2020-04-10','2019-03-03 18:42:49',NULL,NULL,'2020',NULL),
(18,19,'US Memorial Day','2020-05-25','2019-03-03 18:42:50',NULL,NULL,'2020',NULL),
(19,19,'US Independence Day','2020-07-03','2019-03-03 18:42:51',NULL,NULL,'2020',NULL),
(57,20,'New Years day','2019-03-01','2019-03-06 17:04:16',NULL,NULL,'2019',NULL),
(58,20,'New Years day','2019-03-01','2019-03-06 17:05:01',NULL,NULL,'2019',NULL),
(59,20,'Valendtine day','2019-03-21','2019-03-06 17:05:01',NULL,NULL,'2019',NULL),
(125,16,'New years day','2019-01-01','2019-03-07 05:14:48',NULL,NULL,'2019',NULL);

/*Table structure for table `invoices` */

DROP TABLE IF EXISTS `invoices`;

CREATE TABLE `invoices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_queue` date DEFAULT NULL,
  `payment_method` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `internal_processor` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_id` int(10) DEFAULT NULL,
  `worker_id` int(10) DEFAULT NULL,
  `payroll_id` int(10) DEFAULT NULL,
  `billing_cycle_end_date` date DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `currency` enum('php','mxn','usd') COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoices` text COLLATE utf8_unicode_ci,
  `comments` text COLLATE utf8_unicode_ci,
  `status` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `invoices` */

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `payments` */

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) DEFAULT NULL,
  `worker_id` int(10) DEFAULT NULL,
  `email_address` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_queue` date DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `currency` enum('php','mxn','usd') COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_summary` text COLLATE utf8_unicode_ci,
  `comments` text COLLATE utf8_unicode_ci,
  `payment_method` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payroll_id` int(10) DEFAULT NULL,
  `date_paid` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `payments` */

/*Table structure for table `payroll_managers` */

DROP TABLE IF EXISTS `payroll_managers`;

CREATE TABLE `payroll_managers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address1` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address2` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `payroll_managers` */

insert  into `payroll_managers`(`id`,`user_id`,`status`,`first_name`,`last_name`,`email`,`address1`,`address2`,`city`,`state`,`zip`,`created_at`,`updated_at`,`deleted_at`) values 
(1,4,'active','Payroll','Manager','payroll@brightdrop.com','324 AVE',NULL,'LK','FL','324235',NULL,NULL,NULL);

/*Table structure for table `pto` */

DROP TABLE IF EXISTS `pto`;

CREATE TABLE `pto` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `workers_id` int(10) DEFAULT NULL,
  `clients_id` int(10) DEFAULT NULL,
  `date_pto` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_hours` int(20) DEFAULT NULL,
  `reason` text COLLATE utf8_unicode_ci,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `pto` */

insert  into `pto`(`id`,`workers_id`,`clients_id`,`date_pto`,`total_hours`,`reason`,`status`,`created_at`,`updated_at`,`deleted_at`) values 
(10,2,16,'2019-03-14',5,'Take Children','Approved','2019-03-04 02:03:03',NULL,NULL),
(11,2,18,'2019-03-28',8,'sick','Approved','2019-03-04 02:02:58',NULL,NULL),
(12,2,19,'2019-03-21',5,'birthday','Approved','2019-03-04 02:02:59',NULL,NULL),
(13,2,17,'2019-03-30',7,'Rest','Approved','2019-03-04 02:02:52',NULL,NULL),
(14,2,17,'2019-03-20',10,NULL,'Approved','2019-03-04 02:03:01',NULL,NULL),
(15,16,18,'2019-03-16',6,NULL,'Approved','2019-03-04 02:12:17',NULL,NULL),
(16,2,19,'2019-03-13',5,'persoal issue','Approved','2019-03-04 03:22:49',NULL,NULL),
(17,2,17,'2019-03-05',4,NULL,'Approved','2019-03-04 03:22:51',NULL,NULL),
(18,2,17,'2019-03-23',3,NULL,'Approved','2019-03-04 03:22:47',NULL,NULL),
(19,16,18,'2019-03-05',2,NULL,'Pending Approval - BrightDrop','2019-03-04 16:18:01',NULL,NULL);

/*Table structure for table `reimbursements` */

DROP TABLE IF EXISTS `reimbursements`;

CREATE TABLE `reimbursements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `workers_id` int(10) DEFAULT NULL,
  `clients_id` int(10) DEFAULT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `currency_type` enum('php','mxn','usd') COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_type` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `internet_service_provider` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approve_status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `statement_date` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `statement_included` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `copy_statement_file` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `additional_notes` text COLLATE utf8_unicode_ci,
  `bill_to` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `additional_notes_account` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `reimbursements` */

insert  into `reimbursements`(`id`,`workers_id`,`clients_id`,`date`,`type`,`amount`,`currency_type`,`other_type`,`internet_service_provider`,`status`,`approve_status`,`created_at`,`updated_at`,`deleted_at`,`statement_date`,`statement_included`,`copy_statement_file`,`additional_notes`,`bill_to`,`payment_method`,`additional_notes_account`) values 
(1,2,16,'2019-03-05','Computer',3500.00,'php',NULL,NULL,'Approved',NULL,'2019-03-05 07:29:42',NULL,NULL,NULL,'yes',NULL,'thanks','BrightDrop','Veem','I agree'),
(2,2,17,'2019-03-05','Computer Repair',2000.00,'php',NULL,NULL,'Declined',NULL,'2019-03-05 07:38:17',NULL,NULL,NULL,'yes',NULL,NULL,NULL,NULL,'Too much'),
(3,2,19,'2019-03-05','Computer',5000.00,'php',NULL,NULL,'Declined',NULL,'2019-03-05 08:03:10',NULL,NULL,NULL,'yes',NULL,'Furthure working',NULL,NULL,NULL),
(4,2,16,'2019-03-05','Computer',300.00,'php',NULL,NULL,'Approved',NULL,'2019-03-05 07:29:42',NULL,NULL,NULL,'yes',NULL,NULL,'BrightDrop','Veem','I agree'),
(5,2,16,'2019-03-29','Internet - Backup',300.00,'usd',NULL,'micro lpt','Approved',NULL,'2019-03-05 07:29:42',NULL,NULL,'2019-03-14','yes',NULL,'ok','BrightDrop','Veem','I agree'),
(6,2,16,'2019-03-13','Computer Repair',545.00,'php',NULL,NULL,'Approved',NULL,'2019-03-05 07:29:42',NULL,NULL,NULL,'yes',NULL,NULL,'BrightDrop','Veem','I agree');

/*Table structure for table `role_user` */

DROP TABLE IF EXISTS `role_user`;

CREATE TABLE `role_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_user` */

insert  into `role_user`(`id`,`role_id`,`user_id`,`created_at`,`updated_at`) values 
(1,5,1,'2018-11-01 18:22:00','2018-11-01 18:23:00'),
(2,2,2,'2018-11-01 17:39:00','2018-11-01 17:39:00'),
(3,3,3,'2019-02-26 06:26:03','2019-02-26 06:26:06'),
(4,4,4,'2018-11-01 18:22:00','2018-11-01 18:23:00'),
(5,1,5,'2018-11-01 18:22:00','2018-11-01 18:23:00'),
(8,5,25,'2019-02-26 13:51:33','2019-02-26 13:51:33'),
(9,5,26,'2019-02-27 19:05:01','2019-02-27 19:05:01'),
(10,5,27,'2019-02-27 19:09:17','2019-02-27 19:09:17'),
(11,5,28,'2019-02-27 21:50:57','2019-02-27 21:50:57'),
(12,3,30,'2019-03-05 17:41:42','2019-03-05 17:41:42'),
(13,3,31,'2019-03-05 17:47:41','2019-03-05 17:47:41'),
(14,3,33,'2019-03-05 20:25:15','2019-03-05 20:25:15');

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`created_at`,`updated_at`,`name`,`description`) values 
(1,'2018-11-01 05:30:00','2018-11-01 05:30:00','Admin','Admin'),
(2,'2018-11-01 07:30:00','2018-11-01 07:30:00','Account','Account Manager'),
(3,'2018-11-01 09:30:00','2018-11-01 09:30:00','Contact','Contact'),
(4,'2018-11-01 10:30:00','2018-11-01 10:30:00','Payroll','Payroll Manager'),
(5,'2019-02-21 03:39:29','2019-02-18 03:39:24','Worker','Worker');

/*Table structure for table `time_cards` */

DROP TABLE IF EXISTS `time_cards`;

CREATE TABLE `time_cards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `clients_id` int(10) DEFAULT NULL,
  `workers_id` int(10) DEFAULT NULL,
  `timesheets_ids` text COLLATE utf8_unicode_ci,
  `status` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_date` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `end_date` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `question` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_work_time` float(10,2) DEFAULT NULL,
  `total_lunch_time` float(10,2) DEFAULT NULL,
  `total_break_time` float(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `time_cards` */

insert  into `time_cards`(`id`,`clients_id`,`workers_id`,`timesheets_ids`,`status`,`start_date`,`end_date`,`notes`,`question`,`total_work_time`,`total_lunch_time`,`total_break_time`,`created_at`,`updated_at`,`deleted_at`) values 
(1,17,2,'86,87,88,89,90,91,92,93,94,95,96,97,98,99','approved','2019-03-13','2019-03-26',NULL,NULL,104.50,4.50,4.50,NULL,NULL,NULL),
(2,18,2,'100,101,102,103,104,105,106,107,108,109,110,111,112,113','declined','2019-03-06','2019-03-19',NULL,NULL,112.00,6.00,6.00,NULL,NULL,NULL),
(3,17,16,'114,115,116,117,118,119,120,121,122,123,124,125,126,127','needs_approval','2019-03-13','2019-03-26',NULL,NULL,112.00,3.00,3.00,NULL,NULL,NULL),
(4,18,16,'128,129,130,131,132,133,134,135,136,137,138,139,140,141','approved','2019-03-06','2019-03-19',NULL,NULL,78.00,3.00,5.00,NULL,NULL,NULL),
(5,19,2,'142,143,144,145,146,147,148,149,150,151,152,153,154,155','approved','2019-03-14','2019-03-27',NULL,NULL,65.00,2.00,5.00,NULL,NULL,NULL),
(6,17,17,'170,171,172,173,174,175,176,177,178,179,180,181,182,183','approved','2019-03-13','2019-03-26',NULL,NULL,112.00,6.00,6.00,NULL,NULL,NULL),
(7,19,17,'198,199,200,201,202,203,204,205,206,207,208,209,210,211','approved','2019-03-14','2019-03-27',NULL,NULL,78.00,2.00,0.00,NULL,NULL,NULL);

/*Table structure for table `time_sheets` */

DROP TABLE IF EXISTS `time_sheets`;

CREATE TABLE `time_sheets` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `clients_id` int(10) DEFAULT NULL,
  `workers_id` int(10) DEFAULT NULL,
  `day` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL,
  `work_time_hours` int(10) DEFAULT '0',
  `lunch_time_hours` int(10) DEFAULT '0',
  `break_time_hours` int(10) DEFAULT '0',
  `notes` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `questions` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `work_time_minutes` int(10) DEFAULT '0',
  `lunch_time_minutes` int(10) DEFAULT '0',
  `break_time_minutes` int(10) DEFAULT '0',
  `time_cards_id` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=226 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `time_sheets` */

insert  into `time_sheets`(`id`,`clients_id`,`workers_id`,`day`,`date`,`work_time_hours`,`lunch_time_hours`,`break_time_hours`,`notes`,`questions`,`status`,`link`,`work_time_minutes`,`lunch_time_minutes`,`break_time_minutes`,`time_cards_id`,`created_at`,`updated_at`,`deleted_at`) values 
(86,17,2,'Wednesday','2019-03-13',8,0,0,NULL,NULL,'approved',NULL,30,30,0,1,NULL,NULL,NULL),
(87,17,2,'Thursday','2019-03-14',8,0,0,NULL,NULL,'approved',NULL,30,30,30,1,NULL,NULL,NULL),
(88,17,2,'Friday','2019-03-15',8,0,0,NULL,NULL,'approved',NULL,30,30,0,1,NULL,NULL,NULL),
(89,17,2,'Saturday','2019-03-16',8,0,0,NULL,NULL,'approved',NULL,30,30,0,1,NULL,NULL,NULL),
(90,17,2,'Sunday','2019-03-17',8,0,0,NULL,NULL,'approved',NULL,0,0,30,1,NULL,NULL,NULL),
(91,17,2,'Monday','2019-03-18',8,0,0,NULL,NULL,'approved',NULL,0,30,0,1,NULL,NULL,NULL),
(92,17,2,'Tuesday','2019-03-19',8,0,0,NULL,NULL,'approved',NULL,0,0,20,1,NULL,NULL,NULL),
(93,17,2,'Wednesday','2019-03-20',0,0,0,NULL,NULL,'approved',NULL,0,0,20,1,NULL,NULL,NULL),
(94,17,2,'Thursday','2019-03-21',8,0,0,NULL,NULL,'approved',NULL,0,0,20,1,NULL,NULL,NULL),
(95,17,2,'Friday','2019-03-22',5,0,0,NULL,NULL,'approved',NULL,0,0,30,1,NULL,NULL,NULL),
(96,17,2,'Saturday','2019-03-23',8,0,0,NULL,NULL,'approved',NULL,0,30,30,1,NULL,NULL,NULL),
(97,17,2,'Sunday','2019-03-24',8,0,0,NULL,NULL,'approved',NULL,30,30,30,1,NULL,NULL,NULL),
(98,17,2,'Monday','2019-03-25',8,0,0,NULL,NULL,'approved',NULL,30,30,30,1,NULL,NULL,NULL),
(99,17,2,'Tuesday','2019-03-26',8,0,0,NULL,NULL,'approved',NULL,30,30,30,1,NULL,NULL,NULL),
(100,18,2,'Wednesday','2019-03-06',8,1,0,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(101,18,2,'Thursday','2019-03-07',8,1,0,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(102,18,2,'Friday','2019-03-08',8,1,0,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(103,18,2,'Saturday','2019-03-09',8,1,0,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(104,18,2,'Sunday','2019-03-10',8,1,0,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(105,18,2,'Monday','2019-03-11',8,1,0,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(106,18,2,'Tuesday','2019-03-12',8,0,0,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(107,18,2,'Wednesday','2019-03-13',8,0,0,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(108,18,2,'Thursday','2019-03-14',8,0,1,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(109,18,2,'Friday','2019-03-15',8,0,1,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(110,18,2,'Saturday','2019-03-16',8,0,1,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(111,18,2,'Sunday','2019-03-17',8,0,1,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(112,18,2,'Monday','2019-03-18',8,0,1,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(113,18,2,'Tuesday','2019-03-19',8,0,1,NULL,NULL,'declined',NULL,0,0,0,2,NULL,NULL,NULL),
(114,17,16,'Wednesday','2019-03-13',8,0,1,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(115,17,16,'Thursday','2019-03-14',8,0,1,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(116,17,16,'Friday','2019-03-15',8,0,1,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(117,17,16,'Saturday','2019-03-16',8,1,0,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(118,17,16,'Sunday','2019-03-17',8,1,0,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(119,17,16,'Monday','2019-03-18',8,1,0,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(120,17,16,'Tuesday','2019-03-19',8,0,0,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(121,17,16,'Wednesday','2019-03-20',8,0,0,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(122,17,16,'Thursday','2019-03-21',8,0,0,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(123,17,16,'Friday','2019-03-22',8,0,0,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(124,17,16,'Saturday','2019-03-23',8,0,0,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(125,17,16,'Sunday','2019-03-24',8,0,0,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(126,17,16,'Monday','2019-03-25',8,0,0,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(127,17,16,'Tuesday','2019-03-26',8,0,0,NULL,NULL,'needs_approval',NULL,0,0,0,3,NULL,NULL,NULL),
(128,18,16,'Wednesday','2019-03-06',6,0,1,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(129,18,16,'Thursday','2019-03-07',6,0,1,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(130,18,16,'Friday','2019-03-08',6,0,1,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(131,18,16,'Saturday','2019-03-09',6,0,1,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(132,18,16,'Sunday','2019-03-10',6,0,1,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(133,18,16,'Monday','2019-03-11',6,0,0,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(134,18,16,'Tuesday','2019-03-12',6,0,0,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(135,18,16,'Wednesday','2019-03-13',6,0,0,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(136,18,16,'Thursday','2019-03-14',6,1,0,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(137,18,16,'Friday','2019-03-15',0,1,0,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(138,18,16,'Saturday','2019-03-16',6,1,0,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(139,18,16,'Sunday','2019-03-17',6,0,0,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(140,18,16,'Monday','2019-03-18',6,0,0,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(141,18,16,'Tuesday','2019-03-19',6,0,0,NULL,NULL,'approved',NULL,0,0,0,4,NULL,NULL,NULL),
(142,19,2,'Thursday','2019-03-14',5,0,1,NULL,NULL,'approved',NULL,0,0,0,5,NULL,NULL,NULL),
(143,19,2,'Friday','2019-03-15',5,0,1,NULL,NULL,'approved',NULL,0,0,0,5,NULL,NULL,NULL),
(144,19,2,'Saturday','2019-03-16',5,0,1,NULL,NULL,'approved',NULL,0,0,0,5,NULL,NULL,NULL),
(145,19,2,'Sunday','2019-03-17',5,0,1,NULL,NULL,'approved',NULL,0,0,0,5,NULL,NULL,NULL),
(146,19,2,'Monday','2019-03-18',5,0,1,NULL,NULL,'approved',NULL,0,0,0,5,NULL,NULL,NULL),
(147,19,2,'Tuesday','2019-03-19',5,0,0,NULL,NULL,'approved',NULL,0,0,0,5,NULL,NULL,NULL),
(148,19,2,'Wednesday','2019-03-20',0,0,0,NULL,NULL,'approved',NULL,0,0,0,5,NULL,NULL,NULL),
(149,19,2,'Thursday','2019-03-21',5,0,0,NULL,NULL,'approved',NULL,0,0,0,5,NULL,NULL,NULL),
(150,19,2,'Friday','2019-03-22',5,0,0,NULL,NULL,'approved',NULL,0,0,0,5,NULL,NULL,NULL),
(151,19,2,'Saturday','2019-03-23',5,0,0,NULL,NULL,'approved',NULL,0,0,0,5,NULL,NULL,NULL),
(152,19,2,'Sunday','2019-03-24',5,0,0,NULL,NULL,'approved',NULL,0,30,0,5,NULL,NULL,NULL),
(153,19,2,'Monday','2019-03-25',5,0,0,NULL,NULL,'approved',NULL,0,30,0,5,NULL,NULL,NULL),
(154,19,2,'Tuesday','2019-03-26',5,0,0,NULL,NULL,'approved',NULL,0,30,0,5,NULL,NULL,NULL),
(155,19,2,'Wednesday','2019-03-27',5,0,0,NULL,NULL,'approved',NULL,0,30,0,5,NULL,NULL,NULL),
(156,16,2,'Monday','2019-02-11',1,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(157,16,2,'Tuesday','2019-02-12',2,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(158,16,2,'Wednesday','2019-02-13',3,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(159,16,2,'Thursday','2019-02-14',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(160,16,2,'Friday','2019-02-15',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(161,16,2,'Saturday','2019-02-16',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(162,16,2,'Sunday','2019-02-17',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(163,16,2,'Monday','2019-02-18',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(164,16,2,'Tuesday','2019-02-19',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(165,16,2,'Wednesday','2019-02-20',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(166,16,2,'Thursday','2019-02-21',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(167,16,2,'Friday','2019-02-22',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(168,16,2,'Saturday','2019-02-23',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(169,16,2,'Sunday','2019-02-24',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(170,17,17,'Wednesday','2019-03-13',8,1,0,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(171,17,17,'Thursday','2019-03-14',8,1,0,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(172,17,17,'Friday','2019-03-15',8,1,0,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(173,17,17,'Saturday','2019-03-16',8,1,0,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(174,17,17,'Sunday','2019-03-17',8,1,0,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(175,17,17,'Monday','2019-03-18',8,1,0,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(176,17,17,'Tuesday','2019-03-19',8,0,0,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(177,17,17,'Wednesday','2019-03-20',8,0,0,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(178,17,17,'Thursday','2019-03-21',8,0,1,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(179,17,17,'Friday','2019-03-22',8,0,1,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(180,17,17,'Saturday','2019-03-23',8,0,1,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(181,17,17,'Sunday','2019-03-24',8,0,1,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(182,17,17,'Monday','2019-03-25',8,0,1,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(183,17,17,'Tuesday','2019-03-26',8,0,1,NULL,NULL,'approved',NULL,0,0,0,6,NULL,NULL,NULL),
(184,18,17,'Wednesday','2019-03-06',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(185,18,17,'Thursday','2019-03-07',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(186,18,17,'Friday','2019-03-08',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(187,18,17,'Saturday','2019-03-09',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(188,18,17,'Sunday','2019-03-10',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(189,18,17,'Monday','2019-03-11',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(190,18,17,'Tuesday','2019-03-12',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(191,18,17,'Wednesday','2019-03-13',5,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(192,18,17,'Thursday','2019-03-14',5,0,1,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(193,18,17,'Friday','2019-03-15',5,0,1,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(194,18,17,'Saturday','2019-03-16',5,0,1,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(195,18,17,'Sunday','2019-03-17',5,0,1,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(196,18,17,'Monday','2019-03-18',5,0,1,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(197,18,17,'Tuesday','2019-03-19',5,0,1,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(198,19,17,'Thursday','2019-03-14',6,0,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(199,19,17,'Friday','2019-03-15',6,0,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(200,19,17,'Saturday','2019-03-16',6,0,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(201,19,17,'Sunday','2019-03-17',6,0,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(202,19,17,'Monday','2019-03-18',6,0,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(203,19,17,'Tuesday','2019-03-19',6,0,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(204,19,17,'Wednesday','2019-03-20',0,0,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(205,19,17,'Thursday','2019-03-21',6,0,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(206,19,17,'Friday','2019-03-22',6,0,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(207,19,17,'Saturday','2019-03-23',6,0,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(208,19,17,'Sunday','2019-03-24',6,1,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(209,19,17,'Monday','2019-03-25',6,0,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(210,19,17,'Tuesday','2019-03-26',6,1,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(211,19,17,'Wednesday','2019-03-27',6,0,0,NULL,NULL,'approved',NULL,0,0,0,7,NULL,NULL,NULL),
(212,16,17,'Monday','2019-02-11',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(213,16,17,'Tuesday','2019-02-12',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(214,16,17,'Wednesday','2019-02-13',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(215,16,17,'Thursday','2019-02-14',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(216,16,17,'Friday','2019-02-15',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(217,16,17,'Saturday','2019-02-16',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(218,16,17,'Sunday','2019-02-17',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(219,16,17,'Monday','2019-02-18',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(220,16,17,'Tuesday','2019-02-19',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(221,16,17,'Wednesday','2019-02-20',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(222,16,17,'Thursday','2019-02-21',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(223,16,17,'Friday','2019-02-22',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(224,16,17,'Saturday','2019-02-23',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL),
(225,16,17,'Sunday','2019-02-24',4,0,0,NULL,NULL,'pending_worker',NULL,0,0,0,NULL,NULL,NULL,NULL);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payroll_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `worker_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_manager_id` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_token` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verified` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`client_id`,`status`,`token`,`password`,`remember_token`,`admin_id`,`payroll_id`,`worker_id`,`contact_id`,`account_manager_id`,`created_at`,`updated_at`,`email_token`,`verified`) values 
(1,'Worker','worker@brightdrop.com','002',1,'','$2y$10$FQpuUc/SuJUl7eKVE7wL5enV7HKR6gZhoXVxIECnyf41OtZ1LxN7C','BaWEHt4r153wtmSGUEBZBchGHGPkmdbEkiGmgOHorQPPTdF7SNi5Rp1F8oae',NULL,NULL,NULL,NULL,NULL,'2017-06-30 21:11:26','2019-02-26 13:37:33',NULL,NULL),
(2,'Account Manager','account@brightdrop.com','004',1,'','$2y$10$3MRZFn4UwqikSRyfW76s3.SmZuF5VMtusFPWz9QCMpM8rX1aNsjlO','awCkIUKzqpIIWUxrLSq8nJcqGiHf5cVjqMXOVFSgDTp12mSxVp8pFoTdmyl9',NULL,NULL,NULL,NULL,NULL,'2017-06-30 21:11:26','2018-11-14 21:41:38',NULL,NULL),
(3,'Contact','contact@brightdrop.com','005',1,'','$2y$10$3MRZFn4UwqikSRyfW76s3.SmZuF5VMtusFPWz9QCMpM8rX1aNsjlO','XAyfByJTyncve7HMI9x03BjFvYSUzVM3UzzNSJdjEpixAlL1VK0GR7N41P1i',NULL,NULL,NULL,NULL,NULL,'2017-06-30 21:08:36','2017-07-03 14:18:54',NULL,NULL),
(4,'Payroll Manager','payroll@brightdrop.com','006',1,'','$2y$10$3MRZFn4UwqikSRyfW76s3.SmZuF5VMtusFPWz9QCMpM8rX1aNsjlO','wyP2XqmkmbQDE3Q31BlbOqfP11ekv183jXKRceniRmKWOf8Y81RrSRjhUcts',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(5,'Administrator','admin@brightdrop.com','007',1,'','$2y$10$3MRZFn4UwqikSRyfW76s3.SmZuF5VMtusFPWz9QCMpM8rX1aNsjlO','514HHS2T6gNsKPJLYUq5KDn6sTQIin6SssF2S1SRwEspaItkNHSHL88sMMSK',NULL,NULL,NULL,NULL,NULL,'2017-10-13 21:59:32','2017-10-13 21:59:32',NULL,NULL),
(25,'Joe smith','joe@brightdrop.com','',1,'','$2y$10$Y5VuAXXW5evFDEXVZ5E2nOxyRveTHWOAQCo8IawiMSTelAkilq2ii','tQwhixaAQW7bU4dTkvKdEb4w6mrVDjd69PeVsliR0pFYPTVTWMdlfdA0D6Vv',NULL,NULL,NULL,NULL,NULL,'2019-02-26 13:51:33','2019-02-26 13:51:33',NULL,NULL),
(27,'Artem Voroy','artem@main.com',NULL,1,NULL,'$2y$10$fckt6DoLDic7soTg4YArcOmZmpCB54ISwS4JgtZJNXrCqCzdyN4eS','DWLhliKZMl8Ev7fv5Zs6I2tLtTQG3lTRAjKnrVtkp5a2eBRLQBAODmNb6udB',NULL,NULL,'16',NULL,NULL,'2019-02-27 19:09:17','2019-02-27 19:09:17',NULL,NULL),
(28,'Viktor Popov','viktor@main.com',NULL,1,NULL,'$2y$10$zlZtvLqkJYiMTFFBiHipm.ZyIrZrIiJViqVWvNnMIvRv0mQJkb166','gC1qLOT6OdubU02JSvKRzoJgdUBoxZoyRVrzLffZzulrZxEKkMCkzfH5UGj2',NULL,NULL,'17',NULL,NULL,'2019-02-27 21:50:57','2019-02-27 21:50:57',NULL,NULL),
(31,'Joshoa Watson','josh@ironrock.com',NULL,1,NULL,'$2y$10$VRlJ0RvOwIRRXxW3tPeFruBGofFYR5N2fNJ6tJUlhB0cooBsbwODm','tO12wxMT6cOt87NrsTMYqbmR3ZdwWKn049zXbWxT8vdOVzFH3qzsixt8Z86s',NULL,NULL,NULL,NULL,NULL,'2019-03-05 17:47:41','2019-03-05 21:13:03',NULL,NULL),
(33,'Megan White','megan@ironrock.com',NULL,1,NULL,'$2y$10$AXZEDPZuNnO0qw8IanBdzurH2/ccyH06COPmFOXOK2JtJWXshp2qq','Y9ChCKcpmNGY7XsPmd0Q4s5ij55RhDVSMuNeLzkcJ3YrknKbPMfwtaCzgNtn',NULL,NULL,NULL,NULL,NULL,'2019-03-05 20:25:15','2019-03-05 20:25:15',NULL,NULL);

/*Table structure for table `workers` */

DROP TABLE IF EXISTS `workers`;

CREATE TABLE `workers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `clients_ids` varchar(2000) COLLATE utf8_unicode_ci DEFAULT '0',
  `account_managers_id` int(10) DEFAULT NULL,
  `status` enum('new_candidate','disqualfied','pre_candidate','available_hired','not_available_hired') COLLATE utf8_unicode_ci DEFAULT 'new_candidate',
  `first_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `legal_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_main` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_veem` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skype` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `philippines_region` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `candidate_account_manager_id` int(10) DEFAULT NULL,
  `fulltime_compensation_amount` float(20,3) DEFAULT NULL,
  `fulltime_compensation_currency` enum('mxn','php','usd') COLLATE utf8_unicode_ci DEFAULT 'mxn',
  `available_hours` float(20,2) DEFAULT NULL,
  `outside_brightdrop` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT NULL,
  `hours_outside_perweek` int(10) DEFAULT NULL,
  `current_nonbrightdrop_hours` int(10) DEFAULT NULL,
  `target_client1` int(10) DEFAULT NULL,
  `available_start_date` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `video_link` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `writing_sample` text COLLATE utf8_unicode_ci,
  `goverment_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `NBI` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `worker_source` enum('brightdrop_support_mailbox','internal_recruitment_manager','internal_other','onlinejob.ph','worker referral','unknown','other') COLLATE utf8_unicode_ci DEFAULT 'brightdrop_support_mailbox',
  `english_skills` text COLLATE utf8_unicode_ci,
  `skills` text COLLATE utf8_unicode_ci,
  `software_knowledge` text COLLATE utf8_unicode_ci,
  `internet_connection` text COLLATE utf8_unicode_ci,
  `technical_computer` text COLLATE utf8_unicode_ci,
  `special_candiate_notes` text COLLATE utf8_unicode_ci,
  `work_schedule` text COLLATE utf8_unicode_ci,
  `backup_plan` text COLLATE utf8_unicode_ci,
  `emergency_contacts` text COLLATE utf8_unicode_ci,
  `payments` text COLLATE utf8_unicode_ci,
  `pto_summary` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `disqualifier_explain` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `target_client2` int(10) DEFAULT NULL,
  `target_client3` int(10) DEFAULT NULL,
  `video_file` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `resume_file` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `internal_recruitment_manager` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `internal_other_employee` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Onlinelinejobs_profilelink` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `worker_referral` int(10) DEFAULT NULL,
  `worksource_other` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pppppppppppppp` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admins_id` int(10) DEFAULT NULL,
  `english_verbal` int(2) DEFAULT NULL,
  `english_written` int(2) DEFAULT NULL,
  `english_verbal_note` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `english_written_note` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `workers` */

insert  into `workers`(`id`,`user_id`,`clients_ids`,`account_managers_id`,`status`,`first_name`,`last_name`,`legal_name`,`email_main`,`email_veem`,`skype`,`phone`,`country`,`philippines_region`,`address`,`birthday`,`gender`,`candidate_account_manager_id`,`fulltime_compensation_amount`,`fulltime_compensation_currency`,`available_hours`,`outside_brightdrop`,`hours_outside_perweek`,`current_nonbrightdrop_hours`,`target_client1`,`available_start_date`,`video_link`,`writing_sample`,`goverment_id`,`NBI`,`worker_source`,`english_skills`,`skills`,`software_knowledge`,`internet_connection`,`technical_computer`,`special_candiate_notes`,`work_schedule`,`backup_plan`,`emergency_contacts`,`payments`,`pto_summary`,`created_at`,`updated_at`,`deleted_at`,`disqualifier_explain`,`target_client2`,`target_client3`,`video_file`,`resume_file`,`internal_recruitment_manager`,`internal_other_employee`,`Onlinelinejobs_profilelink`,`worker_referral`,`worksource_other`,`pppppppppppppp`,`admins_id`,`english_verbal`,`english_written`,`english_verbal_note`,`english_written_note`) values 
(1,1,'0,17,18,19,20,16',1,'available_hired','MAin','WOker','Main Worker','Worker@brightdrop.com','Worker@brightdrop.com','ertret','9567345353','US','FL','LAKE','1991-03-21','male',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2019-03-07 05:14:48',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),
(2,25,'0,17,18,19,20,16',1,'available_hired','Joe','Smith','Joe Smith','Joe@brightdrop.com','Joe@brightdrop.com','fggsdg','4352664346','US','FL','LAKE','1991-02-21','male',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2019-03-07 05:14:48',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),
(16,27,'0,17,18,19,16',1,'new_candidate','artem','voroy','artem','artem@main.com','artem@main.com','ewrweg','462346346','Philippines','Mindanao','325 et3w 345t34','2019-02-19','Male',0,NULL,'mxn',0.00,'no',0,NULL,0,NULL,NULL,NULL,NULL,NULL,'brightdrop_support_mailbox',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2019-03-07 05:14:48',NULL,NULL,'fdg',0,0,NULL,NULL,'0','0',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL),
(17,28,'0,17,18,19,16',1,'new_candidate','Viktor','Popov','Viktor Popov','viktor@main.com','viktor@main.com','viktorkp','964535345','Mexico','none','123 ave lake','2019-02-13','Male',0,NULL,'mxn',0.00,'no',0,NULL,1,'2019-02-06',NULL,NULL,NULL,NULL,'brightdrop_support_mailbox',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2019-03-07 05:14:48',NULL,NULL,NULL,1,1,NULL,NULL,'0','0',NULL,1,NULL,NULL,1,NULL,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
