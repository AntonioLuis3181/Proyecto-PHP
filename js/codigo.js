"use strict";
var oEmpresa = new Empresa(); 

registrarEventos();

function registrarEventos() {

    document
        .querySelector("#mnuAltaVenta")
        .addEventListener("click", mostrarFormulario);
    document
        .querySelector("#mnuListadoGeneral")
        .addEventListener("click", procesarListadoVentas); 

    if (typeof frmAltaVenta !== 'undefined') {
        frmAltaVenta.btnAceptarAltaVenta.addEventListener("click", procesarAltaVenta);
    }
}

function mostrarFormulario(oEvento) {
  let opcionMenu = oEvento.target.id; 

  ocultarFormularios();

  switch (opcionMenu) {

    case "mnuAltaVenta":
      frmAltaVenta.classList.remove("d-none");

      actualizarDesplegableProductos(); 
      break;
  }
}

function ocultarFormularios() {

    if (typeof frmAltaVenta !== 'undefined') {
        frmAltaVenta.classList.add("d-none");
    }

  document.querySelector("#resultadoBusqueda").innerHTML = "";
  document.querySelector("#listados").innerHTML = "";
}


async function actualizarDesplegableProductos() {
    const frmAltaVenta = document.querySelector("#frmAltaVenta");
    if (!frmAltaVenta) return;
    
    let selectElement = frmAltaVenta.lstIdProduct;
    let options = "<option value=''>Seleccionar producto...</option>";
    selectElement.innerHTML = options; // Limpiar y poner la opción por defecto

    try {
        // La llamada a oEmpresa.getProductos() debe ir a productos.php
        let respuesta = await oEmpresa.getProductos();
        
        // Verificamos si la respuesta es válida y tiene el array de datos
        if (respuesta && respuesta.ok && Array.isArray(respuesta.datos)) {
            for (let prod of respuesta.datos) {
                options += 
                    "<option value='" + prod.id_product + "'>" + 
                    prod.product_name + " (€" + parseFloat(prod.price).toFixed(2) + ")" + 
                    "</option>";
            }
        } else {
            console.error("Error al obtener productos:", respuesta.mensaje || "Respuesta incompleta.");
            options = "<option value=''>Error al cargar productos</option>";
        }
    } catch (error) {
        // Capturar errores de red o del motor AJAX
        console.error("Error en la petición de productos:", error);
        options = "<option value=''>Error de red al cargar productos</option>";
    }
    
    selectElement.innerHTML = options;
}


async function procesarAltaVenta() {

    let sale_date = frmAltaVenta.txtSaleDate.value.trim(); 
    let product_quantity = parseInt(frmAltaVenta.txtProductQuantity.value.trim());
    let id_product = parseInt(frmAltaVenta.lstIdProduct.value);
    let address = frmAltaVenta.txtAddress.value.trim();
    let online_sale = frmAltaVenta.chkOnlineSale.checked ? 1 : 0; 


    if (validarAltaVenta(sale_date, product_quantity, id_product)) {
        

        let oVenta = new Venta(null, sale_date, product_quantity, id_product, online_sale, address);

        let respuesta = await oEmpresa.altaVenta(oVenta);


        alert(respuesta.mensaje); 

        if (respuesta.ok) {

            frmAltaVenta.reset();
            frmAltaVenta.classList.add("d-none");
        }
    }
}

function validarAltaVenta(sale_date, product_quantity, id_product) {
    let valido = true;
    let errores = [];

    if (!sale_date) {
        errores.push("La fecha y hora de venta son obligatorias.");
        valido = false;
    }
    if (isNaN(product_quantity) || product_quantity <= 0) {
        errores.push("La cantidad debe ser un número entero positivo.");
        valido = false;
    }
    if (isNaN(id_product)) {
        errores.push("Debe seleccionar un producto.");
        valido = false;
    }

    if (!valido) {
        alert("Errores en el formulario de Venta:\n" + errores.join("\n"));
    }
    return valido;
}


async function procesarListadoVentas() {
    ocultarFormularios();
    
    let respuesta = await oEmpresa.listadoVentas();

    let listado = '<h2>Listado de Ventas</h2>';
    
    if (respuesta.ok && Array.isArray(respuesta.datos)) {
        listado += '<table class="table table-striped" id="listadoVentas">';
        listado += "<thead><tr><th>ID</th><th>Fecha</th><th>Cant.</th><th>ID Prod.</th><th>Online</th><th>Dirección</th><th>Acción</th></tr></thead>";
        listado += "<tbody>";

        for (let ventaData of respuesta.datos) { 
            listado += "<tr><td>" + ventaData.id_sale + "</td>";
            listado += "<td>" + ventaData.sale_date.substring(0, 16) + "</td>"; 
            listado += "<td>" + ventaData.product_quantity + "</td>";
            listado += "<td>" + ventaData.id_product + "</td>";
            listado += "<td>" + (ventaData.online_sale == 1 ? "Sí" : "No") + "</td>";
            listado += "<td>" + (ventaData.address || '-') + "</td>";
            listado += 
                "<td><button class='btn btn-danger btn-sm btn-borrar-venta' data-id-sale='" + ventaData.id_sale + 
                "'><i class='bi bi-trash'></i> Borrar</button></td></tr>";
        }

        listado += "</tbody></table>";
        
    } else {
        listado += "<p class='alert alert-warning'>Error al cargar el listado de ventas: " + (respuesta.mensaje || "Datos no disponibles") + "</p>";
    }

    document.querySelector("#listados").innerHTML = listado;
    

    if (document.querySelector("#listadoVentas")) {
        document.querySelector("#listadoVentas").addEventListener("click", function(oEvento) {
            let boton = oEvento.target.closest('.btn-borrar-venta');
            if (boton) {
                procesarBorrarVenta(boton.dataset.idSale);
            }
        });
    }
}

async function procesarBorrarVenta(id_sale) {
    if (confirm(`¿Estás seguro de que deseas borrar la Venta #${id_sale}?`)) {
        let respuesta = await oEmpresa.borrarVenta(id_sale);

        alert(respuesta.mensaje);

        if (respuesta.ok) {
            procesarListadoVentas(); 
        }
    }
}