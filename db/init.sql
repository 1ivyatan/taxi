use DATABASE_NAME; -- change this

CREATE TABLE `taxi_cars` (
  `car_id` int(11) NOT NULL,
  `license` varchar(7) NOT NULL,
  `model` varchar(256) NOT NULL,
  `seats` int(11) NOT NULL,
  `imagehex` mediumtext NOT NULL,
  `removed` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `edited` datetime DEFAULT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT 0
);

CREATE TABLE `taxi_reservations` (
  `res_id` int(11) NOT NULL,
  `phone` varchar(8) NOT NULL,
  `email` varchar(256) NOT NULL,
  `name` varchar(32) NOT NULL,
  `fname` varchar(42) NOT NULL,
  `notes` text NOT NULL,
  `date` date NOT NULL,
  `status` enum('pending','viewed','completed','rejected') NOT NULL DEFAULT 'pending',
  `removed` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `edited` datetime DEFAULT NULL
);

CREATE TABLE `taxi_usr` (
  `usr_id` int(11) NOT NULL,
  `usr` varchar(24) NOT NULL,
  `passwd` varchar(256) NOT NULL,
  `name` varchar(64) NOT NULL,
  `fname` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(8) NOT NULL,
  `descr` text DEFAULT NULL,
  `imagehex` mediumtext DEFAULT NULL,
  `role` enum('driver','admin','superuser') NOT NULL DEFAULT 'driver',
  `removed` tinyint(1) NOT NULL DEFAULT 0
);

INSERT INTO `taxi_usr` (`usr_id`, `usr`, `passwd`, `name`, `fname`, `email`, `phone`, `descr`, `imagehex`, `role`, `removed`) VALUES
(1, 'admin', '$2y$10$gySwCiCRj6g.LtvBErfsu.ZCBYJb/bkWpagQaqHFdM1LXWiXWXoau', 'Admin', 'Admin', 'admin@fakemail.lv', '22222222', 'Desc', NULL, 'superuser', 0);
