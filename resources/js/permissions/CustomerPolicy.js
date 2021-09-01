const find = function (array, name) {
  if (array && Array.isArray(array)) {
    return (array.findIndex(policy => (policy.name === name) ) !== -1);
  }
  return false;
}

export default class CustomerPolicy
{
  static editCustomer($user, customer){
    return find(user.roles,'Administrador');
  }

}
