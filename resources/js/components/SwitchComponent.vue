<template>
  <label class="switch" :style="styleSize" @click="toggleChecked">
    <input id="id" type="checkbox" :checked="checked" />
    <span class="slider round"></span>
    <span class="msg">{{ msg }}</span>
  </label>
</template>
<script>
export default {
  name: "SwitchComponent",
  props: {
    id: {
      type: String,
      default: "switch",
    },
    size: {
      type: Number,
      default: 100,
    },
    value: {
      type: Boolean,
      default: false,
    },
    msgTrue: {
      type: String,
      default: "Si",
    },
    msgFalse: {
      type: String,
      default: "No",
    },
  },
  data() {
    return {
      checked: this.value,
    };
  },
  computed: {
    msg() {
      return this.checked ? this.msgTrue : this.msgFalse;
    },
    styleSize() {
      return { fontSize: `${this.size / 100}px` };
    },
  },
  methods: {
    toggleChecked(e) {
      e.preventDefault();
      this.checked = !this.checked;
      this.$emit("input", this.checked);
    },
  },
};
</script>
<style scoped>
.switch {
  position: relative;
  display: inline-block;
  width: 60em;
  height: 34em;
  cursor: pointer;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26em;
  width: 26em;
  left: 4em;
  bottom: 4em;
  background-color: white;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

input:checked + .slider {
  background-color: #2196f3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196f3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26em);
  -ms-transform: translateX(26em);
  transform: translateX(26em);
}

.slider.round {
  border-radius: 34em;
}

.slider.round:before {
  border-radius: 50%;
}

.msg {
  position: absolute;
  top: 0;
  font-size: 18em;
  bottom: 0;
  right: 0;
  transform: translateX(100%);
  display: flex;
  align-items: center;
}
</style>
