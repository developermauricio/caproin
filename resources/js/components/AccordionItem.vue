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
      :class="{ show: isOpen, hidden__colapse: hidden }"
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
      hidden: true,
    };
  },
  computed: {
    ariaExpanded() {
      this.checkHeight();
      this.hidden = true;
      setTimeout(() => {
        if (this.isOpen) {
          this.hidden = false;
        }
      }, this.seconds * 1000 + 500);
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
      this.$emit("change-current", this.index);
    },
    checkHeight() {
      if (!this.$refs.body_item) {
        return 0;
      }
      this.height = this.$refs.body_item.clientHeight + 100;
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
  transition: 0.5s all ease-in-out;
}

.hidden__colapse {
  overflow: hidden;
}

.collapse--custom:not(.show) {
  max-height: 0 !important;
}

.collapse-title {
  width: 100%;
}
</style>
