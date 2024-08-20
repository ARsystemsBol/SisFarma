
//****************************************************** */
//                       VENTAS                          */
//****************************************************** */

// SELECCIONAR MEDICAMENTO = VENTAS 
$(".btn-accion-n4").click(function (e) {
  e.preventDefault();
  var idproducto = $(this).attr("idp");
  var idmodal = $(this).attr("idmodal");
  var descripcion = $(this).attr("des");
  var stock = $(this).attr("st");
  var fecven = $(this).attr("fv");
  var pcompra = $(this).attr("pc");  
  SelectProductoVenta(idproducto, idmodal, descripcion, stock, pcompra, fecven);
});

// MOSTRAR Y OCULTAR EL BOTON DE AGREGAR DEPENDIENDO SI SOBRE PASA LA CANTIDAD DE STOCK Y CALCULAMOS EL TOTAL SOLO VISUAL
$("#txt_cant_producton4").keyup(function (e) {
  e.preventDefault();
  var precio_total = $(this).val() * $('#txt_precion4').val();
  $('#txt_precio_totaln4').html(precio_total);
  var existencia = parseInt($("#txt_existencian4").html());
  // Ocultat el boton Agregar si la cantidad es menor que 1 o mayor a la existencia
  if ($(this).val() < 1 || isNaN($(this).val()) || $(this).val() > existencia) {
    $("#add_product_ventan4").slideUp();
    $('#txt_precio_totaln4').html(0);
  } else {
    $("#add_product_ventan4").slideDown();
  }
});

$("#txt_precion4").keyup(function (e) {
  e.preventDefault();
  var precio_total = $(this).val() * $('#txt_cant_producton4').val();
  $('#txt_precio_totaln4').html(precio_total); 
  // Ocultat el boton Agregar si la cantidad es menor que 1 o mayor a la existencia
  if ($(this).val() < 1 || isNaN($(this).val()) ) {
    $("#add_product_ventan4").slideUp();
    $('#txt_precio_totaln4').html(0);
  } else {
    $("#add_product_ventan4").slideDown();
  }
});

// AGREGAR MEDICAMENTO AL DETALLE = REGISTRO DE LOTE 2
$('#add_product_ventan4').click(function(e) {
  e.preventDefault();
   //console.log('hola');
   var idmedicamento = $('#txt_cod_producton4').html();
   var descripcion = $('#txt_descripcionn4').html();
   var fechavencimiento = $('#txt_fechavencimienton4').val();
   var cantidad = $('#txt_cant_producton4').val();
   var preciounitario = $('#txt_precion4').val();  
   var action = 'adddetalleventa';
  // var todo = accion+' '+idmedicamento +' '+descripcion+' '+fechavencimiento+' '+cantidad+' '+preciounitario;   
   $('#btn_anular_ventan4').slideDown();
   $('#btn_facturar_ventan4').slideDown();
   $('#nom_cliente').val('todo');
   //ajax
    $.ajax({
    url: 'modal/medicamentos.modal.php',
    type: 'POST',
    async: true,
    data: {action:action,
          idmedicamento:idmedicamento,
          cantidad:cantidad, 
          descripcion:descripcion, 
          fechavencimiento:fechavencimiento,
          preciounitario:preciounitario},
    success: function(response) {
      
      if (response != 'error') {
        var info = JSON.parse(response);
        //console.log(info);
        //Array Detalles y Totales        
        $('#detalle_ventan4').html(info.detalle);
        $('#detalle_totalesn4').html(info.totales);
        //valores a su estado 0        
        $('#txt_cod_producton4').html('');
        $('#txt_descripcionn4').html('-');
        $('#txt_existencian4').html('0');
        $('#txt_fechavencimienton4').val('');
        $('#txt_cant_producton4').val('0');
        $('#txt_precion4').val('0.00');
        $('#txt_precio_totaln4').html('0.00');
        //Ocultamos el boton de agregar
        $('#add_product_ventan4').slideUp();      
      }else {
        console.log('No hay dato');      
      }   
      viewProcesar5();    
    },           
    error: function(error) {
    }
    });
    //final ajax 
});

// ANULAR TRASPASO btn_anular-n1 ok
$('#btn_anular_ventan4').click(function(e) {
  e.preventDefault();
  var rows = $('#detalle_ventan4 tr').length;
  if (rows > 0) {
    var action = 'anularVentas';
    $.ajax({
      url: 'modal/medicamentos.modal.php',
      type: 'POST',
      async: true,
      data: {action:action},
      success: function(response) {
        if (response != 0) {
          location.reload();
        }
      },
      error: function(error) {
      }
    });
  }
}); 

// PROCESAR VENTA btn_facturar_ventan4
$('#btn_facturar_ventan4').click(function(e) {
  e.preventDefault();
  var rows = $('#detalle_ventan4 tr').length;
  if (rows > 0) {
    var action = 'procesarVentas';
    var codcliente = $('#idcliente').val();
    $.ajax({
      url: 'modal/medicamentos.modal.php',
      type: 'POST',
      async: true,
      data: {action:action,codcliente:codcliente},
      success: function(response) {
      if (response != 0) {
        var info = JSON.parse(response);
        console.log(info);
        kardexVenta(info.nofactura);
        generarNotaVenta(info.codcliente,info.nofactura);
        location.reload();
      }else {
        console.log('no hay dato');
      }
      },
      error: function(error) {

      }
    });
  }
});



// calcula diferencia al cerrar caja efectivocaja
$("#efectivocaja").keyup(function (e) {
  e.preventDefault();
  var total =  $('#totalcaja').val();
  var efectivo = $(this).val();
  var diferencia = efectivo - total;   
    $('#difcaja').val(diferencia);
    if (diferencia > 0) {
      $('#detallecierre').val('SOBRANTE');
    } else if(diferencia < 0) {
      $('#detallecierre').val('FALTANTE');
    } else if(diferencia == ''){
      $('#detallecierre').val('NINGUNA');
    }
  
  
});



//**************    FUNCIONES       ***************** */

//SeleccionarMedicamento/envair valores y Cerrar Modal ok
function SelectProductoVenta(idproducto,idmodal,descripcion,stock,pcompra,fecven){
  $('#txt_cod_producton4').html(idproducto);
  $('#txt_descripcionn4').html(descripcion);
  $('#txt_existencian4').html(stock);
  $('#txt_fechavencimienton4').val(fecven);
  $('#txt_cant_producton4').val('1');
  $('#txt_precion4').val(pcompra);
  $('#txt_precio_totaln4').html(pcompra);
  $('#txt_cant_producton4').removeAttr('disabled'); 
  $('#txt_precion4').removeAttr('disabled');   
  $('#add_product_ventan4').show();    
  $(idmodal).modal('hide'); 
  $('#txt_cant_producton4').focus(); 
}

// mostrar/ ocultar botones anular ventas
function viewProcesar5() {
  if ($('#detalle_ventan4 tr').length > 0){    
    $('#btn_anular_ventan4').show();
    $('#btn_facturar_ventan4').show();  
  }else {
    $('#btn_anular_ventan4').hide();
    $('#btn_facturar_ventan4').hide();  
  }
}

//FUNCION PARA ELIMINAR EL REGISTRO DETALLE EN EL MODULO DE VENTAS
function del_product_detalle_ventas(correlativo) {
  var action = 'delDetalleVentasid';
  var id_detalle = correlativo;
  $.ajax({
    url: 'modal/medicamentos.modal.php',
    type: "POST",
    async: true,
    data: {action:action,id_detalle:id_detalle},
    success: function(response) {
        if (response != 0) {
        var info = JSON.parse(response);
        //Array Detalles y Totales        
        $('#detalle_ventan4').html(info.detalle);
        $('#detalle_totalesn4').html(info.totales);
        //valores a su estado 0        
        $('#txt_cod_producton4').html('');
        $('#txt_descripcionn4').html('-');
        $('#txt_existencian4').html('0');
        $('#txt_fechavencimienton4').val('');
        $('#txt_cant_producton4').val('0');
        $('#txt_precion4').html('0.00');
        $('#txt_precio_totaln4').html('0.00');
        //Ocultamos el boton de agregar
        $('#add_product_ventan4').slideUp();
      }else {
        $('#detalle_ventan4').html('');
        $('#detalle_totalesn4').html('');
      }
      viewProcesar5();
    },
    error: function(error) {
      
    }
  });
}

$('.view_facturas').click(function(e) {
  e.preventDefault();
  var cliente = $(this).attr('cl');
  var factura = $(this).attr('f');
  console.log('yaaa')
  generarNotaVenta(cliente,factura);
});

// GENERAR NOTA DE VENTA PDF generarNotaVenta
function generarNotaVenta(cliente,factura) {
  url = 'documentos/generarNotaVenta.php?cl='+cliente+'&f='+factura;
  window.open(url, '_blank');
}

// REALIZAMOS EL PROCESO DE REBAJAR EL STOCK EN LA TABLA STOCK
function kardexVenta(id) {
  var action = 'kardex_venta'; 
  var nofactura = id;   
  $.ajax({
    url: 'modal/kardex.modal.php',
    type: "POST",
    async: true,
    data: {action:action,nofactura:nofactura},
    success: function(response) {
      if (response == 0) {
        console.log(info);
      }else {
        //codigo
      }
      //kardexRegLote(id);
    },
    error: function(error) {
    }
  });
}

