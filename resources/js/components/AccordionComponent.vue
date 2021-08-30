<template>
  <div class="collapse-margin collapse-icon">
    <accordion-item
      v-for="(item, index) in items"
      :key="item.id"
      :item="item"
      :index="index"
      :isOpen="isCurrent(item)"
      @change-current="changeCurrent"
    >
      <template v-slot:title>
        <slot name="title" v-bind:item="item" v-bind:index="index">
          {{ item.title }}
        </slot>
      </template>
      <template v-slot:default>
        <slot v-bind:item="item" v-bind:index="index"></slot>
      </template>
    </accordion-item>
  </div>
</template>
<script>
import AccordionItem from "./AccordionItem.vue";
export default {
  components: { AccordionItem },
  name: "AccordionComponent",
  data() {
    return {
      current: {
        id: null,
      },
    };
  },
  props: {
    items: {
      type: Array,
      require: true,
    },
  },
  methods: {
    changeCurrent(item) {
      if (this.current.id === item.id) {
        this.current = Object.assign({}, this.current, {
          id: null,
        });
      } else {
        this.current = item;
      }
    },
    isCurrent(item) {
      return this.current && item.id === this.current.id;
    },
  },
};
</script>
