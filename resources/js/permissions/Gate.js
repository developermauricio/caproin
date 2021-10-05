import BranchOfficesPolicy from "./BranchOfficesPolicy";
import CustomerPolicy from "./CustomerPolicy";
import InvoicePolicy from "./InvoicePolicy";
import PurchaseOrderPolicy from "./PurchaseOrderPolicy";
import TradeAgreement from "./TradeAgreement";
import ZonePolicy from "./ZonePolicy";
import EditStateInvoicePolicy from "./EditStateInvoicePolicy";

export default class Gate
{
  constructor(user, roles)
  {
    this.roles = roles;
    this.user = Object.assign({rolesTxt: roles}, user);

    this.policies = {
      invoice: InvoicePolicy,
      tradeAgreement: TradeAgreement,
      zone: ZonePolicy,
      branch: BranchOfficesPolicy,
      customer: CustomerPolicy,
      purchaseOrder: PurchaseOrderPolicy,
      editStateInvoice: EditStateInvoicePolicy
    };
  }

  before()
  {
    return this.user.role === 'Administrador';
  }

  allow(action, type, model = null)
  {
    if (this.before()) {
      return true;
    }

    return this.policies[type][action](this.user, model);
  }

  deny(action, type, model = null)
  {
    return ! this.allow(action, type, model);
  }
}
