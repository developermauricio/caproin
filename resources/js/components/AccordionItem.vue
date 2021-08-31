<template>
  <div class="card" :class="{ open: isOpen }">
    <div
      id="item.id"
      class="card-header"
      :class="{ collapsed: !isOpen }"
      data-toggle="collapse"
      role="button"
      :aria-expanded="ariaExpanded"
      :aria-controls="'accordion_' + item.id"
      @click="changeCurrent()"
    >
      <span class="lead collapse-title">
        <slot name="title" v-bind:item="item" v-bind:index="index">
          {{ item.title }}
        </slot>
      </span>
    </div>
    <div
      :id="'accordion_' + item.id"
      :aria-labelledby="item.id"
      class="collapse--custom"
      :class="{ show: isOpen }"
      aria-expanded="false"
      :style="customStyle"
    >
      <div class="card-body" ref="body_item">
        <slot v-bind:item="item" v-bind:index="index"></slot>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "AccordionItem",
  props: {
    item: {
      type: Object,
      require: true,
    },
    isOpen: {
      type: Boolean,
      require: true,
    },
    index: {
      type: Number,
      require: true,
    },
  },
  data() {
    return {
      height: 0,
    };
  },
  computed: {
    ariaExpanded() {
      return this.isOpen ? "true" : "false";
    },
    seconds() {
      const seconds = this.height / 1600;
      return seconds > 0.6 ? 0.6 : seconds;
    },
    customStyle() {
      return {
        maxHeight: `${this.height}px`,
        transition: `${this.seconds}s all ease-in`,
      };
    },
  },
  methods: {
    changeCurrent() {
      this.checkHeight();
      this.$emit("change-current", this.index);
    },
    checkHeight() {
      this.height = this.$refs.body_item.clientHeight;
    },
  },
  mounted() {
    setTimeout(() => {
      this.checkHeight();
    }, 200);
  },
};
</script>
<style scoped>
.collapse--custom {
  overflow: hidden;
  transition: 0.5s all ease-in-out;
}
.collapse--custom:not(.show) {
  max-height: 0 !important;
}
</style>
