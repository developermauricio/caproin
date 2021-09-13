<template>
  <div>
    <accordion-component :items="order_details">
      <template v-slot:title="{ item: order_detail, index }">
        <div class="header">
          <i
            class="icon--close"
            @click="removeOrderDetail(index)"
            v-html="closeIcon"
          ></i>
          <span class="header__title">
            {{ order_detail.product.name }} - {{ order_detail.product.code }}
          </span>
          <span class="header__price">
            <strong>Precio unitario: </strong>
            {{ order_detail.value | price }}
            <strong class="pl-1">Cantidad: </strong>
            {{ order_detail.quantity }}
            <strong class="pl-1">Total: </strong>
            {{ order_detail.total_value | price }}
          </span>
        </div>
      </template>
      <template v-slot:default="{ item: order_detail }">
        <order-detail
          :errors="errors"
          :order_detail="order_detail"
          :currency="currency"
          :type_currencies="type_currencies"
          :type_products="type_products"
        />
      </template>
    </accordion-component>

    <accordion-component :items="[{ title: 'Añadir productos' }]">
      <template v-slot:default>
        <vue-good-table
          :pagination-options="{
            enabled: true,
            mode: 'records',
            perPage: 10,
            position: 'top',
            perPageDropdown: [3, 7, 9],
            dropdownAllowAll: false,
            setCurrentPage: 2,
            nextLabel: 'Siguiente',
            prevLabel: 'Anterior',
            rowsPerPageLabel: 'Filas por página',
            ofLabel: 'de',
            pageLabel: 'page', // for 'pages' mode
            allLabel: 'All',
            // infoFn: (params) => `my own page ${params.firstRecordOnPage}`,
          }"
          :columns="columns"
          :rows="remainingProducts"
          :search-options="{
            enabled: true,
            placeholder: 'Buscar Productos o Servicios',
          }"
        >
          <template slot="table-row" slot-scope="props">
            <p v-if="props.column.field == 'description'">
              {{ shortText(props.row.description) }}
            </p>
            <button
              v-else-if="props.column.field == 'btn'"
              @click="addProducts(props.row)"
              type="button"
              class="btn btn-success"
            >
              Agregar Producto
            </button>
          </template>
        </vue-good-table>
      </template>
    </accordion-component>
  </div>
</template>

<script>
import OrderDetail from "./OrderDetail.vue";
export default {
  components: { OrderDetail },
  name: "OrderDetails",
  props: {
    errors: {
      type: Object,
      default: function () {
        return {};
      },
    },
    order_details: {
      type: Array,
      require: true,
      default: function () {
        return [];
      },
    },
    currency: {
      type: Object,
      require: true,
    },
    type_currencies: {
      type: Array,
      default: function () {
        return [];
      },
    },
    type_products: {
      type: Array,
      default: function () {
        return [];
      },
    },
  },
  data() {
    return {
      order_detail: {
        product_id: 0,
        internal_product_code: 0,
        manufacturer: "",
        client_product_code: "",
        customer_product_description: "",
        application: "",
        blueprint_number: "",
        blueprint_file: "",
        quantity: 1,
        total_value: 0,
        value: "",
        // internal_quote_number: "",
        house_quote_number: "",
        currency_id: null,
        product: null,
        currency: null,
      },
      products: [],
      columns: [
        {
          label: "Código",
          field: "code",
        },
        {
          label: "Tipo",
          field: "product_type.name",
        },
        {
          label: "Nombre",
          field: "name",
        },
        {
          label: "Descripción",
          field: "description",
          sortable: false,
        },
        {
          label: "Acciones",
          field: "btn",
          html: true,
        },
      ],
    };
  },
  created() {
    this.getApiProducts();
  },
  computed: {
    closeIcon() {
      return feather.icons["x"].toSvg();
    },
    remainingProducts() {
      return this.products.filter(
        (product) =>
          !this.order_details.find((order) => order.product_id == product.id)
      );
    },
  },
  methods: {
    removeOrderDetail(index) {
      this.order_details.splice(index, 1);
    },
    addProducts(product) {
      if (!product) {
        return;
      }
      this.order_details.push(
        Object.assign({}, this.order_detail, {
          product_id: product.id,
          internal_product_code: product.code,
          product: product,
        })
      );
    },
    getApiProducts() {
      axios.get("/api/all-products").then((resp) => {
        this.products = resp.data.data;
      });
    },
    shortText(text) {
      if (text.length > 60) {
        return text.slice(0, 60) + "...";
      }
      return text;
    },
  },
};
</script>
<style scoped>
.icon--close {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}

.header__title {
  padding-left: 1.3rem;
}

.header__price {
  position: absolute;
  right: 0.5rem;
}

.header {
  position: relative;
  width: 100%;
}
</style>
