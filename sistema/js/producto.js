$(document).ready(function(){
  $('.btnMenu').click(function(e) {
    e.preventDefault();
    if($('nav').hasClass('viewMenu')) {
      $('nav').removeClass('viewMenu');
    }else {
      $('nav').addClass('viewMenu');
    }
  });

  $('nav ul li').click(function() {
    $('nav ul li ul').slideUp();
    $(this).children('ul').slideToggle();
  });


  

//************************************************************************************* */
//                                      PRODUCTOS                                       */
//************************************************************************************* */

// Modal Agregar Producto
    $('.add_product').click(function(e) {
      e.preventDefault();
      var producto = $(this).attr('product');
      var action = 'infoProducto';
      $.ajax({
        url: 'modal.php',
        type: 'POST',
        async: true,
        data: {action:action,producto:producto},

        success: function(response) {
        if (response != 0) {
          var info = JSON.parse(response);
        //  $('#producto_id').val(info.codproducto);
        //  $('.nameProducto').html(info.descripcion);

          $('.bodyModal').html('<form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">'+
            '<h1>Agregar Producto</h1><br>'+
            '<h2 class="nameProducto">'+info.descripcion+'</h2>'+
            '<br>'+
            '<hr>'+
            '<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad del Producto" required><br>'+
            '<input type="number" name="precio" id="txtPrecio" placeholder="Precio del Producto" required>'+
            '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" required><br>'+
            '<input type="hidden" name="action" value="addProduct" required>'+
            '<div class="alert alertAddProduct"></div>'+
            '<button type="submit" class="btn_new">Agregar</button>'+
            '<a href="#" class="btn_ok closeModal" onclick="closeModal();">Cerrar</a>'+

          '</form>');
        }
        },
        error: function(error) {
          console.log(error);
        }
        });

      $('.modal').fadeIn();

    });

// modal Eliminar producto
$('.del_product').click(function(e) {
  e.preventDefault();
  var producto = $(this).attr('product');
  var action = 'infoProducto';
  $.ajax({
    url: 'modal.php',
    type: 'POST',
    async: true,
    data: {action:action,producto:producto},

    success: function(response) {
    if (response != 0) {
      var info = JSON.parse(response);
    //  $('#producto_id').val(info.codproducto);
    //  $('.nameProducto').html(info.descripcion);

      $('.bodyModal').html('<form action="" method="post" name="form_del_product" id="form_del_product" onsubmit="event.preventDefault(); delProduct();">'+
        '<h2 style="color: red; font-size: 18px;">¿Estás seguro de eliminar el Producto</h2>'+
        '<h2 class="nameProducto">'+info.descripcion+'</h2>'+
        '<hr>'+
        '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" required><br>'+
        '<input type="hidden" name="action" value="delProduct" required>'+
        '<div class="alert alertAddProduct"></div>'+
        '<input type="submit"  value="Aceptar" class="ok"><br>'+
        '<a href="#" style="text-align: center;" class="btn_cancelar" onclick="closeModal();">Cerrar</a>'+
      '</form>');
    }
    },
    error: function(error) {
      console.log('error');
    }
    });

  $('.modal').fadeIn();

});

$('#search_proveedor').change(function(e) {
  e.preventDefault();
  var sistema = getUrl();
  location.href = sistema+'buscar_productos.php?proveedor='+$(this).val();

});

//************************************************************************************* */
//                                      MEDICAMENTOS                                    */
//************************************************************************************* */





//************************************************************************************* */
//                                   REGISTRO DE LOTES                                  */
//************************************************************************************* */

// SELECCIONAR MEDICAMENTO = REGISTRO DE LOTE btn-accion
/* $(document).on('click', '.btn-accion', function() {
  var id = $(this).closest('tr').attr('id');
  $(this).data('id', id);
  $('#txt_cod_medicamento').val(id);
  $('#txt_cod_medicamento').focus();

  $('#modsearreglote').modal('hide'); 
}); */

$(".btn-accion").click(function (e) {
  e.preventDefault();
  var idproducto = $(this).attr("idp");
  var idmodal = $(this).attr("idmodal");
  var descripcion = $(this).attr("des");
  var stock = $(this).attr("st");  
  var pcompra = $(this).attr("pc");
  SelectProducto1(idproducto, idmodal, descripcion, stock, pcompra);
});

function SelectProducto1(idproducto,idmodal,descripcion,stock,pcompra){
  $('#txt_cod_medicamento').html(idproducto);
  $('#txt_descripcion-reglote').html(descripcion);
  $('#stock-reglote').html(stock);
  $('#txt_fecven-n1').val('');
  $('#txt_cant_producto-reglote').val('1');
  $('#txt_precio-reglote').val(pcompra);
  $('#txt_precio-cen').val(pcompra);
  $('#txt_precio-suc').val(pcompra);
  $('#txt_precio_total-reglote').val(pcompra);
  $('#txt_cant_producto-reglote').removeAttr('disabled');   
  $('#txt_precio-reglote').removeAttr('disabled');   
  $('#txt_precio-cen').removeAttr('disabled');   
  $('#txt_precio-suc').removeAttr('disabled');   
  $('#add_product_venta1').show();  
  $(idmodal).modal('hide');
  $('#txt_fecvencimiento').focus();
}


// BUSCAR MEDICAMENTO = REGISTRO DE LOTE txt_cod_medicamento  1
/* $('#txt_cod_medicamento').focus(function(e) {
  e.preventDefault();
  var smrl = $(this).val();
  if (smrl < 0) {   
    $('#txt_descripcion-reglote').html('--');
    $('#stock-reglote').html('-');
    $('#txt_cant_producto-reglote').html('0');
    $('#txt_precio-reglote').html('0.00');
    $('#txt_precio_total-reglote').html('0.00');
    //Bloquear Cantidad
    $('#txt_cant_producto-reglote').attr('disabled');
    $('#txt_precio-reglote').attr('disabled');
    // Ocultar Boto Agregar
    $('#add_product_venta1').slideUp();
  }
  var action = 'searchMedRegLote';
  if (smrl > 0 ) {
  $.ajax({
    url: 'modal.php',
    type: "POST",
    async: true,
    data: {action:action,smrl:smrl},
    success: function(response){
      if(response == 0) {       
        $('#txt_descripcion-reglote').html('--');        
        $('#stock-reglote').html('--');
        $('#txt_cant_producto-reglote').html('0');
        $('#txt_precio-reglote').html('0.00');
        $('#txt_precio_total-reglote').html('0.00');
        //Bloquear Cantidad
        $('#txt_cant_producto-reglote').attr('disabled');
        $('#txt_precio-reglote').attr('disabled');
        // Ocultar Boto Agregar
        $('#add_product_venta1').slideUp();


      }else{
        var data = JSON.parse(response);
        $('#txt_descripcion-reglote').html(data.nombrecompleto);
        $('#stock-reglote').html(data.stock);
        $('#txt_cant_producto-reglote').val('1');
        $('#txt_precio-reglote').val(data.preciounitario);
        $('#txt_precio_total-reglote').html(data.preciounitario);    
        
        //Bloquear Cantidad
        $('#txt_cant_producto-reglote').removeAttr('disabled');
        $('#txt_precio-reglote').removeAttr('disabled');
        // Ocultar Boto Agregar
        $('#add_product_venta1').slideDown();  
      }
    },
    error: function(error) {
    }
  });    
    $('#txt_descripcion-reglote').html('---');
    $('#stock-reglote').html('---');
    $('#txt_cant_producto-reglote').html('0');
    $('#txt_precio-reglote').html('0.00');
    $('#txt_precio_total-reglote').html('0.00');
    //Bloquear Cantidad
    $('#txt_cant_producto-reglote').attr('disabled');
    $('#txt_precio-reglote').attr('disabled');
    // Ocultar Boto Agregar
    $('#add_product_venta1').slideUp();

  }  
}); */

// CALCULAR EL TOTAL CUANDO LA CANTIDAD CAMBIA  = REGISTRO DE LOTE  100.1
$('#txt_cant_producto-reglote').keyup(function(e) {
  e.preventDefault();
  var precio_total = $(this).val() * $('#txt_precio-reglote').val();
   $('#txt_precio_total-reglote').html(precio_total);
  // Ocultat el boton Agregar si la cantidad es menor que 1 o si el valor no es numerico
  if (($(this).val() < 0 || isNaN($(this).val()))){
    $('#add_product_venta1').slideUp();
  }else {
    $('#add_product_venta1').slideDown();
  }
});

// CALCULAR EL TOTAL CUANDO EL PRECIO DE COMPRA CAMBIA  = REGISTRO DE LOTE 100.1.2
$('#txt_precio-reglote').keyup(function(e) {
  e.preventDefault();
  var precio_total = $(this).val() * $('#txt_cant_producto-reglote').val();
   $('#txt_precio_total-reglote').html(precio_total);
  // Ocultat el boton Agregar si la cantidad es menor que 1 o si el valor no es numerico
  if (($(this).val() < 0 || isNaN($(this).val()))){
    $('#add_product_venta1').slideUp();
  }else {
    $('#add_product_venta1').slideDown();
  }
});

// AGREGAR MEDICAMENTO AL DETALLE = REGISTRO DE LOTE 2
$('#add_product_venta1').click(function(e) {
  e.preventDefault();
  if ($('#txt_cant_producto-reglote').val() > 0) {    
    var idmedicamento = $('#txt_cod_medicamento').html();
    var cantidad = $('#txt_cant_producto-reglote').val();
    var descripcion = $('#txt_descripcion-reglote').html();
    var fecvencimiento = $('#txt_fecvencimiento').val(); 
    var preciounitario = $('#txt_precio-reglote').val();
    var preciocen = $('#txt_precio-cen').val();
    var preciosuc = $('#txt_precio-suc').val();
    var action = 'addMedRegLote';
    //$('#descripcionlote').val(fecvencimiento);    
    $('#btn_cancelar_reglote').slideDown();
    $('#btn_reglote').slideDown();  
    var todo = (preciounitario + preciocen + preciosuc );
    console.log(todo);
    $.ajax({
      url: 'modal.php',
      type: 'POST',
      async: true,
      data: {action:action,
            idmedicamento:idmedicamento,
            cantidad:cantidad, 
            descripcion:descripcion, 
            fecvencimiento:fecvencimiento,
            preciounitario:preciounitario,
            preciocen:preciocen,
            preciosuc:preciosuc},
      success: function(response) {
        
        if (response != 'error') {
          var info = JSON.parse(response);
          console.log(info.detalle1);
          console.log(info.totales1);
          $('#detalle_venta1').html(info.detalle1);
          $('#detalle_totales1').html(info.totales1);
          $('#txt_cod_medicamento').html(''); 
          $('#txt_descripcion-reglote').html('--');
          $('#stock-reglote').html('-');
          $('#txt_cant_producto-reglote').val('0');
          $('#txt_precio-reglote').val('0.00');
          $('#txt_precio-cen').val('0.00');
          $('#txt_precio-suc').val('0.00');
          $('#txt_precio_total-reglote').html('0.00');
          //Bloquear Cantidad
          $('#txt_cant_producto-reglote').attr('disabled');
          $('#txt_precio-reglote').attr('disabled');
          $('#txt_precio-cen').attr('disabled');
          $('#txt_precio-suc').attr('disabled');
          // Ocultar Boto Agregar
          $('#add_product_venta1').slideUp();               
          
        }else {
          console.log('No hay dato');      
        }   
        viewProcesar1();    
      },           
      error: function(error) {
      }
    }); 
  }
});

// ANULAR REGISTRO DE LOTE 3
$('#btn_cancelar_reglote').click(function(e) {
  e.preventDefault();
  var rows = $('#detalle_venta1 tr').length;
  if (rows > 0) {
    var action = 'cancelarRegLote'; //anularVenta
    $.ajax({
      url: 'modal.php',
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

//  REGISTRAR LOTE 5
$('#btn_reglote').click(function(e) {
  e.preventDefault();
  var rows = $('#detalle_venta1 tr').length;
  if (rows > 0) {
    var action = 'procesarRegLote';
    var descripcionlote = $('#descripcionlote').val();
    var proveedorlote = $('#proveedorlote').val();
    var estadolote = $('#estadolote').val();
     $.ajax({
      url: 'modal.php',
      type: 'POST',
      async: true,
      data: {action:action,estadolote:estadolote,descripcionlote:descripcionlote,proveedorlote:proveedorlote },
      success: function(response) {
      if (response != 0) {
        var info = JSON.parse(response);
        //console.log(info.nolote);
        kardexRegLote(info.nolote);
        generarNotaIngreso(info.codproveedor,info.nolote);        
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


//VER NOTA DE INGRESO = REGISTRO LOTE 6
$('.view_notaingreso').click(function(e) {
  e.preventDefault();
  var codProveedor = $(this).attr('p');
  var noLote = $(this).attr('l');
  generarNotaIngreso(codProveedor,noLote);
});










//************************************************************************************* */
//                                 VENTAS MEDICAMENTOS                                  */
//************************************************************************************* */

// buscar producto = Ventas 
$('#txt_cod_producto').keyup(function(e) {
  e.preventDefault();
  var productos = $(this).val();
  if (productos == "") {
    $('#txt_descripcion').html('-');
    $('#txt_existencia').html('-');
    $('#txt_cant_producto').val('0');
    $('#txt_precio').html('0.00');
    $('#txt_precio_total').html('0.00');

    //Bloquear Cantidad
    $('#txt_cant_producto').attr('disabled', 'disabled');
    // Ocultar Boto Agregar
    $('#add_product_venta').slideUp();
  }
  var action = 'infoProducto';
  if (productos != '') {
  $.ajax({
    url: 'modal.php',
    type: "POST",
    async: true,
    data: {action:action,producto:productos},
    success: function(response){
      if(response == 0) {
        $('#txt_descripcion').html('-');
        $('#txt_existencia').html('-');
        $('#txt_cant_producto').val('0');
        $('#txt_precio').html('0.00');
        $('#txt_precio_total').html('0.00');

        //Bloquear Cantidad
        $('#txt_cant_producto').attr('disabled','disabled');
        // Ocultar Boto Agregar
        $('#add_product_venta').slideUp();

      }else{

        var info = JSON.parse(response);
        $('#txt_descripcion').html(info.descripcion);
        $('#txt_existencia').html(info.existencia);
        $('#txt_cant_producto').val('1');
        $('#txt_precio').html(info.precio);
        $('#txt_precio_total').html(info.precio);
        // Activar Cantidad
        $('#txt_cant_producto').removeAttr('disabled');
        // Mostar boton Agregar
        $('#add_product_venta').slideDown();

      }
    },
    error: function(error) {
    }
  });
  $('#txt_descripcion').html('-');
  $('#txt_existencia').html('-');
  $('#txt_cant_producto').val('0');
  $('#txt_precio').html('0.00');
  $('#txt_precio_total').html('0.00');

  //Bloquear Cantidad
  $('#txt_cant_producto').attr('disabled','disabled');
  // Ocultar Boto Agregar
  $('#add_product_venta').slideUp();

  }
});





//************************************************************************************* */
//                                     CLIENTES                                         */
//************************************************************************************* */
// activa campos para registrar Cliente
$('.btn_new_cliente').click(function(e) {
  e.preventDefault();
  verificacionCaja();
  $('#nom_cliente').removeAttr('disabled');
  $('#tel_cliente').removeAttr('disabled');
  $('#dir_cliente').removeAttr('disabled');
  $('#btn_cnc').slideDown();
  $('#tel_cliente').val(0);
  $('#dir_cliente').val('SIN DIRECCION');    

  $('#div_registro_cliente').slideDown();
});



// activa campos para registrar Cliente
$('.btn_cancel_new_cliente').click(function(e) {
  e.preventDefault();  
  $('#nom_cliente').attr('disabled','disabled');
  $('#tel_cliente').attr('disabled','disabled');
  $('#dir_cliente').attr('disabled','disabled');
  $('#btn_cnc').slideUp();
  $('#tel_cliente').val('');
  $('#dir_cliente').val('');     
  $('#div_registro_cliente').slideUp();
  
});



// buscar Cliente  BUSCA CLIENTE
$('#dni_cliente').keyup(function(e) {
  e.preventDefault();
  var cl = $(this).val();
  var action = 'searchCliente';
  $.ajax({
    url: 'modal.php',
    type: "POST",
    async: true,
    data: {action:action,cliente:cl},
    success: function(response) {
      if (response == 0) {
        $('#idcliente').val('');
        $('#nom_cliente').val('');
        $('#tel_cliente').val('');
        $('#dir_cliente').val('');
        // mostar boton agregar
        $('.btn_new_cliente').slideDown();
      }else {
        var data = $.parseJSON(response);
        $('#idcliente').val(data.idcliente);
        $('#nom_cliente').val(data.nombre);
        $('#tel_cliente').val(data.telefono);
        $('#dir_cliente').val(data.direccion);
        // ocultar boton Agregar
        $('.btn_new_cliente').slideUp();
        $('#btn_cnc').slideUp();
        verificacionCaja();

        // Bloque campos
        $('#nom_cliente').attr('disabled','disabled');
        $('#tel_cliente').attr('disabled','disabled');
        $('#dir_cliente').attr('disabled','disabled');
        // ocultar boto Guardar
        $('#div_registro_cliente').slideUp();
      }
    },
    error: function(error) {

    }
  });

});


// crear cliente = Ventas
$('#form_new_cliente_venta').submit(function(e) {
  e.preventDefault();
  $.ajax({
    url: 'modal.php',
    type: "POST",
    async: true,
    data: $('#form_new_cliente_venta').serialize(),
    success: function(response) {
      if (response  != 0) {
        // Agregar id a input hidden
        $('#idcliente').val(response);
        //bloque campos
        $('#nom_cliente').attr('disabled','disabled');
        $('#tel_cliente').attr('disabled','disabled');
        $('#dir_cliente').attr('disabled','disabled');
        // ocultar boton Agregar
        $('.btn_new_cliente').slideUp();
        //ocultar boton Guardar
        $('#div_registro_cliente').slideDown();
      }
    },
    error: function(error) {
    }
  });
});



// calcular el Total 100.
$('#txt_cant_producto').keyup(function(e) {
  e.preventDefault();
  var precio_total = $(this).val() * $('#txt_precio').html();
  var existencia = parseInt($('#txt_existencia').html());
  $('#txt_precio_total').html(precio_total);
  // Ocultat el boton Agregar si la cantidad es menor que 1
  if (($(this).val() < 1 || isNaN($(this).val())) || ($(this).val() > existencia)){
    $('#add_product_venta').slideUp();
  }else {
    $('#add_product_venta').slideDown();
  }
});






// Agregar producto al detalle_venta 2
$('#add_product_venta').click(function(e) {
  e.preventDefault();
  if ($('#txt_cant_producto').val() > 0) {
    var codproducto = $('#txt_cod_producto').val();
    var cantidad = $('#txt_cant_producto').val();
    var action = 'addProductoDetalle';
    $.ajax({
      url: 'modal.php',
      type: 'POST',
      async: true,
      data: {action:action,producto:codproducto,cantidad:cantidad},
      success: function(response) {
        
        if (response != 'error') {
          var info = JSON.parse(response);
          console.log(info);
          $('#detalle_venta').html(info.detalle);
          $('#detalle_totales').html(info.totales);
          $('#txt_cod_producto').val('');
          $('#txt_descripcion').html('-');
          $('#txt_existencia').html('-');
          $('#txt_cant_producto').val('0');
          $('#txt_precio').html('0.00');
          $('#txt_precio_total').html('0.00');

          // Bloquear cantidad
          $('#txt_cant_producto').attr('disabled','disabled');

          // Ocultar boton agregar
          $('#add_product_venta').slideUp();
        }else {
          console.log('No hay dato');
        }
        viewProcesar();
      },
      error: function(error) {

      }
    });
  }
});






// anular venta 3
$('#btn_anular_venta').click(function(e) {
  e.preventDefault();
  var rows = $('#detalle_venta tr').length;
  if (rows > 0) {
    var action = 'anularVenta';
    $.ajax({
      url: 'modal.php',
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

// facturar venta 5
$('#btn_facturar_venta').click(function(e) {
  e.preventDefault();
  var rows = $('#detalle_venta tr').length;
  if (rows > 0) {
    var action = 'procesarVenta';
    var codcliente = $('#idcliente').val();
    $.ajax({
      url: 'modal.php',
      type: 'POST',
      async: true,
      data: {action:action,codcliente:codcliente},
      success: function(response) {
      if (response != 0) {
        var info = JSON.parse(response);
        //console.log(info);
        generarPDF(info.codcliente,info.nofactura);
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



//Ver Factura 6
$('.view_factura').click(function(e) {
  e.preventDefault();

  var codCliente = $(this).attr('cl');
  var noFactura = $(this).attr('f');

  generarPDF(codCliente,noFactura);
});

//VER NOTA DE INGRESO = GENERAR CARDEX DE MEDICAMENTO
$('.view_kardex').click(function(e) {
  e.preventDefault();
  // var idmedicamento = $(this).attr('idmed');
  var idmedicamento = $('#idm').html(); 
  //console.log(idmedicamento);    
  grKarMed(idmedicamento);
});



// Cambiar contraseña  
$('.newPass').keyup(function() {
  validaPass();
});


// cambiar contraseña
$('#frmChangePass').submit(function(e){
  e.preventDefault();
  var passActual = $('#actual').val();
  var passNuevo = $('#nueva').val();
  var passconfir = $('#confirmar').val();
  var action = "changePasword";
  if (passNuevo != passconfir) {
    $('.alertChangePass').html('<p style="color:red;">Las contraseñas no Coinciden</p>');
    $('.alertChangePass').slideDown();
    return false;
    }
  if (passNuevo.length < 5) {
  $('.alertChangePass').html('<p style="color:orangered;">Las contraseñas deben contener como mínimo 5 caracteres');
  $('.alertChangePass').slideDown();
  return false;
  }
  $.ajax({
    url: 'modal.php',
    type: 'POST',
    async: true,
    data: {action:action,passActual:passActual,passNuevo:passNuevo},
    success: function(response) {
      if (response != 'error') {
        var info = JSON.parse(response);
        if (info.cod == '00') {
          $('.alertChangePass').html('<p style="color:green;">'+info.msg+'</p>');
          $('#frmChangePass')[0].reset();
        }else {
          $('.alertChangePass').html('<p style="color:green;">'+info.msg+'</p>');
        }
        $('.alertChangePass').slideDown();
      }
    },
    error: function(error) {
    }
  });
});

$(".confirmar").submit(function(e) {
  e.preventDefault();
  Swal.fire({
    title: 'Esta seguro de eliminar?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'SI, Eliminar!'
  }).then((result) => {
    if (result.isConfirmed) {
      this.submit();
    }
  })
})


}); // fin ready

function validaPass() {
  var passNuevo = $('#nueva').val();
  var confirmarPass = $('#confirmar').val();
  if (passNuevo != confirmarPass) {
    $('.alertChangePass').html('<p style="color:red;">Las contraseñas no Coinciden</p>');
    $('.alertChangePass').slideDown();
    return false;
  }
if (passNuevo.length < 5) {
  $('.alertChangePass').html('<p style="color:orangered;">Las contraseñas deben contener como mínimo 5 caracteres');
  $('.alertChangePass').slideDown();
  return false;
}

$('.alertChangePass').html('<p style="color:blue;">Las contraseñas Coinciden.</p>');
$('.alertChangePass').slideDown();
}

//generarPDF
function generarPDF(cliente,factura) {
  url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;
  window.open(url, '_blank');
}

function kardexRegLote(id) {
  var action = 'kardex_reg_lote'; 
  var notaing = id;   
  $.ajax({
    url: 'modal/kardex.modal.php',
    type: "POST",
    async: true,
    data: {action:action,notaing:notaing},
    success: function(response) {
      if (response == 0) {
        console.log('');
      }else {
        //codigo
      }
      
    },
    error: function(error) {
    }
  });
}

//generaNotaInfreso
function generarNotaIngreso(idproveedor,idlote) {
  url = 'documentos/generarNotaIngLote.php?p='+idproveedor+'&l='+idlote;
  window.open(url, '_blank');
}

//generaNotaInfreso
function grKarMed(idmedicamento) {
  url = 'documentos/grKarMed.php?idmed='+idmedicamento;
  window.open(url,'_blank');
}



function del_product_detalle(correlativo) {
  var action = 'delProductoDetalle';
  var id_detalle = correlativo;
  $.ajax({
    url: 'modal.php',
    type: "POST",
    async: true,
    data: {action:action,id_detalle:id_detalle},
    success: function(response) {
        if (response != 0) {
        var info = JSON.parse(response);
        $('#detalle_venta').html(info.detalle);
        $('#detalle_totales').html(info.totales);
        $('#txt_cod_producto').val('');
        $('#txt_descripcion').html('-');
        $('#txt_existencia').html('-');
        $('#txt_cant_producto').val('0');
        $('#txt_precio').html('0.00');
        $('#txt_precio_total').html('0.00');

        // Bloquear cantidad
        $('#txt_cant_producto').attr('disabled','disabled');

        // Ocultar boton agregar
        $('#add_product_venta').slideUp();
      }else {
        $('#detalle_venta').html('');
        $('#detalle_totales').html('');


      }
      viewProcesar();
    },
    error: function(error) {
      
    }
  });
}

//FUNCION PARA ELIMINAR EL REGISTRO DEL LOTE
function del_product_detalle_lote(correlativo) {
  var action = 'delDetalleRegistroLote';
  var id_detalle = correlativo;
  $.ajax({
    url: 'modal.php',
    type: "POST",
    async: true,
    data: {action:action,id_detalle:id_detalle},
    success: function(response) {
        if (response != 0) {
        var info = JSON.parse(response);
        $('#detalle_venta1').html(info.detalle1);
        $('#detalle_totales1').html(info.totales1);

        $('#txt_cod_medicamento').val(''); 
        $('#txt_descripcion-reglote').html('--');
        $('#stock-reglote').html('-');
        $('#txt_cant_producto-reglote').html('0');
        $('#txt_precio-reglote').html('0.00');
        $('#txt_precio_total-reglote').html('0.00');
        //Bloquear Cantidad
        $('#txt_cant_producto-reglote').attr('disabled');
        $('#txt_precio-reglote').attr('disabled');
        // Ocultar Boto Agregar
        $('#add_product_venta1').slideUp(); 
      }else {
        $('#detalle_venta1').html('');
        $('#detalle_totales1').html('');
      }
      viewProcesar1();
    },
    error: function(error) {
      
    }
  });
}



// mostrar/ ocultar boton Procesar
function viewProcesar() {
  if ($('#detalle_venta tr').length > 0){
    $('#btn_facturar_venta').show();
    $('#btn_anular_venta').show();
  }else {
    $('#btn_facturar_venta').hide();
    $('#btn_anular_venta').hide();
  }
}

// mostrar/ ocultar botones anular y registrar lote
function viewProcesar1() {
  if ($('#detalle_venta1 tr').length > 0){    
    $('#btn_cancelar_reglote').show();
    $('#btn_reglote').show();  
  }else {
    $('#btn_cancelar_reglote').hide();
    $('#btn_reglote').hide();  
  }
}

function searchForDetalle(id) {
  var action = 'searchForDetalle';
  var user = id;
  $.ajax({
    url: 'modal.php',
    type: "POST",
    async: true,
    data: {action:action,user:user},
    success: function(response) {
      if (response == 0) {
        console.log(info);
      }else {
        var info = JSON.parse(response);
        $('#detalle_venta').html(info.detalle);
        $('#detalle_totales').html(info.totales);
      }
      viewProcesar();
    },
    error: function(error) {

    }
  });
}


function getUrl() {
  var loc = window.location;
  var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/')+ 1);
  return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}

// funcion para agregar producto
function sendDataProduct() {
  $('.alertAddProduct').html('');
  $.ajax({
    url: 'modal.php',
    type: 'POST',
    async: true,
    data: $('#form_add_product').serialize(),
    success: function(response) {
      if (producto == 'error') {
        $('.alertAddProduct').html('<p style="color : red;">Error al agregar producto.</p>');

      }else {
        var info = JSON.parse(response);
        $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
        $('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
        $('#txtCantidad').val('');
        $('#txtPrecio').val('');
        $('.alertAddProduct').html('<p>Producto Agregado Corectamente.</p>');

      }
    },
    error: function(error) {
      console.log(error);

    }
  });

}

// funcion para elimar producto
function delProduct() {
  var pr = $('#producto_id').val();
  $('.alertAddProduct').html('');
  $.ajax({
    url: 'modal.php',
    type: 'POST',
    async: true,
    data: $('#form_del_product').serialize(),
    success: function(response) {

      if (response == 'error') {
        $('.alertAddProduct').html('<p style="color : red;">Error al eliminar producto.</p>');

      }else {

        $('.row'+pr).remove();
        $('#form_del_product .ok').remove();
        $('.alertAddProduct').html('<p>Producto Eliminado Corectamente.</p>');

      }
    },
    error: function(error) {
      console.log(error);

    }
  });

}

function verificacionCaja() {
  var action = 'vercaja';
  // $('#nom_cliente').val('action');                     
  $.ajax({
    url: 'modal/procedimientos.modal.php',
    type: "POST",
    async: true,
    data: {action:action},
    success: function(response) {
      if (response == 0) {
        console.log(info);
      }else {
        var info = JSON.parse(response);
        $estadocaja = info.estado;
        if ($estadocaja == 2) {                    
          $('#modcaja').modal({backdrop: 'static', keyboard: false})  ; 
          $('#modcaja').modal('show');       
          
        } else { }                
      }
    },
    error: function(error) {
    }
  });
}



