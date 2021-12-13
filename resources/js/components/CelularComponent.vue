<template>
  <div>
    <VuePhoneNumberInput
      v-model="initValue"
      :required="required"
      :class="{ 'is-invalid': addClassInvalid }"
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
    <div class="invalid-feedback">
      {{ msgError }}
    </div>
  </div>
</template>

<script>
import VuePhoneNumberInput from "vue-phone-number-input";

export default {
  data() {
    return {
      initValue: null,
      init: false,
      isValid: false,
      showErrors: false,
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
    msgError: {
      type: String,
      default: "Ingrese un numero valido",
    },
  },
  components: {
    VuePhoneNumberInput,
  },
  created() {
    this.initValue = this.value;

    eventBus.$on("validarFormulario", () => {
      this.showErrors = true;
    });

    eventBus.$on("resetValidaciones", () => {
      this.showErrors = false;
    });
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
  computed: {
    addClassInvalid() {
      return this.showErrors && !this.isValid;
    },
  },
};
</script>

<style>
</style>
