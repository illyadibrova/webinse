CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `first_name` varchar(50) COLLATE utf8_general_ci NOT NULL,
 `second_name` varchar(50) COLLATE utf8_general_ci NOT NULL,
 `email` varchar(50) COLLATE utf8_general_ci NOT NULL,
 `created` datetime NOT NULL,
 `modified` datetime NOT NULL,
 `status` enum('1','0') COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;