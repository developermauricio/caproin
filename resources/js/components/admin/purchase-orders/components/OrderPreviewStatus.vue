<template>
  <div class="status__container">
    <div
      class="status--item"
      v-for="(history, index) in purchase_order_state_histories"
      :key="JSON.stringify(history)"
    >
      <h2>{{index+1}}. {{ state_order(history) }}</h2>
      <p><strong>Estimado:</strong> {{ estimated_date(history) }}</p>
      <p>{{ history.description }}</p>
    </div>purchase_order_state_histories
  </div>
</template>

<script>
export default {
  name: "OrderPreviewStatus",
  props: {
    purchase_order_state_histories: {
      type: Array,
      require: true,
    },
  },
  methods: {
    state_order(history) {
      if (!history.state_order) {
        return "Sin estado";
      }
      return history.state_order.name;
    },
    getMonth(date) {
      return ("0" + (date.getMonth() + 1)).slice(-2);
    },
    getDay(date) {
      return ("0" + date.getDate()).slice(-2);
    },
    estimated_date(history) {
      if (!history.estimated_date || !history.estimated_date.getFullYear) {
        return history.estimated_date.slice(0, 10);
      }
      const date = history.estimated_date;
      return `${date.getFullYear()}-${this.getMonth(date)}-${this.getDay(
        date
      )}`;
    },
  },
};
</script>
<style scoped>
.status--item:not(:last-child){
  border-bottom: 1px solid;
  margin-bottom: 1rem;
}
</style>
