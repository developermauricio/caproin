const timeout = (ms) => {
  return new Promise((resolve) => setTimeout(resolve, ms));
};

export const checkForm = async (selectorParent) => {
  eventBus.$emit("validarFormulario");
  await timeout(100);
  const isValid = document.querySelectorAll(selectorParent + " .is-invalid").length < 1;
  if (isValid) {
    eventBus.$emit('resetValidaciones');
  }
  return isValid;
};


export const formatDate = (date) => {
  return moment(date).format();
}


export const formatDateUTC = (date) => {
  if (!date) {
    return "";
  }
  return moment.utc(date);
};
