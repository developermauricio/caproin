<template>
  <div>
    <table class="table">
      <thead>
        <tr>
          <th>Moneda</th>
          <th>Valor</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product_price in product_prices" :key="product_price.currency_id">
          <td>{{ getCode(product_price.currency_id) }}</td>
          <td>
            <input-form
              type="money"
              id="txt_price"
              pattern="all"
              errorMsg="Ingrese precio válido"
              requiredMsg="La precio es obligatorio"
              :required="true"
              :modelo.sync="product_price.price"
              :showLabel="false"
              :money="money"
            >
            </input-form>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
export default {
  name: "ProductPrice",
  data() {
    return {
      type_currencies: [],
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "$ ",
        suffix: "",
        precision: 0,
      },
    };
  },
  props: {
    product_prices: {
      type: Array,
    },
  },
  mounted() {
    this.getData();
  },
  methods: {
    async getData() {
      this.type_currencies = (await axios.get("/api/all-coin-type-list")).data;
      this.type_currencies.forEach((type) => {
        if (!this.product_prices.find(product => product.currency_id === type.id)) {
          this.product_prices.push({
            price: 0,
            currency_id: type.id,
          });
        }
      });
    },
    getCode(type_id){
      return this.type_currencies.find(type => type.id === type_id).code
    }
  },
};
</script>
