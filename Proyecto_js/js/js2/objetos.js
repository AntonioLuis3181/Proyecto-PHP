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


// ====================================================================
// CLASE DE ACCESO A DATOS (Empresa)
// ====================================================================

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