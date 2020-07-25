--
-- Creazione tabella per il salvataggio dei mac address
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `mac` varchar(17) NOT NULL,
  `ssid` varchar(255) NOT NULL,
  `dbm` tinyint(4) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL,
  `received_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB;
