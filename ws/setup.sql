--
-- Creazione tabella per il salvataggio dei mac address
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `mac` varchar(17) NOT NULL,
  `ssid` varchar(255) NOT NULL,
  `dbm` tinyint(4) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL,
  `received_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB;

-- Tabella per il match fra mac address e nome
CREATE TABLE `devices` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `mac` VARCHAR(17) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;
