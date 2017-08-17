CREATE TABLE `provider`.`zona_xref_vendedor` (
	`id_zona_xref_vendedor` INT NOT NULL AUTO_INCREMENT
	,`idVendedor` INT NOT NULL
	,`idZona` INT NOT NULL
	,PRIMARY KEY (`id_zona_xref_vendedor`)
	) ENGINE = InnoDB;