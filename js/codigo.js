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
    document
        .querySelector("#mnualtacategoria")
        .addEventListener("click", mostrarFormularios);
    document
        .querySelector("#mnulistadocategorias")
        .addEventListener("click", mostrarFormularios);
    document
        .querySelector("#mnubuscarpornombre")
        .addEventListener("click", mostrarFormularios);
    document
    .querySelector("#mnulistadoparametrizado")
    .addEventListener("click", mostrarFormularios);

    if (typeof frmAltaVenta !== 'undefined') {
        frmAltaVenta.btnAceptarAltaVenta.addEventListener("click", procesarAltaVenta);
        frmAltaCategoria.btnAltaCategoria.addEventListener("click", altaCategoria);
        frmBuscarPorNombre.btnBuscarPorNombre.addEventListener("click",procesarBuscarPorNombre);
        frmListadoParametrizado.btnListadoParametrizado.addEventListener("click",listadoCategoriasParametrizado);
    }
}

function mostrarFormulario(oEvento) {
  let opcionMenu = oEvento.target.id; 

  ocultarFormularios();

  switch (opcionMenu) {

    case "mnuAltaVenta":
      frmAltaVenta.classList.remove("d-none");

      actualizarDesplegableProductos(); 
    case "mnualtacategoria":
        frmAltaCategoria.classList.remove("d-none");
        cargarDesplegables();
        break;
    case "mnulistadocategorias":
        listadoCategorias();
        break;
    case "mnubuscarpornombre":
        frmBuscarPorNombre.classList.remove("d-none");
        break;
    case "mnulistadoparametrizado":
        frmListadoParametrizado.classList.remove("d-none");
        CargarParametrizado();
        break;
  }
}

function ocultarFormularios() {

    if (typeof frmAltaVenta !== 'undefined') {
        frmAltaVenta.classList.add("d-none");
    }

  document.querySelector("#resultadoBusqueda").innerHTML = "";
  document.querySelector("#listados").innerHTML = "";
  frmAltaCategoria.classList.add("d-none");
  frmBuscarPorNombre.classList.add("d-none");
  frmListadoParametrizado.classList.add("d-none");
  document.querySelector("#Resultados").innerHTML = "";
  document.querySelector("#resultadoListado").innerHTML = "";
  document.querySelector("#resultadoParametrizado").innerHTML = "";
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
async function altaCategoria() {
  let nombre = frmAltaCategoria.category_name.value.trim();
  let descripcion = frmAltaCategoria.description.value.trim();
  let id_season = frmAltaCategoria.id_season.value.trim();
  let seasonalAvailable = frmAltaCategoria.seasonal_product_available.checked
    ? 1
    : 0;
  let likes = parseInt(frmAltaCategoria.likes.value);
  console.log("id_season:", id_season, typeof id_season);

  let categoria = new category(
    null,
    nombre,
    descripcion,
    null,
    likes,
    seasonalAvailable,
    id_season
  );

  let respuesta = await oGestor.crearCategoria(categoria);

  alert(respuesta.mensaje);

  if (respuesta.ok) {
    frmAltaCategoria.reset();
    frmAltaCategoria.classList.add("d-none");
  }
}

async function procesarBuscarPorNombre() {
  let nombre = frmBuscarPorNombre.buscar_nombre.value.trim();

  let respuesta = await oGestor.buscarCategoriaPorNombre(nombre);
  let lista = '<table class="table table-striped">';
  lista +=
    "<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Fecha Alta</th><th>Likes</th><th>Disponible Estacionalmente</th><th>ID Temporada</th></tr>";
  lista += "<tbody>";
  for (let categoria of respuesta.datos) {
    lista += "<tr><td>" + categoria.id_category + "</td>";
    lista += "<td>" + categoria.category_name + "</td>";
    lista += "<td>" + categoria.description + "</td>";
    lista += "<td>" + categoria.creation_date + "</td>";
    lista += "<td>" + categoria.like_count + "</td>";
    lista +=
      "<td>" + (categoria.seasonal_product_available ? "Sí" : "No") + "</td>";
    lista += "<td>" + categoria.id_season + "</td></tr>";
  }
  lista += "</tbody></table>";
  document.querySelector("#Resultados").innerHTML = lista;
}
async function listadoCategorias() {
  let respuesta = await oGestor.listarCategorias();

  let listado = '<table class="table table-striped" id ="tablaCategorias">';

  listado += `
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Fecha Alta</th>
        <th>Likes</th>
        <th>Disponible Estacionalmente</th>
        <th>ID Temporada</th>
        <th>Eliminar</th>
      </tr>
    </thead>
    <tbody>
  `;

  for (let categoria of respuesta.datos) {
    listado += "<tr><td>" + categoria.id_category + "</td>";
    listado += "<td>" + categoria.category_name + "</td>";
    listado += "<td>" + categoria.description + "</td>";
    listado += "<td>" + categoria.creation_date + "</td>";
    listado += "<td>" + categoria.like_count + "</td>";
    listado += "<td>" + categoria.seasonal_product_available + "</td>";
    listado += "<td>" + categoria.id_season + "</td>";
    listado +=
      "<td><button class='btn btn-danger' data-id_category='" +
  categoria.id_category +
  "'><i class='bi bi-trash' ></i></button></td></tr>";
  }

  listado += "</tbody></table>";

  document.querySelector("#resultadoListado").innerHTML = listado;
  document
    .querySelector("#tablaCategorias")
    .addEventListener("click", procesarBorrarCategoria);
}

async function procesarBorrarCategoria(oEvento) {
  let boton = oEvento.target;
  if (oEvento.target.nodeName == "I" || oEvento.target.nodeName == "BUTTON") {
    if (oEvento.target.nodeName == "I") {
      // Pulsacion sobre el icono
      boton = oEvento.target.parentElement; // El padre es el boton
    } else {
      boton = oEvento.target;
    }
    let idCategory = boton.dataset.id_category;
    console.log(idCategory);
    let respuesta = await oGestor.borrarCategoria(idCategory);

    alert(respuesta.mensaje);

    if (respuesta.ok) {
      // Si NO hay error
      // Borrado de la tabla html
      let borrado = document.querySelector("#tablaCategorias").innerHTML = "";

      if (borrado) borrado.remove();
      
      
    }
  }
}


async function CargarParametrizado() {
  let respuesta = await oGestor.getGeneros();

  if (respuesta.ok) {
    let options = "";
    for (let fila_categoria of respuesta.datos) {
      options += "<option value='" + fila_categoria.id_season + "'>";
      options += fila_categoria.season_name + "</option>";
    }

    frmListadoParametrizado.listado_season.innerHTML = options;
  } else {
    alert(respuesta.mensaje);
  }
}

async function listadoCategoriasParametrizado() {
  let id_season = frmListadoParametrizado.listado_season.value.trim();

  let respuesta = await oGestor.listarCategoriasParametrizado(id_season);
  let lista = '<table class="table table-striped">';
  lista +=
    "<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Fecha Alta</th><th>Likes</th><th>Disponible Estacionalmente</th></tr>";
  lista += "<tbody>";
  for (let categoria of respuesta.datos) {
    lista += "<tr><td>" + categoria.id_category + "</td>";
    lista += "<td>" + categoria.category_name + "</td>";
    lista += "<td>" + categoria.description + "</td>";
    lista += "<td>" + categoria.creation_date + "</td>";
    lista += "<td>" + categoria.like_count + "</td>";
    lista +=
      "<td>" + (categoria.seasonal_product_available ? "Sí" : "No") + "</td>";
    ("</td></tr>");
  }
  lista += "</tbody></table>";
  document.querySelector("#resultadoParametrizado").innerHTML = lista;

  return respuesta;
}

