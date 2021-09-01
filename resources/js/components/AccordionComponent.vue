<template>
  <div class="collapse-margin collapse-icon">
    <accordion-item
      v-for="(item, index) in items"
      :key="index"
      :item="item"
      :index="index"
      :isOpen="isCurrent(index)"
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
      currentIndex: {
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
    changeCurrent(index) {
      if (this.currentIndex === index) {
        this.currentIndex = -1;
      } else {
        this.currentIndex = index;
      }
    },
    isCurrent(index) {
      return this.currentIndex === index;
    },
  },
};
</script>
