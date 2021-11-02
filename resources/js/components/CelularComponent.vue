<template>
  <!-- <div> -->
  <!-- :class="{ 'is-invalid': !isValid }"  -->
  <VuePhoneNumberInput
    v-model="initValue"
    :required="required"
    requiredMsg="El teléfono es obligatorio"
    @phone-number-focused="focus($event)"
    @update="changePhone($event)"
    :fetch-country="true"
    :translations="{
      countrySelectorLabel: 'Código País',
      countrySelectorError: 'Selecciona un País',
      phoneNumberLabel: 'Número',
      example: 'Ejemplo',
    }"
  />
  <!-- <div class="invalid-feedback">
      El número de cotización del fabricante es obligatorio
    </div>
  </div> -->
</template>

<script>
import VuePhoneNumberInput from "vue-phone-number-input";

export default {
  data() {
    return {
      initValue: null,
      init: false,
      isValid: false,
    };
  },
  props: {
    value: {
      type: String,
      require: true,
    },
    required: {
      type: Boolean,
      default: true,
    },
  },
  components: {
    VuePhoneNumberInput,
  },
  created() {
    this.initValue = this.value;
  },
  watch: {
    value(next) {
      if (!this.init && !this.initValue) {
        this.initValue = next;
      }
    },
  },
  methods: {
    changePhone(e) {
      this.isValid = e.isValid;
      if (e.formatInternational) {
        this.$emit("input", e.formatInternational);
      } else {
        this.$emit("input", "");
      }
    },
    focus(e) {
      this.init = true;
    },
  },
};
</script>

<style>
</style>
