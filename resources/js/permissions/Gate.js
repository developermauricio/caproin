import InvoicePolicy from "./InvoicePolicy";
import TradeAgreement from "./TradeAgreement";

export default class Gate
{
  constructor(user)
  {
    this.user = user;

    this.policies = {
      invoice: InvoicePolicy,
      tradeAgreement: TradeAgreement,
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
