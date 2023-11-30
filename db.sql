CREATE TABLE `reminders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reminder_date` date NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_no` varchar(15) DEFAULT NULL,
  `sms_no` varchar(15) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `recur_7_days` tinyint(1) DEFAULT '0',
  `recur_5_days` tinyint(1) DEFAULT '0',
  `recur_3_days` tinyint(1) DEFAULT '0',
  `recur_2_days` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

CREATE TABLE `users_data` (
  `User_Name` varchar(150) NOT NULL,
  `EmailId` varchar(150) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `Phone_num` bigint DEFAULT NULL,
  PRIMARY KEY (`User_Name`),
  UNIQUE KEY `EmailId_UNIQUE` (`EmailId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci