//****************************************************** */
//                       TRASPASOS                       */
//****************************************************** */

// SELECCIONAR MEDICAMENTO = TRASPASO DE ALMACEN A SUCURSAL
$(".btn-accion-n1").click(function (e) {
  e.preventDefault();
  var idproducto = $(this).attr("idp");
  var idmodal = $(this).attr("idmodal");
  var descripcion = $(this).attr("des");
  var stock = $(this).attr("st");
  var fecven = $(this).attr("fv");
  var pcompra = $(this).attr("pc");
  var pventa = $(this).attr("pv");
  SelectProducto(idproducto, idmodal, descripcion, stock, pcompra, pventa,fecven);
});

// MOSTRAR Y OCULTAR EL BOTON DE AGREGAR DEPENDIENDO SI SOBRE PASA LA CANTIDAD DE STOCK ok
$("#txt_cant-n1").keyup(function (e) {
  e.preventDefault();
  var existencia = parseInt($("#txt_stock-n1").html());
  // Ocultat el boton Agregar si la cantidad es menor que 1 o mayor a la existencia
  if ($(this).val() < 1 || isNaN($(this).val()) || $(this).val() > existencia) {
    $("#btn_agredet-n1").slideUp();
  } else {
    $("#btn_agredet-n1").slideDown();
  }
});

// AGREGAR DETALLE TRASPASO DE ALMACEN A SUCURSAL ok
$("#btn_agredet-n1").click(function (e) {
  e.preventDefault();
  if ($("#txt_cant-n1").val() > 0) {
    var idmedicamento = parseInt($("#txt_idmed-n1").html());
    var cantidad = $("#txt_cant-n1").val();
    var descripcion = $("#txt_des-n1").html();
    var fecvencimiento = $("#txt_fecven-n1").val();
    var precioventa = $("#txt_prev-n1").val();
    var preciocompra = $("#txt_prec-n1").val();
    var action = "addDetalle-n1";

    $.ajax({
      url: "modal/traspasos.modal.php",
      type: "POST",
      async: true,
      data: {
        action: action,
        idmedicamento: idmedicamento,
        cantidad: cantidad,
        descripcion: descripcion,
        fecvencimiento: fecvencimiento,
        precioventa: precioventa,
        preciocompra: preciocompra},
      success: function (response) {
        if (response != "error") {
          var info = JSON.parse(response);
          //detalles
          $("#detalle_traspaso-n1").html(info.detalle);
          $("#detalle_totales-n1").html(info.totales);
          //VOLVEMOS A LOS VALORES ANTERIORES
          $("#txt_idmed-n1").html("-");
          $("#txt_des-n1").html("-");
          $("#txt_stock-n1").html("0");
          $("#txt_fecven-n1").val("");
          $("#txt_cant-n1").val(0);
          $("#txt_prec-n1").val(0);
          $("#txt_prev-n1").val(0);
          //Bloquear Cantidad y precio de venta
          $("#txt_cant-n1").attr("disabled");
          $("#txt_prev-n1").attr("disabled");
          // Ocultar Boto Agregar
          $("#btn_agredet-n1").slideUp();
        } else {
          console.log("No hay dato");
        }
        viewProcesar2();
      },
      error: function (error) { }
    });
  }
});

// BOTON DE PRUEBA
$('#btn_prueba').click(function(e) {
  e.preventDefault();      
  $('#descriptraspaso-n1').val('cambia'); 
});

// ANULAR TRASPASO btn_anular-n1 ok
$('#btn_anular-n1').click(function(e) {
  e.preventDefault();
  var rows = $('#detalle_traspaso-n1 tr').length;
  if (rows > 0) {
    var action = 'anularTraspaso';
    $.ajax({
      url: 'modal/traspasos.modal.php',
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


// BOTON PARA seleccionar los datos a enviar TRASPASOS sucdestino-n1 txt_prev-n1 ok btn-tn1
$('#tipotraspaso-n1').change(function(e) {
  e.preventDefault();
  var idtipot = $(this).val();
  if (idtipot == 2) {  
    $('#sucdestino-n1').val('0');  
    $('#idproveedor-n1').removeAttr('disabled');
    $('#sucdestino-n1').attr('disabled','disabled'); 
    $('#txt_prev-n1').val('');  
    $('#txt_prev-n1').attr('disabled','disabled'); 
    $('#btn-tn1').hide();
    $('#btn-tn2').hide();
    $('#btn-tn3').show();

  } else if(idtipot == 4){   
    $('#idproveedor-n1').val('0');  
    $('#idproveedor-n1').attr('disabled','disabled');
    $('#sucdestino-n1').removeAttr('disabled');  
    $('#txt_prev-n1').removeAttr('disabled');  
    $('#btn-tn1').show();
    $('#btn-tn2').hide();
    $('#btn-tn3').hide();  
  } 
  else if (idtipot == 6) {
    $('#txt_prev-n1').val('');  
    $('#txt_prev-n1').attr('disabled','disabled');
    $('#btn-tn1').hide();
    $('#btn-tn2').show();
    $('#btn-tn3').hide();  
  }
  else if (idtipot == 0) {
    
    $('#btn-tn1').hide();
    $('#btn-tn2').hide();
    $('#btn-tn3').hide();  
  }
});

//  PROCESAR TRASPASOS btn_procesar-n1
$('#btn_procesar-n1').click(function(e) {
  e.preventDefault();  
  var rows = $('#detalle_traspaso-n1 tr').length;
  if (rows > 0) {
    var action = "";
    var tipot_n1 = $('#tipotraspaso-n1').val();
    var suco_n1 = $('#sucorigen-n1').val();
    var sucd_n1 = $('#sucdestino-n1').val();
    var idp_n1 = $('#idproveedor-n1').val();
    var des_n1 = $('#descriptraspaso-n1').val();
    if (des_n1 == ""){
      $('#alerta').html('<div class="alert alert-danger" role="alert"> Todos los campos son obligatorios</div>');
         }
    /* var todo = action+' '+tipot_n1 +' '+suco_n1+' '+sucd_n1+' '+idp_n1+' '+des_n1+' '+des_n1; 
    $('#descriptraspaso-n1').val(todo);  */
    if (tipot_n1 == 4) {
      var action = 'procesarTraspaso-n1'; 
        /* var todo = action+' '+tipot_n1 +' '+suco_n1+' '+sucd_n1+' '+idp_n1+' '+des_n1+' '+des_n1; 
    $('#descriptraspaso-n1').val(todo);  */
      $.ajax({
        url: 'modal/traspasos.modal.php',
        type: 'POST',
        async: true,
        data: {action:action,tipot_n1:tipot_n1,suco_n1:suco_n1,sucd_n1:sucd_n1,idp_n1:idp_n1,des_n1:des_n1},
        success: function(response) {
        if (response != 0) {
          var info = JSON.parse(response);
          console.log(info);
          kardexRegTrapaso(info.notraspaso);
          generarNotaRemision(info.idsucdestino,info.notraspaso);            
          location.reload();
        }else {
          console.log('no hay dato');        
        }
        },
        error: function(error) {
  
        }
      });  
    } else if (tipot_n1 == 6) {
      var action = 'procesarTraspaso-n2';  
     /*  var todo= 'adrian';  
      $('#descriptraspaso-n1').val(todo);  */
      $.ajax({
        url: 'modal/traspasos.modal.php',
        type: 'POST',
        async: true,
        data: {action:action,tipot_n1:tipot_n1,suco_n1:suco_n1,sucd_n1:sucd_n1,des_n1:des_n1},
        success: function(response) {
        if (response != 0) {
          var info = JSON.parse(response);
          console.log(info);
          kardexRegDevolucionSuc(info.nodevsuc);
          generarNotaDevolucionSuc(info.idsucorigen,info.nodevsuc);            
          location.reload();
        }else {
          console.log('no hay dato');        
        }
        },
        error: function(error) {  
        }
      });  
    }
    
    
  }
});

// SELECCIONAR MEDICAMENTO = DEVOLUCION DE STOCK A ALMACEN CENTRAL
$(".btn-accion-n2").click(function (e) {
  e.preventDefault();
  var idproducto = $(this).attr("idp");
  var idmodal = $(this).attr("idmodal");
  var descripcion = $(this).attr("des");
  var stock = $(this).attr("st");
  var fecven = $(this).attr("fv");
  var pcompra = $(this).attr("pc");
  var pventa = $(this).attr("pc");
  SelectProducto(idproducto, idmodal, descripcion, stock, pcompra, pventa, fecven);
});

// OBTENER EL CODIGO DEL LABORATORIO PARA CARGAR LOS MEDICAMENTOS proveedorlote btnmodalreglote idlabmodal
$("#loco").click(function (e) {
  e.preventDefault(); 
 
  var idlab = $('#proveedorlote').val();
  console.log(idlab);
  if (idlab == 1) {
    $('#modsearreglotealcos').modal('show');   

  } else if(idlab == 2){
      $('#modsearreglotevita').modal('show');   
  } else if(idlab == 3){
      $('#modsearreglote').modal('show');
  }
    else if(idlab == 4){
      $('#modsearreglotecofar').modal('show');   
  }
    else if(idlab == 5){
      $('#modsearregloteifa').modal('show');  
  }
    else if(idlab == 6){
      $('#modsearreglotepharma').modal('show');   
  }
    else if(idlab == 7){
      $('#modsearreglotetecnofarma').modal('show'); 
  }
    else if(idlab == 8){
      $('#modsearreglotefarmaval').modal('show');
  }
    else if(idlab == 9){
      $('#modsearreglotesae').modal('show');       
  }
    else if(idlab == 10){
      $('#modsearregloteinti').modal('show');
  }
 
});





//**************    FUNCIONES       ***************** */

//SeleccionarMedicamento/envair valores y Cerrar Modal ok
function SelectProducto(idproducto,idmodal,descripcion,stock,pcompra, pventa,fecven){ 
  $('#txt_idmed-n1').html(idproducto);
  $('#txt_des-n1').html(descripcion);
  $('#txt_stock-n1').html(stock);
  $('#txt_fecven-n1').val(fecven);
  $('#txt_cant-n1').val('1');
  $('#txt_prec-n1').val(pcompra);
  $('#txt_prev-n1').val(pventa);
  $('#txt_cant-n1').removeAttr('disabled');   
  $('#btn_agredet-n1').show();  
  $(idmodal).modal('hide'); 
}

// mostrar/ ocultar botones anular y TRASPASO ALMACEN A SUCURSAL ok
function viewProcesar2() {
  if ($('#detalle_traspaso-n1 tr').length > 0){    
    $('#btn_anular-n1').show();
    $('#btn_procesar-n1').show();  
  }else {
    $('#btn_anular-n1').hide();
    $('#btn_procesar-n1').hide();  
  }
}

//FUNCION PARA ELIMINAR EL REGISTRO DETALLE TEMPORAL TRASPASO ALMACEN A SUCURSAL ok
function del_product_detalle_st(correlativo) {
  var action = 'delDetallest';
  var id_detalle = correlativo;
  $.ajax({
    url: 'modal/traspasos.modal.php',
    type: "POST",
    async: true,
    data: {action:action,id_detalle:id_detalle},
    success: function(response) {
        if (response != 0) {
        var info = JSON.parse(response);
        //detalles
        $('#detalle_traspaso-n1').html(info.detalle);
        $('#detalle_totales-n1').html(info.totales);
        //VOLVEMOS A LOS VALORES ANTERIORES
        $('#txt_idmed-n1').html('-');
        $('#txt_des-n1').html('-');
        $('#txt_stock-n1').html('0');
        $('#txt_fecven-n1').val('');
        $('#txt_cant-n1').val(0);
        $('#txt_prec-n1').val(0);
        $('#txt_prev-n1').val(0);
        //Bloquear Cantidad y precio de venta
        $('#txt_cant-n1').attr('disabled');
        $('#txt_prev-n1').attr('disabled');
        // Ocultar Boto Agregar
        $('#btn_agredet-n1').slideUp();      

        
      }else {
        $('#detalle_traspaso-n1').html('');
        $('#detalle_totales-n1').html('');
      }
      viewProcesar2();
    },
    error: function(error) {
      
    }
  });
}

function kardexRegTrapaso(id) {
  var action = 'kardex_reg_traspaso'; 
  var notatraspaso = id;   
  $.ajax({
    url: 'modal/kardex.modal.php',
    type: "POST",
    async: true,
    data: {action:action,notatraspaso:notatraspaso},
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

function kardexRegDevolucionSuc(id) {
  var action = 'kardex_reg_devolucion_suc'; 
  var notadevolucion = id;   
  $.ajax({
    url: 'modal/kardex.modal.php',
    type: "POST",
    async: true,
    data: {action:action,notadevolucion:notadevolucion},
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


//generaNotaRemision
function generarNotaRemision(idsucdestino,idnotraspaso) {
  url = 'documentos/generarNotaRemision.php?sd='+idsucdestino+'&idt='+idnotraspaso;
  window.open(url, '_blank');
}

//VER NOTA DE REMISION 
$('.view_notaremision').click(function(e) {
  e.preventDefault();
  var coddestino = $(this).attr('sd');
  var notraspaso = $(this).attr('idt');
  generarNotaRemision(coddestino,notraspaso);
});

//generaNotaDevolucion
function generarNotaDevolucionSuc(idsucorigen,idnotraspaso) {
  url = 'documentos/generarNotaDevolucionSuc.php?so='+idsucorigen+'&idv='+idnotraspaso;
  window.open(url, '_blank');
}

//VER NOTA DE REMISION 
$('.view_notadevolucion').click(function(e) {
  e.preventDefault();
  var codorigen = $(this).attr('so');
  var notraspaso = $(this).attr('idv');
  generarNotaDevolucionSuc(codorigen,notraspaso);
});

