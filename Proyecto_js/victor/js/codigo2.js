"use strict";

// main
var oGestor = new GestorTienda();
var idgenero = 0;

registrarEventos();

function registrarEventos() {
    // Opciones de menú

    document
    .querySelector("#mnualtaproducto")
    .addEventListener("click",mostrarFormularios)
    document
    .querySelector("#mnugestionproducto")
    .addEventListener("click",mostrarFormularios)

    // Botones
    
    frmaltaproducto.btnAltaProducto.addEventListener("click",altaProducto);
    frmbuscarproducto.btnbuscarnombre.addEventListener("click",buscarnombreProducto);
    

    
}

function mostrarFormularios(oEvento) {
  let opcion = oEvento.target.id;


  ocultarFormularios();

  switch (opcion) {
    case "mnualtaproducto":
      frmaltaproducto.classList.remove("d-none");
       cargarCategorias(frmaltaproducto.lstCategoria);
      break;
    case "mnugestionproducto":
    document.getElementById("div_gestion_product").classList.remove("d-none");
     listadoProductos();
     cargarCategorias(frmGestionProducto.id_categoria)

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

async function cargarCategorias(oCategoria) {

    let respuesta = await oGestor.getCategorias();
    

  if (respuesta.ok) {
    let options = "";
    for (let fila_categoria of respuesta.datos) {
        options += "<option value='" + fila_categoria.id_category + "'>";
        options += fila_categoria.category_name + "</option>";
    }

    oCategoria.innerHTML = options;
    

  } else {
    alert(respuesta.mensaje);
  }
}

async function listadoProductos() {
  
  let respuesta = await oGestor.getProductos();
    

  if (respuesta.ok) {
    let options = "";
    for (let fila_producto of respuesta.datos) {
        options+="<tr>";
        options+="<td>"+fila_producto.id_product+"</td>"
        options+="<td>"+fila_producto.product_name+"</td>"
        options+="<td>"+fila_producto.price+"</td>"
        options+="<td>"+fila_producto.category_name+"</td>"
        options+="<td>"+fila_producto.in_stock+"</td>"
        options+="<td>"+fila_producto.registration_date+"</td>"

        options += "<td><form class='d-inline me-1' action='productos.php' method='post'>";
        options += "<input type='hidden' name='producto' value='" + fila_producto + "' />";
        options += "<button name='Editar' class='btn btn-primary'><i class='bi bi-pencil-square'></i></button></form>";

        options += "<form class='d-inline'>";
        options += "<button name='borrar' class='btn btn-danger' onclick='borrarproducto("+fila_producto.id_product+")'><i class='bi bi-trash'></i></button></form>";

        options +="</td> </tr>"
    }

    document.querySelector("#tabla_listado tbody").innerHTML = options;
    

  } else {
    alert(respuesta.mensaje);
  }
}

async function borrarproducto(id_product) {
  let respuesta = await oGestor.deleteproduct(id_product);
    

  if (respuesta.ok) {

    alert(respuesta.mensaje);
    listadoProductos();

  }else {
    alert(respuesta.mensaje);
  }

}

async function altaProducto() {
  let nombre = frmaltaproducto.txtNombre.value.trim();
  let stock = frmaltaproducto.txtStock.checked ? 1 : 0;
  let precio = frmaltaproducto.txtPrecio.value.trim();
  let categorias=frmaltaproducto.lstCategoria.value;

  let producto = new product(null, nombre, precio, categorias, stock,null);

 let respuesta = await oGestor.crearProducto(producto);

    alert(respuesta.mensaje);

    if(respuesta.ok){
        frmaltaproducto.reset();
        frmaltaproducto.classList.add("d-none");
    }
}

async function buscarnombreProducto(oEvento) {
  oEvento.preventDefault()
  let nombre=frmbuscarproducto.buscar_nombre.value.trim()
  let respuesta = await oGestor.getproductosfiltrados(nombre);
    

  if (respuesta.ok) {
    let options = "";
    if(respuesta.datos!=null){
    for (let fila_producto of respuesta.datos) {
        options+="<tr>";
        options+="<td>"+fila_producto.id_product+"</td>"
        options+="<td>"+fila_producto.product_name+"</td>"
        options+="<td>"+fila_producto.price+"</td>"
        options+="<td>"+fila_producto.category_name+"</td>"
        options+="<td>"+fila_producto.in_stock+"</td>"
        options+="<td>"+fila_producto.registration_date+"</td>"

        options +="</tr>"
    }
  }

    document.querySelector("#tablafiltronombreproducto tbody").innerHTML = options;
    

  } else {
    alert(respuesta.mensaje);
  }
}

async function procesarBuscarPorNombre(){
    let nombre = frmBuscarPorNombre.buscar_nombre.value.trim();

    let respuesta = await oGestor.buscarCategoriaPorNombre(nombre);
    let lista = '<table class="table table-striped">';
    lista += '<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Fecha Alta</th><th>Likes</th><th>Disponible Estacionalmente</th><th>ID Temporada</th></tr>';
    lista +="<tbody>";
    for(let categoria of respuesta.datos){
        lista += "<tr><td>" + categoria.id_category + "</td>";
        lista += "<td>" + categoria.category_name + "</td>";
        lista += "<td>" + categoria.description + "</td>";
        lista += "<td>" + categoria.creation_date + "</td>";
        lista += "<td>" + categoria.like_count + "</td>";
        lista += "<td>" + (categoria.seasonal_product_available ? "Sí" : "No") + "</td>";
        lista += "<td>" + categoria.id_season + "</td></tr>";

    }
    lista += "</tbody></table>";
    document.querySelector("#resultadoBusqueda").innerHTML = lista;
     }
 async function listadoCategorias(){

  let respuesta = await oGestor.listarCategorias();

   let listado = '<table class="table table-striped">';

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
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
  `;

  for (let categoria of respuesta.datos) {
    
    listado += `
      <tr>
        <td>${categoria.id_category}</td>
        <td>${categoria.category_name}</td>
        <td>${categoria.description}</td>
        <td>${categoria.creation_date}</td>
        <td>${categoria.like_count}</td>
        <td>${categoria.seasonal_product_available ? "Sí" : "No"}</td>
        <td>${categoria.id_season}</td>
        <td>
          <button name="Editar" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil-square"></i>
          </button>
          <button name="Borrar" id = "Borrar" class="btn btn-danger btn-sm">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      </tr>
    `;

    
  }

  listado += "</tbody></table>";

  document.querySelector("#resultadoListado").innerHTML = listado;
  
    

}

async function CargarParametrizado(){
    
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

async function listadoCategoriasParametrizado(){
    let id_season = frmListadoParametrizado.listado_season.value.trim();

    let respuesta = await oGestor.listarCategoriasParametrizado(id_season);
  let lista = '<table class="table table-striped">';
      lista += '<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Fecha Alta</th><th>Likes</th><th>Disponible Estacionalmente</th></tr>';
      lista +="<tbody>";
      for(let categoria of respuesta.datos){
          lista += "<tr><td>" + categoria.id_category + "</td>";
          lista += "<td>" + categoria.category_name + "</td>";
          lista += "<td>" + categoria.description + "</td>";
          lista += "<td>" + categoria.creation_date + "</td>";
          lista += "<td>" + categoria.like_count + "</td>";
          lista += "<td>" + (categoria.seasonal_product_available ? "Sí" : "No") + "</td>";
          "</td></tr>";

      }
      lista += "</tbody></table>";
      document.querySelector("#resultadoParametrizado").innerHTML = lista;


    return respuesta;
}


     
    

