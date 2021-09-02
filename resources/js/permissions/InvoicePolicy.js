const find = function (array, name) {
  if (array && Array.isArray(array)) {
    return (array.findIndex(policy => (policy.name === name) ) !== -1);
  }
  return false;
}

export default class InvoicePolicy
{
  static createInvoice($user, invoice){
    return find(user.roles,'Administrador') || find(user.roles,'Finanzas');
  }

}
