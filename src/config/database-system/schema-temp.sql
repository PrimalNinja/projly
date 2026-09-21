CREATE TABLE `t_sessions` (
  `session_id` varchar(100) NOT NULL DEFAULT '',
  `session_token` varchar(100) NOT NULL,
  `session_data` text NOT NULL,
  `expires` int(11) NOT NULL DEFAULT '0',
  `lifetime` int(11) NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDOFSTATEMENT

