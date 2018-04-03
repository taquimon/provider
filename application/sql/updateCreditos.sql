UPDATE pedido_credito p JOIN detalle d on p.idPedido = d.idPedido SET p.saldo = (SELECT SUM(d.precio * d.cantidad) as total FROM detalle d WHERE p.idPedido = d.idPedido group by d.idPedido) - (SELECT SUM(p.acuenta))