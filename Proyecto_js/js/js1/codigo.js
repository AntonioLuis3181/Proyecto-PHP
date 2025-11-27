"use strict";

// main
var oGestor = new GestorTienda();



registrarEventos();

function registrarEventos() {
  // Opciones de menú
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
  // Botones
  frmAltaCategoria.btnAltaCategoria.addEventListener("click", altaCategoria);
  frmBuscarPorNombre.btnBuscarPorNombre.addEventListener(
    "click",
    procesarBuscarPorNombre
  );
  frmListadoParametrizado.btnListadoParametrizado.addEventListener(
    "click",
    listadoCategoriasParametrizado
  );
}

function mostrarFormularios(oEvento) {
  let opcion = oEvento.target.id;

  ocultarFormularios();
  ocultarFormularios2();

  switch (opcion) {
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
  frmAltaCategoria.classList.add("d-none");
  frmBuscarPorNombre.classList.add("d-none");
  frmListadoParametrizado.classList.add("d-none");
  document.querySelector("#Resultados").innerHTML = "";
  document.querySelector("#resultadoListado").innerHTML = "";
  document.querySelector("#resultadoParametrizado").innerHTML = "";
}

async function cargarDesplegables() {
  let respuesta = await oGestor.getGeneros();

  if (respuesta.ok) {
    let options = "";
    for (let fila_categoria of respuesta.datos) {
      options += "<option value='" + fila_categoria.id_season + "'>";
      options += fila_categoria.season_name + "</option>";
    }

    frmAltaCategoria.id_season.innerHTML = options;
  } else {
    alert(respuesta.mensaje);
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
