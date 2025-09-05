import controller_0 from "../ux-turbo/turbo_controller.js";
import controller_1 from "../ux-vue/render_controller.js";
import controller_2 from "../../controllers/hello_controller.js";
export const eagerControllers = {"symfony--ux-turbo--turbo-core": controller_0, "symfony--ux-vue--vue": controller_1, "hello": controller_2};
export const lazyControllers = {"csrf-protection": () => import("../../controllers/csrf_protection_controller.js")};
export const isApplicationDebug = true;