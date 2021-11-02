<template>
  <div :class="'form-group ' + clases">
    <label v-if="showLabel" class="form-control-label" :for="id">
      <span v-text="label"></span>

      <span class="text-danger asterisco" v-if="required">*</span>
      <slot name="toltip"></slot>
    </label>
    <datepicker
      v-if="type === 'date'"
      :language="es"
      :input-class="'form-control ' + (hasError ? 'is-invalid' : '')"
      placeholder="formato 1998-06-06"
      :class="hasError ? 'is-invalid' : ''"
      :full-month-name="true"
      format="yyyy-MM-dd"
      :value="dateValue"
      :typeable="true"
      v-bind="datepicker"
      v-on="inputListeners"
      @selected="change($event, true)"
    ></datepicker>
    <textarea
      v-else-if="type === 'textarea'"
      class="form-control"
      :class="hasError ? 'is-invalid' : ''"
      :id="id"
      :placeholder="label"
      :value="modelo"
      :required="required"
      v-on:input="change($event.target.value)"
      v-bind="options"
      v-on="inputListeners"
      @change="cambio"
    ></textarea>
    <multiselect
      v-else-if="type === 'multiselect'"
      :class="hasError ? 'is-invalid' : ''"
      :id="id"
      :label="label"
      :required="required"
      v-bind="multiselect"
      :value="modelo"
      v-on="inputListeners"
      @input="change($event)"
    ></multiselect>
    <money
      v-else-if="type === 'money'"
      v-on:input="change($event)"
      v-on="inputListeners"
      v-bind:value="modelo"
      v-bind="money"
      class="form-control"
      :class="hasError ? 'is-invalid' : ''"
    ></money>
    <ckeditor
      v-else-if="type === 'ckeditor'"
      @input="onEditorInput"
      v-bind="options"
      :class="hasError ? 'is-invalid' : ''"
    ></ckeditor>
    <input
      :type="tipoInput"
      class="form-control"
      :name="name"
      :class="hasError ? 'is-invalid' : ''"
      :id="id"
      :placeholder="label"
      :required="required"
      :value="modelo"
      v-on:input="change($event.target.value)"
      @blur="changeFocus"
      @change="cambio"
      v-bind="options"
      v-on="inputListeners"
      v-else
      :disabled="disabled == 1"
    />
    <div class="invalid-feedback" v-if="hasError">
      {{ messageError }}
    </div>
  </div>
</template>
<script>
import { en, es } from "vuejs-datepicker/dist/locale";
import Datepicker from "vuejs-datepicker";
import Multiselect from "vue-multiselect";

const validations = {
  all: RegExp(".+"),

  num: /^[0-9 ]+$/iu,
  alf: /^[a-z á-ú ü à-ù]+$/iu,
  alf_num: /^[a-z á-ú ü 0-9 à-ù]+$/iu,

  email: RegExp(
    "^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$"
  ),

  url: /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/,

  datemdy: /^([0-2][0-9]|(3)[0-1])(.)(((0)[0-9])|((1)[0-2]))(.)\d{4}$/,
  dateymd: /^\d{4}(.)(((0)[0-9])|((1)[0-2]))(.)([0-2][0-9]|(3)[0-1])$/,
};

export default {
  data: function () {
    return {
      validator: null,
      isValid: true,
      valorActual: null,
      tipoInput: "text",
      es: es,
      en: en,
      language: window.lang,
      noValue: true,
    };
  },
  props: {
    name: {
      type: String,
      default: "",
    },
    disabled: Number,
    label: String,
    id: String,
    pattern: String,
    errorMsg: String,
    requiredMsg: String,
    required: Boolean,
    modelo: [String, Number, Date, Object, Array],
    clases: String,
    msgServer: Array,
    type: String,
    datepicker: {
      type: Object,
      default: function () {
        return {};
      },
    },
    money: {
      type: Object,
      default: function () {
        return {};
      },
    },
    multiselect: {
      type: Object,
      default: function () {
        return {};
      },
    },
    options: {
      type: Object,
      default: function () {
        return {};
      },
    },
    showLabel: {
      type: Boolean,
      default: true,
    },
    customValid: {
      type: Function,
    },
  },
  components: {
    Datepicker,
    Multiselect,
  },
  computed: {
    dateValue() {
      if (this.type === "date" && this.modelo) {
        return this.$formatDate(this.modelo);
      }
      return this.modelo;
    },
    inputListeners: function () {
      var vm = this;
      return Object.assign({}, this.$listeners);
    },
    hasError() {
      return !this.isValid || this.msgServer;
    },
    messageError() {
      if (this.msgServer) return this.msgServer[0];

      if (!this.isValid && this.noValue) {
        return this.requiredMsg;
      } else {
        return this.errorMsg;
      }
    },
  },
  mounted() {
    if (this.type) {
      this.tipoInput = this.type;
    }
    eventBus.$on("validarFormulario", () => {
      this.changeFocus();
    });

    eventBus.$on("resetValidaciones", () => {
      this.isValid = true;
    });

    this.validator = validations[this.pattern]
      ? validations[this.pattern]
      : RegExp(this.pattern);
  },
  methods: {
    change(val) {
      this.validate(val, true);
    },
    changeFocus() {
      this.validate();
    },
    validate(value = null, change = false) {
      if (change) {
        if (value !== this.valorActual) {
          this.$emit("update:msgServer", null);
          this.$emit("update:modelo", value);
          this.$emit("updatedValue", value);
        } else {
          return;
        }
        this.valorActual = value;
      } else if (this.modelo) {
        this.valorActual = this.modelo;
      }

      if (this.type === "date") {
        this.isValid = this.required ? !this.isEmpty(this.valorActual) : true;
      } else if (this.customValid) {
        this.isValid = this.customValid(this.valorActual);
      } else if (typeof value !== "string") {
        this.validRequired(this.valorActual);
      } else if (value === "$ 0") {
        this.noValue = true;
        this.isValid = !this.required;
      } else {
        if (this.validator) {
          this.isValid = this.validator.test(this.valorActual);
        }
        this.validRequired(this.valorActual);
      }
    },
    validRequired(val) {
      this.noValue = false;
      this.isEmpty(val);
      if (this.noValue) {
        this.isValid = !this.required;
      }
    },
    isEmpty(value) {
      if (!value) {
        this.noValue = true;
        return true;
      }
      const type = typeof value;
      switch (type) {
        case "string":
          this.noValue = value === "$ 0" ? true : value.trim().length < 1;
          break;
        case "object":
          if (Array.isArray(value)) {
            this.noValue = value.length < 1;
            this.isValid = this.required ? !this.noValue : true;
          } else {
            this.noValue = !value;
            this.isValid = this.required ? !this.noValue : true;
          }
          break;
        case "number":
          this.noValue = false;
          this.isValid = true;
          break;
        default:
          this.noValue = true;
          break;
      }
      return this.noValue;
    },
    cambio() {
      this.$emit("update:modelo", this.valorActual);
      this.validate();
    },
    onEditorInput(event) {
      this.validate(event, true);
    },
    formatDate(date) {
      if (!date) {
        return "";
      }
      return moment.utc(date);
    },
  },
};
</script>
