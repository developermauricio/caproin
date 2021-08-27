<template>
  <div>
    Order preview
  </div>
</template>

<script>
export default {
  name: "OrderPreview",
  props: {
    purchase_order: {
      type: Object,
      require: true,
    },
  },
};
</script>

<style scope>
.history {
  position: relative;
  padding-top: 1rem;
}

.history:not(:nth-child(1)) {
  border-top: 2px solid #f3f2ee;
}

.icon--close {
  position: absolute;
  top: 0;
  left: 0;
  color: rgb(240, 94, 125);
  z-index: 30;
  cursor: pointer;
}
</style>
