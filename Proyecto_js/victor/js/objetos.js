"use strict";

class category {
  #id_category;
  #category_name;
  #description;
  #creation_date;
  #like_count;
  #seasonal_product_available;
  #id_season;

  constructor(
    id_category,
    category_name,
    description,
    creation_date,
    like_count,
    seasonal_product_available,
    id_season
  ) {
    this.#id_category = id_category;
    this.#category_name = category_name;
    this.#description = description;
    this.#creation_date = creation_date;
    this.#like_count = like_count;
    this.#seasonal_product_available = seasonal_product_available;
    this.#id_season = id_season;
  }
  get id_category() {
    return this.#id_category;
  }
  get category_name() {
    return this.#category_name;
  }
  get description() {
    return this.#description;
  }
  get creation_date() {
    return this.#creation_date;
  }
  get like_count() {
    return this.#like_count;
  }
  get seasonal_product_available() {
    return this.#seasonal_product_available;
  }
  get id_season() {
    return this.#id_season;
  }
  set id_category(valor) {
    this.#id_category = valor;
  }
  set category_name(valor) {
    this.#category_name = valor;
  }
  set description(valor) {
    this.#description = valor;
  }
  set creation_date(valor) {
    this.#creation_date = valor;
  }
  set like_count(valor) {
    this.#like_count = valor;
  }
  set seasonal_product_available(valor) {
    this.#seasonal_product_available = valor;
  }
  set id_season(valor) {
    this.#id_season = valor;
  }
  toJSON() {
    return {
      id_category: this.#id_category,
      category_name: this.#category_name,
      description: this.#description,
      creation_date: this.#creation_date,
      like_count: this.#like_count,
      seasonal_product_available: this.#seasonal_product_available,
      id_season: this.#id_season,
    };
  }
}

class season {
  #id_season;
  #season_name;

  constructor(id_season, season_name) {
    this.#id_season = id_season;
    this.#season_name = season_name;
  }

  get id_season() {
    return this.#id_season;
  }
  get season_name() {
    return this.#season_name;
  }
  set id_season(valor) {
    this.#id_season = valor;
  }
  set season_name(valor) {
    this.#season_name = valor;
  }
  toJSON() {
    return {
      id_season: this.#id_season,
      season_name: this.#season_name,
    };
  }
}

class GestorTienda {
  async getGeneros() {
    let datos = new FormData();

    let respuesta = await peticionGET("get_categorias.php", datos);

    return respuesta;
  }

  async getCategorias() {
    let datos = new FormData();

    let respuesta = await peticionGET("get_categorias2.php", datos);

    return respuesta;
  }

  async crearCategoria(oCategoria) {
    let datos = new FormData();
    datos.append("categoria", JSON.stringify(oCategoria));
    let respuesta = await peticionPOST("alta_categoria.php", datos);

    return respuesta;
  }

  async crearProducto(oProducto) {
    let datos = new FormData();
    datos.append("producto", JSON.stringify(oProducto));
    let respuesta = await peticionPOST("alta_producto.php", datos);

    return respuesta;
  }

  async buscarCategoriaPorNombre(nombre) {
    let datos = new FormData();

    datos.append("nombre", nombre);
    let respuesta = await peticionGET("buscar_por_nombre.php", datos);

    return respuesta;
  }

  async listarCategorias() {
    let datos = new FormData();

    let respuesta = await peticionGET("listado_categoria.php", datos);

    return respuesta;
  }

  listarCategoriasParametrizado(id_season) {
    let datos = new FormData();
    datos.append("id_season", id_season);
    let respuesta = peticionGET("listado_parametrizado.php", datos);

    return respuesta;
  }

  async borrarComponente(idComponente) {
    let datos = new FormData();

    datos.append("idcomponente", idComponente);

    let respuesta = await peticionPOST("borrar_componente.php", datos);

    return respuesta;
  }

  async getProductos(){

    let datos = new FormData();

    let respuesta = await peticionGET("listado_producto.php", datos);

    return respuesta;
  }

   getproductosfiltrados(nombre) {
    let datos = new FormData();
    datos.append("product_name", nombre);
    let respuesta = peticionGET("productosnombre.php", datos);

    return respuesta;
  }

  async deleteproduct(id_product) {
    let datos = new FormData();

    datos.append("id_product", id_product);

    let respuesta = await peticionPOST("borrar_producto.php", datos);

    return respuesta;
  }
}
