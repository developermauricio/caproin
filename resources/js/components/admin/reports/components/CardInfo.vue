<template>
  <div class="card">
    <slot></slot>
  </div>
</template>
<script>
export default {
  name: "CardInfo"
}
</script>
<style scoped>
.card{
  border-radius: .6rem;
  padding: 1rem;
  background-color: #00548b;
  border: 1px solid var(--gray);
}
.card *{
  color: #ffffff;
}
</style>
