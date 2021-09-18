/*
SQLyog Community v13.1.6 (64 bit)
MySQL - 5.6.50-log : Database - scheduling
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`scheduling` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `scheduling`;

/*Table structure for table `cys` */

DROP TABLE IF EXISTS `cys`;

CREATE TABLE `cys` (
  `cys_id` int(11) NOT NULL AUTO_INCREMENT,
  `cys` varchar(20) NOT NULL,
  PRIMARY KEY (`cys_id`),
  UNIQUE KEY `cys` (`cys`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `cys` */

insert  into `cys`(`cys_id`,`cys`) values 
(1,'BSED 1A'),
(2,'BEED 1A'),
(3,'BEED 1B');

/*Table structure for table `dept` */

DROP TABLE IF EXISTS `dept`;

CREATE TABLE `dept` (
  `dept_id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_code` varchar(10) NOT NULL,
  `dept_name` varchar(100) NOT NULL,
  PRIMARY KEY (`dept_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `dept` */

insert  into `dept`(`dept_id`,`dept_code`,`dept_name`) values 
(8,'CAS','School of Arts and Sciences'),
(7,'COED ','College of Education');

/*Table structure for table `designation` */

DROP TABLE IF EXISTS `designation`;

CREATE TABLE `designation` (
  `designation_id` int(11) NOT NULL AUTO_INCREMENT,
  `designation_name` varchar(100) NOT NULL,
  PRIMARY KEY (`designation_id`)
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;

/*Data for the table `designation` */

insert  into `designation`(`designation_id`,`designation_name`) values 
(67,'Faculty'),
(66,'Dean');

/*Table structure for table `exam_sched` */

DROP TABLE IF EXISTS `exam_sched`;

CREATE TABLE `exam_sched` (
  `sched_id` int(11) NOT NULL AUTO_INCREMENT,
  `time_id` int(1) NOT NULL,
  `day` varchar(50) NOT NULL,
  `member_id` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `cys` varchar(15) NOT NULL,
  `room` varchar(15) NOT NULL,
  `remarks` varchar(50) NOT NULL,
  `settings_id` int(11) NOT NULL,
  `cys1` varchar(10) NOT NULL,
  `term` varchar(10) NOT NULL,
  `encoded_by` int(11) NOT NULL,
  PRIMARY KEY (`sched_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `exam_sched` */

/*Table structure for table `member` */

DROP TABLE IF EXISTS `member`;

CREATE TABLE `member` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_last` varchar(30) NOT NULL,
  `member_first` varchar(30) NOT NULL,
  `member_rank` varchar(50) NOT NULL,
  `member_salut` varchar(30) NOT NULL,
  `dept_code` varchar(10) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `program_code` varchar(10) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM AUTO_INCREMENT=182 DEFAULT CHARSET=latin1;

/*Data for the table `member` */

insert  into `member`(`member_id`,`member_last`,`member_first`,`member_rank`,`member_salut`,`dept_code`,`designation_id`,`program_code`,`username`,`password`,`status`) values 
(27,'Admin','Admin','Assistant Professor I','Mrs','CIT',5,'','admin','admin','admin'),
(177,'Rizal','Jose','Assistant Professor I','Dr','CAS',66,'','josecas','rizal','admin');

/*Table structure for table `program` */

DROP TABLE IF EXISTS `program`;

CREATE TABLE `program` (
  `prog_id` int(11) NOT NULL AUTO_INCREMENT,
  `prog_code` varchar(10) NOT NULL,
  `prog_title` varchar(50) NOT NULL,
  PRIMARY KEY (`prog_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Data for the table `program` */

insert  into `program`(`prog_id`,`prog_code`,`prog_title`) values 
(6,'BSPsych ','Bachelor of Science in Psychology'),
(13,'BSED','Bachelor of Science in Secondary Education'),
(14,'BEED','Bachelor of Science in Elementary Education');

/*Table structure for table `rank` */

DROP TABLE IF EXISTS `rank`;

CREATE TABLE `rank` (
  `rank_id` int(11) NOT NULL AUTO_INCREMENT,
  `rank` varchar(30) NOT NULL,
  PRIMARY KEY (`rank_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

/*Data for the table `rank` */

insert  into `rank`(`rank_id`,`rank`) values 
(1,'Instructor I'),
(4,'Instructor II'),
(6,'Assistant Professor I'),
(5,'Instructor III'),
(7,'Assistant Professor II'),
(8,'Assistant Professor III'),
(9,'Assistant Professor IV'),
(10,'Associate Professor I'),
(11,'Associate Professor II'),
(12,'Associate Professor III'),
(13,'Associate Professor IV'),
(14,'Professor I'),
(15,'Professor II'),
(16,'Professor III'),
(17,'Professor IV'),
(18,'College Professor '),
(19,'University Professor ');

/*Table structure for table `room` */

DROP TABLE IF EXISTS `room`;

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room` varchar(15) NOT NULL,
  PRIMARY KEY (`room_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `room` */

insert  into `room`(`room_id`,`room`) values 
(1,'101'),
(2,'102'),
(3,'103'),
(4,'104');

/*Table structure for table `salut` */

DROP TABLE IF EXISTS `salut`;

CREATE TABLE `salut` (
  `salut_id` int(11) NOT NULL AUTO_INCREMENT,
  `salut` varchar(10) NOT NULL,
  PRIMARY KEY (`salut_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `salut` */

insert  into `salut`(`salut_id`,`salut`) values 
(1,'Ms'),
(2,'Mrs'),
(3,'Mr'),
(5,'Dr'),
(6,'Prof'),
(7,'Engr');

/*Table structure for table `schedule` */

DROP TABLE IF EXISTS `schedule`;

CREATE TABLE `schedule` (
  `sched_id` int(11) NOT NULL AUTO_INCREMENT,
  `time_id` int(1) DEFAULT NULL,
  `start_time` varchar(50) DEFAULT NULL,
  `end_time` varchar(50) DEFAULT NULL,
  `day` varchar(50) NOT NULL,
  `member_id` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `cys` varchar(15) NOT NULL,
  `room` varchar(15) NOT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  `settings_id` int(11) NOT NULL,
  `encoded_by` varchar(10) NOT NULL,
  PRIMARY KEY (`sched_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

/*Data for the table `schedule` */

insert  into `schedule`(`sched_id`,`time_id`,`start_time`,`end_time`,`day`,`member_id`,`subject_code`,`cys`,`room`,`remarks`,`settings_id`,`encoded_by`) values 
(27,NULL,'09:25','11:25','f',177,'ALG','BEED 1B','102',NULL,12,'27'),
(26,NULL,'10:16','13:16','t',27,'ALG','BEED 1A','101',NULL,12,'27'),
(24,NULL,'08:11','09:12','w',177,'COMPLIT','BEED 1B','102',NULL,12,'27'),
(25,NULL,'08:15','10:15','th',177,'ALG','BEED 1A','101',NULL,12,'27'),
(22,NULL,'07:05','10:05','m',27,'COMPLIT','BEED 1A','101',NULL,12,'27');

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `term` varchar(10) NOT NULL,
  `sem` varchar(15) NOT NULL,
  `sy` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`settings_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `settings` */

insert  into `settings`(`settings_id`,`term`,`sem`,`sy`,`status`) values 
(12,'Midterm','1st','2017-2018','active');

/*Table structure for table `signatories` */

DROP TABLE IF EXISTS `signatories`;

CREATE TABLE `signatories` (
  `sign_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `seq` int(2) NOT NULL,
  `set_by` int(11) NOT NULL,
  PRIMARY KEY (`sign_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `signatories` */

insert  into `signatories`(`sign_id`,`member_id`,`seq`,`set_by`) values 
(1,27,1,27);

/*Table structure for table `subject` */

DROP TABLE IF EXISTS `subject`;

CREATE TABLE `subject` (
  `subject_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_code` varchar(15) NOT NULL,
  `subject_title` varchar(100) NOT NULL,
  `subject_units` varchar(8) DEFAULT NULL,
  `prerequisite` varchar(100) DEFAULT NULL,
  `member_id` int(11) NOT NULL,
  PRIMARY KEY (`subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `subject` */

insert  into `subject`(`subject_id`,`subject_code`,`subject_title`,`subject_units`,`prerequisite`,`member_id`) values 
(1,'ENG1','English 1','10','ALG',27),
(2,'ALG','Algebra','10','Complit',27),
(3,'COMPLIT','Computer Literacy','10','ALG',27),
(4,'SOCSCI','Social Science','30','Complit',27);

/*Table structure for table `sy` */

DROP TABLE IF EXISTS `sy`;

CREATE TABLE `sy` (
  `sy_id` int(11) NOT NULL AUTO_INCREMENT,
  `sy` varchar(10) NOT NULL,
  PRIMARY KEY (`sy_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `sy` */

insert  into `sy`(`sy_id`,`sy`) values 
(1,'2017-2018');

/*Table structure for table `time` */

DROP TABLE IF EXISTS `time`;

CREATE TABLE `time` (
  `time_id` int(11) NOT NULL AUTO_INCREMENT,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `days` varchar(15) NOT NULL,
  PRIMARY KEY (`time_id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

/*Data for the table `time` */

insert  into `time`(`time_id`,`time_start`,`time_end`,`days`) values 
(4,'07:30:00','08:30:00','mwf'),
(5,'08:30:00','09:30:00','mwf'),
(6,'09:30:00','10:30:00','mwf'),
(7,'10:30:00','11:30:00','mwf'),
(8,'11:30:00','12:30:00','mwf'),
(9,'12:30:00','13:30:00','mwf'),
(10,'13:30:00','14:30:00','mwf'),
(11,'14:30:00','15:30:00','mwf'),
(12,'15:30:00','16:30:00','mwf'),
(13,'16:30:00','17:30:00','mwf'),
(14,'17:30:00','18:30:00','mwf'),
(15,'18:30:00','19:30:00','mwf'),
(18,'07:30:00','09:00:00','tth'),
(19,'09:00:00','10:30:00','tth'),
(20,'10:30:00','12:00:00','tth'),
(21,'12:00:00','13:30:00','tth'),
(22,'13:30:00','15:00:00','tth'),
(23,'15:00:00','16:30:00','tth'),
(24,'16:30:00','18:00:00','tth'),
(25,'18:00:00','19:30:00','tth'),
(37,'19:30:00','20:30:00','mwf'),
(56,'15:00:00','16:00:00','fst'),
(52,'10:00:00','11:00:00','fst'),
(51,'09:00:00','10:00:00','fst'),
(41,'08:00:00','09:00:00','fst'),
(55,'14:00:00','15:00:00','fst'),
(54,'13:00:00','14:00:00','fst'),
(53,'11:00:00','12:00:00','fst'),
(57,'16:00:00','17:00:00','fst'),
(58,'17:00:00','18:00:00','fst');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL,
  `program` varchar(10) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `user` */

insert  into `user`(`user_id`,`username`,`password`,`name`,`status`,`program`) values 
(1,'admin','a1Bz20ydqelm8m1wql3fefa44509901fc42790757c7a77d3c9','Admin','active','all');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
