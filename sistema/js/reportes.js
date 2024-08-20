//****************************************************** */
//                       REPORTES                        */
//****************************************************** */

// REPORTES MEDICAMENTOS prov
    //VER MED X LABORATORIO
        $('#prov').change(function(e) {
            e.preventDefault();
            var idlab = $(this).val();
           //console.log(idlab);
            $('#prove').val(idlab);              
        });   
        $('.view_rxl').click(function(e) {
            e.preventDefault();
            var lab = $('#prove').val();  
            //console.log(lab);
            grListMedProv(lab);
        });  
        // GENERAR MED X LABORATORIO
        function grListMedProv(lab) {
            url = 'documentos/grListMedProv.php?l='+lab;
            window.open(url, '_blank');
        }

// BAJA EXISTENCIA
    // BTN MOSTRAR OPCIONES PARA IMPRIMIR
    $('#printbe').click(function(e) {
        //console.log('info');
        $('#printbe').hide();
        $('#printbec').slideDown(); 
        $('#printbet').slideDown();  
        $('#printbel').slideDown();  
        $('#labprntbel').slideDown();  
    });
    
      // BTN OCULTAR OPCIONES PARA IMPRIMIR
      $('#printbec').click(function(e) {
        console.log('info');
        $('#printbec').hide();
        $('#printbe').slideDown(); 
        $('#printbet').slideUp();  
        $('#printbel').slideUp();  
        $('#labprntbel').slideUp();  
    });

    
    