class product {
  #id_product;
  #product_name;
  #price;
  #id_category;
  #in_stock;
  #registration_date;

  constructor(
    id_product,
    product_name,
    price,
    id_category,
    in_stock,
    registration_date
  ) {
    this.#id_product = id_product;
    this.#product_name = product_name;
    this.#price = price;
    this.#id_category = id_category;
    this.#in_stock = in_stock;
    this.#registration_date = registration_date;
  }
  get id_product() {
    return this.#id_product;
  }
  get product_name() {
    return this.#product_name;
  }
  get price() {
    return this.#price;
  }
  get id_category() {
    return this.#id_category;
  }
  get in_stock() {
    return this.#in_stock;
  }
  get registration_date() {
    return this.#registration_date;
  }
  set id_product(valor) {
    this.#id_product = valor;
  }
  set product_name(valor) {
    this.#product_name = valor;
  }
  set price(valor) {
    this.#price = valor;
  }
  set id_category(valor) {
    this.#id_category = valor;
  }
  set in_stock(valor) {
    this.#in_stock = valor;
  }
  set registration_date(valor) {
    this.#registration_date = valor;
  }

  toJSON() {
    return {
      id_product: this.#id_product,
      product_name: this.#product_name,
      price: this.#price,
      id_category: this.#id_category,
      in_stock: this.#in_stock,
      registration_date: this.#registration_date,
      };
  }
}