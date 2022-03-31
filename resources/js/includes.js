/**
 * @author Elias Champi Hancco
 * @name my all global components
 */
/* import jquery and bootstrap js */
import Vue from "vue";
// card
import Card from "./Components/Card/Card";
import Form from "./Components/Card/Form";
import Panel from "./Components/Card/Panel";
// Ui Components
import Button from "./Components/Ui/Button";
import Action from "./Components/Ui/Action";
import Submit from "./Components/Ui/Submit";
import Check from "./Components/Ui/Check";
import Switch from "./Components/Ui/Switch";
import Input from "./Components/Ui/Input";
import mInputBtn from "./Components/Ui/InputBtn";
import Textarea from "./Components/Ui/Textarea";
import Plain from "./Components/Ui/Plain";
import Router from "./Components/Ui/Router";
import Modal from "./Components/Ui/Modal";
import Alert from "./Components/Ui/Alert";
import smsdata from "./Data/message.json";
// validator components
import VeeValidate, { Validator } from "vee-validate";
import es from "vee-validate/dist/locale/es";
import backValidate from "./Helpers/validate";
// snack notificator
import VueSnackbar from "./Components/Ui/Snack";
// table and pagination component
import Table from "./Components/Table/Table";
import Pagination from "./Components/Table/Pagination";
import atributes from "./Data/atribute.json";

import loader from "./Components/Ui/Loader/Pulse";
// here is defined global util variables
import * as util from "./Helpers/util";

try {
  window.$ = window.jQuery = require("jquery");
  require("bootstrap");
} catch (e) {
  console.log(e); //eslint-disable-line
}

Validator.localize({ es });
Vue.use(VeeValidate, {
  locale: "es",
  inject: true,
  fieldsBagName: "veeFields"
});
Vue.use(backValidate, atributes);
Vue.use(VueSnackbar);
Vue.component("modal", Modal);
Vue.component("card", Card);
Vue.component("panel", Panel);
Vue.component("alert", Alert);
Vue.component("pagination", Pagination);
Vue.component("mTable", Table);
Vue.component("PulseLoader", loader);
Vue.component("mButton", Button);
Vue.component("mSubmit", Submit);
Vue.component("mAction", Action);
Vue.component("mCheck", Check);
Vue.component("mSwitch", Switch);
Vue.component("mInput", Input);
Vue.component("mInputBtn", mInputBtn);
Vue.component("mTextarea", Textarea);
Vue.component("mPlain", Plain);
Vue.component("mRouter", Router);
Vue.component("mForm", Form);
Vue.prototype.$objectHasRow = util.objectHasRow;
Vue.prototype.$defined = util.defined;

Vue.prototype.$confirm = (action, entity) => {
  const p1 = smsdata.confirm.replace(":registro", entity);
  return p1.replace(":action", smsdata.action[action]);
};
Vue.prototype.$downl = util.download;
