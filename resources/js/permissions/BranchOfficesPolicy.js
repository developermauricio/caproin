const find = function (array, name) {
  if (array && Array.isArray(array)) {
    return (array.findIndex(policy => (policy.name === name) ) !== -1);
  }
  return false;
}

export default class BranchOfficesPolicy
{
  static createBranch($user, branch){
    return find(user.roles,'Administrador');
  }

  static deleteBranch($user, branch){
    return find(user.roles, 'Administrador');
  }

}
