
// PROCEDIMIENTO => CIERRE DE CAJA

// CIERRE CAJA => 
$('#cerrarcaja').click(function(e) {
    e.preventDefault();
    //console.log('log');
    var diferencia = $('#difcaja').val();
    var observaciones = $('#detallecierre').val();    
    var efectivocaja = $('#efectivocaja').val();
    var idcajac = $('#idcajac').val();       
    var action = 'cerrarCaja';
    var iduser = $('#idu').val(); 
    var idcaja = $('#idc').val(); 
    var fecha = $('#fa').val(); 
    var todo = diferencia + observaciones + efectivocaja + action + idcajac;
    //console.log(todo);
      $.ajax({
        url: 'modal/procedimientos.modal.php',
        type: 'POST',
        async: true,
        data: {action:action, 
            diferencia:diferencia, 
            observaciones:observaciones,
            efectivocaja:efectivocaja},
        success: function(response) {
          if (response != 0) {
            console.log(fecha,idcaja,iduser);
            //location.reload();
            generarCierreCaja(idcajac);          
          }
        },
        error: function(error) {
  
        }
      });
    
  });

//GENERAR REPORTE DE CIERRE
function generarCierreCaja(id) {
  url = 'documentos/generarCierreCaja.php?id='+id;
  window.open(url, '_blank');
}

//GENERAR REPORTE DE VENTAS DE CAJA
function generarReporteVentaCaja(fecha,idcaja,iduser) {
  url = 'documentos/generarReporteVentaCaja.php?f='+fecha+'&idc='+idcaja+'&idu='+iduser;
  window.open(url, '_blank')

} 

//VER NOTA DE INGRESO = REGISTRO LOTE 6
$('.view_caja').click(function(e) {
  e.preventDefault();
  var codcaja = $(this).attr('id');
  console.log(codcaja);
  generarCierreCaja(codcaja);
});

//VER NOTA DE INGRESO = REGISTRO LOTE 6
$('.cierre_ventas').click(function(e) {
  e.preventDefault();
  var fecha = $(this).attr('f');
  var coduser = $(this).attr('idu');
  var codcaja = $(this).attr('idc');
  console.log(fecha, coduser, codcaja);
  generarReporteVentaCaja(fecha, coduser, codcaja);
});

