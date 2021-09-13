const find = function (array, name) {
  if (array && Array.isArray(array)) {
    return (array.findIndex(policy => (policy.name === name)) !== -1);
  }
  return false;
}

export default class PurchaseOrderPolicy {
  static editPurchaseOrder($user, customer) {
    return $user.rolesTxt.indexOf('Administrador') !== -1;
  }
}
