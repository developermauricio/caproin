<template>
  <div class="collapse-margin collapse-icon">
    <div
      class="card"
      :class="{ open: isCurrent(item) }"
      v-for="(item, index) in items"
      :key="item.id"
    >
      <div
        id="item.id"
        class="card-header"
        :class="{ collapsed: !isCurrent(item) }"
        data-toggle="collapse"
        role="button"
        :aria-expanded="ariaExpanded(item)"
        :aria-controls="'accordion_' + item.id"
        @click="changeCurrent(item)"
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
        class="collapse"
        :class="{ show: isCurrent(item) }"
        aria-expanded="false"
      >
        <div class="card-body">
          <slot v-bind:item="item" v-bind:index="index"></slot>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
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
    ariaExpanded(item) {
      return this.isCurrent(item) ? "true" : "false";
    },
  },
};
</script>
