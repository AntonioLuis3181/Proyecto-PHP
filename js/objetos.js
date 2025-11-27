class Venta {
    #id_sale;
    #sale_date;
    #product_quantity;
    #id_product;
    #online_sale;
    #address;

    constructor(id_sale, sale_date, product_quantity, id_product, online_sale, address) {
        this.#id_sale = id_sale;
        this.#sale_date = sale_date;
        this.#product_quantity = product_quantity;
        this.#id_product = id_product;
        this.#online_sale = online_sale;
        this.#address = address;
    }

    get id_sale() { return this.#id_sale; }
    get sale_date() { return this.#sale_date; }
    get product_quantity() { return this.#product_quantity; }
    get id_product() { return this.#id_product; }
    get online_sale() { return this.#online_sale; }
    get address() { return this.#address; }
    
    /**
     * Prepara los datos para ser enviados al backend (sale.php) en el formato que espera.
     * @returns {FormData}
     */
    toFormData() {
        let datos = new FormData();
        datos.append("action", "crear"); // Parámetro POST esperado por sale.php
        datos.append("sale_date", this.#sale_date);
        datos.append("product_quantity", this.#product_quantity);
        datos.append("id_product", this.#id_product);
        datos.append("address", this.#address);
        datos.append("online_sale", this.#online_sale); // 1 o 0
        return datos;
    }
}
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

  async crearCategoria(oCategoria) {
    let datos = new FormData();
    datos.append("categoria", JSON.stringify(oCategoria));
    let respuesta = await peticionPOST("alta_categoria.php", datos);

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
  async borrarCategoria(idCategory) {
    let datos = new FormData();

    datos.append("idCategory", idCategory);

    let respuesta = await peticionPOST("borrar_categoria.php", datos);

    return respuesta;
  }
}


class Empresa {


    async altaVenta(oVenta) {
        // oVenta.toFormData() prepara el FormData con los campos esperados por sale.php
        let datos = oVenta.toFormData();
        
        // Usa peticionPOST para la alta de venta
        let respuesta = await peticionPOST("sale.php", datos);
        
        return respuesta;
    }


    async listadoVentas() {
        let datos = new FormData();
        // Usa peticionGET para obtener el listado de ventas
        let respuesta = await peticionGET("listadoVentas.php", datos);
        return respuesta;
    }

    async borrarVenta(id_sale) {
        let datos = new FormData();
        datos.append("accion", "borrar");
        datos.append("id_sale", id_sale);


        let respuesta = await peticionPOST("listadoVentas.php", datos);

        return respuesta;
    }

     async getProductos() {
        let datos = new FormData();
        // Usaremos un nombre genérico. Si necesitas crear este archivo, usa la query de sale.php.
        let respuesta = await peticionGET("productos.php", datos); 
        return respuesta;
     }
}
