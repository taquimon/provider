ALTER TABLE `pedido_credito` ADD `idVendedor` INT NOT NULL AFTER `numeroRecibo`;
ALTER TABLE `pedido` ADD `idVendedor` INT NOT NULL AFTER `descuento`;