"use strict";(self.webpackChunkdemo1=self.webpackChunkdemo1||[]).push([[430],{2430:(A,u,a)=>{a.r(u),a.d(u,{BuilderModule:()=>M});var s=a(9808),p=a(4521),e=a(5e3),c=(a(6330),a(6288)),m=a(4619),r=a(3075);const _=["form"];function h(l,g){1&l&&(e.ynx(0),e.TgZ(1,"span",57),e._uU(2,"Preview"),e.qZA(),e.BQk())}function f(l,g){1&l&&(e.ynx(0),e.TgZ(1,"span",58),e._uU(2),e._UZ(3,"span",59),e.qZA(),e.BQk()),2&l&&(e.xp6(1),e.Udp("display","block"),e.xp6(1),e.hij(" Please wait..."," "," "))}function Z(l,g){1&l&&(e.ynx(0),e.TgZ(1,"span",57),e._uU(2,"Reset"),e.qZA(),e.BQk())}function T(l,g){1&l&&(e.ynx(0),e.TgZ(1,"span",58),e._uU(2),e._UZ(3,"span",59),e.qZA(),e.BQk()),2&l&&(e.xp6(1),e.Udp("display","block"),e.xp6(1),e.hij(" Please wait..."," "," "))}const b=function(){return{class:"h-80px w-80px"}},d=function(l){return{active:l}};let v=(()=>{class l{constructor(t){this.layout=t,this.activeTab="Header",this.configLoading=!1,this.resetLoading=!1}ngOnInit(){this.model=this.layout.getConfig()}setActiveTab(t){this.activeTab=t}resetPreview(){this.resetLoading=!0,this.layout.refreshConfigToDefault()}submitPreview(){this.configLoading=!0,this.layout.setConfig(this.model),location.reload()}}return l.\u0275fac=function(t){return new(t||l)(e.Y36(c.Pb))},l.\u0275cmp=e.Xpm({type:l,selectors:[["app-builder"]],viewQuery:function(t,n){if(1&t&&e.Gf(_,7),2&t){let o;e.iGM(o=e.CRH())&&(n.form=o.first)}},decls:212,vars:64,consts:[[1,"card","mb-10"],[1,"card-body","d-flex","align-items-center","py-8"],[1,"d-flex","h-80px","w-80px","flex-shrink-0","flex-center","position-relative"],[1,"svg-icon","svg-icon-primary","position-absolute","opacity-15",3,"inlineSVG","setSVGAttributes"],[1,"svg-icon","svg-icon-3x","svg-icon-primary","position-absolute",3,"inlineSVG"],[1,"ms-6"],[1,"list-unstyled","text-gray-600","fw-bold","fs-6","p-0","m-0"],[1,"card","card-custom"],[1,"card-header","card-header-stretch","overflow-auto"],["role","tablist",1,"nav","nav-stretch","nav-line-tabs","fw-bold","border-transparent","flex-nowrap"],[1,"nav-item"],["role","tab",1,"nav-link","cursor-pointer",3,"ngClass","click"],["novalidate","",1,"form",3,"ngSubmit"],["form","ngForm"],[1,"card-body"],[1,"tab-content","pt-3"],[1,"tab-pane",3,"ngClass"],[1,"row","mb-10"],[1,"col-lg-3","col-form-label","text-lg-end"],[1,"col-lg-9","col-xl-4"],[1,"form-check","form-check-custom","form-check-solid","form-switch","mb-5"],["type","checkbox","name","model.header.fixed.desktop",1,"form-check-input",3,"ngModel","ngModelChange"],[1,"form-check-label","text-muted"],[1,"form-check","form-check-custom","form-check-solid","form-switch","mb-3"],["type","checkbox","name","model.header.fixed.tabletAndMobile",1,"form-check-input",3,"ngModel","ngModelChange"],[1,"form-text","text-muted"],["name","model.header.left",1,"form-select","form-select-solid",3,"ngModel","ngModelChange"],["value","menu"],["value","page-title"],["name","model.header.width",1,"form-select","form-select-solid",3,"ngModel","ngModelChange"],["value","fluid"],["value","fixed"],[1,"form-check","form-check-custom","form-check-solid","form-switch","mb-2"],["type","checkbox","name","model.toolbar.display",1,"form-check-input",3,"ngModel","ngModelChange"],["type","checkbox","name","model.toolbar.fixed.desktop",1,"form-check-input",3,"ngModel","ngModelChange"],["type","checkbox","name","model.toolbar.fixed.tabletAndMobileMode",1,"form-check-input",3,"ngModel","ngModelChange"],["name","model.toolbar.width",1,"form-select","form-select-solid",3,"ngModel","ngModelChange"],["type","checkbox","name","model.pageTitle.display",1,"form-check-input",3,"ngModel","ngModelChange"],["type","checkbox","name","model.pageTitle.breadCrumbs",1,"form-check-input",3,"ngModel","ngModelChange"],["name","model.content.width",1,"form-select","form-select-solid",3,"ngModel","ngModelChange"],[1,"switch","switch-icon"],["type","checkbox","name","model.aside.display",1,"form-check-input",3,"ngModel","ngModelChange"],["name","model.aside.theme",1,"form-select","form-select-solid",3,"ngModel","ngModelChange"],["value","dark"],["value","light"],["type","checkbox","name","model.aside.fixed",1,"form-check-input",3,"ngModel","ngModelChange"],["type","checkbox","name","model.aside.minimize",1,"form-check-input",3,"ngModel","ngModelChange"],["type","checkbox","name","model.aside.minimized",1,"form-check-input",3,"ngModel","ngModelChange"],["type","checkbox","name","model.aside.hoverable",1,"form-check-input",3,"ngModel","ngModelChange"],["name","model.footer.width",1,"form-select","form-select-solid",3,"ngModel","ngModelChange"],[1,"card-footer","py-6"],[1,"row"],[1,"col-lg-3"],[1,"col-lg-9"],["type","button",1,"btn","btn-primary","me-2",3,"disabled","click"],[4,"ngIf"],["type","button","id","kt_layout_builder_reset",1,"btn","btn-active-light","btn-color-muted",3,"disabled","click"],[1,"indicator-label"],[1,"indicator-progress"],[1,"spinner-border","spinner-border-sm","align-middle","ms-2"]],template:function(t,n){1&t&&(e.TgZ(0,"div",0)(1,"div",1)(2,"div",2),e._UZ(3,"span",3)(4,"span",4),e.qZA(),e.TgZ(5,"div",5)(6,"p",6),e._uU(7," The layout builder is to assist your set and configure your preferred project layout specifications and preview it in real-time and export the HTML template with its includable partials of this demo. The downloaded version does not include the assets folder since the layout builder's main purpose is to help to generate the final HTML code without hassle. Layout builder changes don't affect pages with layout wrappers. "),e.qZA()()()(),e.TgZ(8,"div",7)(9,"div",8)(10,"ul",9)(11,"li",10)(12,"a",11),e.NdJ("click",function(){return n.setActiveTab("Header")}),e._uU(13," Header "),e.qZA()(),e.TgZ(14,"li",10)(15,"a",11),e.NdJ("click",function(){return n.setActiveTab("Toolbar")}),e._uU(16," Toolbar "),e.qZA()(),e.TgZ(17,"li",10)(18,"a",11),e.NdJ("click",function(){return n.setActiveTab("PageTitle")}),e._uU(19," Page Title "),e.qZA()(),e.TgZ(20,"li",10)(21,"a",11),e.NdJ("click",function(){return n.setActiveTab("Aside")}),e._uU(22," Aside "),e.qZA()(),e.TgZ(23,"li",10)(24,"a",11),e.NdJ("click",function(){return n.setActiveTab("Content")}),e._uU(25," Content "),e.qZA()(),e.TgZ(26,"li",10)(27,"a",11),e.NdJ("click",function(){return n.setActiveTab("Footer")}),e._uU(28," Footer "),e.qZA()()()(),e.TgZ(29,"form",12,13),e.NdJ("ngSubmit",function(){return n.submitPreview()}),e.TgZ(31,"div",14)(32,"div",15)(33,"div",16)(34,"div",17)(35,"label",18),e._uU(36,"Fixed Header:"),e.qZA(),e.TgZ(37,"div",19)(38,"label",20)(39,"input",21),e.NdJ("ngModelChange",function(i){return n.model.header.fixed.desktop=i}),e.qZA(),e.TgZ(40,"span",22),e._uU(41,"Desktop"),e.qZA()(),e.TgZ(42,"label",23)(43,"input",24),e.NdJ("ngModelChange",function(i){return n.model.header.fixed.tabletAndMobile=i}),e.qZA(),e.TgZ(44,"span",22),e._uU(45,"Tablet & Mobile"),e.qZA()(),e.TgZ(46,"div",25),e._uU(47,"Enable fixed header"),e.qZA()()(),e.TgZ(48,"div",17)(49,"label",18),e._uU(50,"Left Content:"),e.qZA(),e.TgZ(51,"div",19)(52,"select",26),e.NdJ("ngModelChange",function(i){return n.model.header.left=i}),e.TgZ(53,"option",27),e._uU(54,"Menu"),e.qZA(),e.TgZ(55,"option",28),e._uU(56,"Page title"),e.qZA()(),e.TgZ(57,"div",25),e._uU(58," Select header left content type. "),e.qZA()()(),e.TgZ(59,"div",17)(60,"label",18),e._uU(61,"Width:"),e.qZA(),e.TgZ(62,"div",19)(63,"select",29),e.NdJ("ngModelChange",function(i){return n.model.header.width=i}),e.TgZ(64,"option",30),e._uU(65,"Fluid"),e.qZA(),e.TgZ(66,"option",31),e._uU(67,"Fixed"),e.qZA()(),e.TgZ(68,"div",25),e._uU(69,"Select header width type."),e.qZA()()()(),e.TgZ(70,"div",16)(71,"div",17)(72,"label",18),e._uU(73,"Display:"),e.qZA(),e.TgZ(74,"div",19)(75,"div",32)(76,"input",33),e.NdJ("ngModelChange",function(i){return n.model.toolbar.display=i}),e.qZA()(),e.TgZ(77,"div",25),e._uU(78,"Display toolbar"),e.qZA()()(),e.TgZ(79,"div",17)(80,"label",18),e._uU(81,"Fixed Toolbar:"),e.qZA(),e.TgZ(82,"div",19)(83,"label",20)(84,"input",34),e.NdJ("ngModelChange",function(i){return n.model.toolbar.fixed.desktop=i}),e.qZA(),e.TgZ(85,"span",22),e._uU(86,"Desktop"),e.qZA()(),e.TgZ(87,"label",23)(88,"input",35),e.NdJ("ngModelChange",function(i){return n.model.toolbar.fixed.tabletAndMobileMode=i}),e.qZA(),e.TgZ(89,"span",22),e._uU(90,"Tablet & Mobile"),e.qZA()(),e.TgZ(91,"div",25),e._uU(92,"Enable fixed toolbar"),e.qZA()()(),e.TgZ(93,"div",17)(94,"label",18),e._uU(95,"Width:"),e.qZA(),e.TgZ(96,"div",19)(97,"select",36),e.NdJ("ngModelChange",function(i){return n.model.toolbar.width=i}),e.TgZ(98,"option",30),e._uU(99,"Fluid"),e.qZA(),e.TgZ(100,"option",31),e._uU(101,"Fixed"),e.qZA()(),e.TgZ(102,"div",25),e._uU(103,"Select layout width type."),e.qZA()()()(),e.TgZ(104,"div",16)(105,"div",17)(106,"label",18),e._uU(107,"Display:"),e.qZA(),e.TgZ(108,"div",19)(109,"div",32)(110,"input",37),e.NdJ("ngModelChange",function(i){return n.model.pageTitle.display=i}),e.qZA()(),e.TgZ(111,"div",25),e._uU(112,"Display page title"),e.qZA()()(),e.TgZ(113,"div",17)(114,"label",18),e._uU(115,"Breadcrumb:"),e.qZA(),e.TgZ(116,"div",19)(117,"div",32)(118,"input",38),e.NdJ("ngModelChange",function(i){return n.model.pageTitle.breadCrumbs=i}),e.qZA()(),e.TgZ(119,"div",25),e._uU(120,"Display page title"),e.qZA()()()(),e.TgZ(121,"div",16)(122,"div",17)(123,"label",18),e._uU(124,"Width:"),e.qZA(),e.TgZ(125,"div",19)(126,"select",39),e.NdJ("ngModelChange",function(i){return n.model.content.width=i}),e.TgZ(127,"option",30),e._uU(128,"Fluid"),e.qZA(),e.TgZ(129,"option",31),e._uU(130,"Fixed"),e.qZA()(),e.TgZ(131,"div",25),e._uU(132,"Select layout width type."),e.qZA()()()(),e.TgZ(133,"div",16)(134,"div",17)(135,"label",18),e._uU(136,"Display:"),e.qZA(),e.TgZ(137,"div",19)(138,"div",40)(139,"div",32)(140,"input",41),e.NdJ("ngModelChange",function(i){return n.model.aside.display=i}),e.qZA()()(),e.TgZ(141,"div",25),e._uU(142,"Display Aside"),e.qZA()()(),e.TgZ(143,"div",17)(144,"label",18),e._uU(145,"Theme:"),e.qZA(),e.TgZ(146,"div",19)(147,"select",42),e.NdJ("ngModelChange",function(i){return n.model.aside.theme=i}),e.TgZ(148,"option",43),e._uU(149,"Dark"),e.qZA(),e.TgZ(150,"option",44),e._uU(151,"Light"),e.qZA()(),e.TgZ(152,"div",25),e._uU(153," Select header left content type. "),e.qZA()()(),e.TgZ(154,"div",17)(155,"label",18),e._uU(156,"Fixed:"),e.qZA(),e.TgZ(157,"div",19)(158,"div",40)(159,"div",32)(160,"input",45),e.NdJ("ngModelChange",function(i){return n.model.aside.fixed=i}),e.qZA()()(),e.TgZ(161,"div",25),e._uU(162,"Enable fixed aside"),e.qZA()()(),e.TgZ(163,"div",17)(164,"label",18),e._uU(165,"Minimize:"),e.qZA(),e.TgZ(166,"div",19)(167,"div",40)(168,"div",32)(169,"input",46),e.NdJ("ngModelChange",function(i){return n.model.aside.minimize=i}),e.qZA()()(),e.TgZ(170,"div",25),e._uU(171,"Enable aside minimization"),e.qZA()()(),e.TgZ(172,"div",17)(173,"label",18),e._uU(174,"Minimized:"),e.qZA(),e.TgZ(175,"div",19)(176,"div",40)(177,"div",32)(178,"input",47),e.NdJ("ngModelChange",function(i){return n.model.aside.minimized=i}),e.qZA()()(),e.TgZ(179,"div",25),e._uU(180,"Default minimized aside"),e.qZA()()(),e.TgZ(181,"div",17)(182,"label",18),e._uU(183,"Hoverable:"),e.qZA(),e.TgZ(184,"div",19)(185,"div",40)(186,"div",32)(187,"input",48),e.NdJ("ngModelChange",function(i){return n.model.aside.hoverable=i}),e.qZA()()(),e.TgZ(188,"div",25),e._uU(189," Enable hoverable minimized aside "),e.qZA()()()(),e.TgZ(190,"div",16)(191,"div",17)(192,"label",18),e._uU(193,"Width:"),e.qZA(),e.TgZ(194,"div",19)(195,"select",49),e.NdJ("ngModelChange",function(i){return n.model.footer.width=i}),e.TgZ(196,"option",30),e._uU(197,"Fluid"),e.qZA(),e.TgZ(198,"option",31),e._uU(199,"Fixed"),e.qZA()(),e.TgZ(200,"div",25),e._uU(201,"Select layout width type."),e.qZA()()()()()(),e.TgZ(202,"div",50)(203,"div",51),e._UZ(204,"div",52),e.TgZ(205,"div",53)(206,"button",54),e.NdJ("click",function(){return n.submitPreview()}),e.YNc(207,h,3,0,"ng-container",55),e.YNc(208,f,4,3,"ng-container",55),e.qZA(),e.TgZ(209,"button",56),e.NdJ("click",function(){return n.resetPreview()}),e.YNc(210,Z,3,0,"ng-container",55),e.YNc(211,T,4,3,"ng-container",55),e.qZA()()()()()()),2&t&&(e.xp6(3),e.Q6J("inlineSVG","./assets/media/icons/duotune/abstract/abs051.svg")("setSVGAttributes",e.DdM(39,b)),e.xp6(1),e.Q6J("inlineSVG","./assets/media/icons/duotune/coding/cod009.svg"),e.xp6(8),e.Q6J("ngClass",e.VKq(40,d,"Header"===n.activeTab)),e.xp6(3),e.Q6J("ngClass",e.VKq(42,d,"Toolbar"===n.activeTab)),e.xp6(3),e.Q6J("ngClass",e.VKq(44,d,"PageTitle"===n.activeTab)),e.xp6(3),e.Q6J("ngClass",e.VKq(46,d,"Aside"===n.activeTab)),e.xp6(3),e.Q6J("ngClass",e.VKq(48,d,"Content"===n.activeTab)),e.xp6(3),e.Q6J("ngClass",e.VKq(50,d,"Footer"===n.activeTab)),e.xp6(6),e.Q6J("ngClass",e.VKq(52,d,"Header"===n.activeTab)),e.xp6(6),e.Q6J("ngModel",n.model.header.fixed.desktop),e.xp6(4),e.Q6J("ngModel",n.model.header.fixed.tabletAndMobile),e.xp6(9),e.Q6J("ngModel",n.model.header.left),e.xp6(11),e.Q6J("ngModel",n.model.header.width),e.xp6(7),e.Q6J("ngClass",e.VKq(54,d,"Toolbar"===n.activeTab)),e.xp6(6),e.Q6J("ngModel",n.model.toolbar.display),e.xp6(8),e.Q6J("ngModel",n.model.toolbar.fixed.desktop),e.xp6(4),e.Q6J("ngModel",n.model.toolbar.fixed.tabletAndMobileMode),e.xp6(9),e.Q6J("ngModel",n.model.toolbar.width),e.xp6(7),e.Q6J("ngClass",e.VKq(56,d,"PageTitle"===n.activeTab)),e.xp6(6),e.Q6J("ngModel",n.model.pageTitle.display),e.xp6(8),e.Q6J("ngModel",n.model.pageTitle.breadCrumbs),e.xp6(3),e.Q6J("ngClass",e.VKq(58,d,"Content"===n.activeTab)),e.xp6(5),e.Q6J("ngModel",n.model.content.width),e.xp6(7),e.Q6J("ngClass",e.VKq(60,d,"Aside"===n.activeTab)),e.xp6(7),e.Q6J("ngModel",n.model.aside.display),e.xp6(7),e.Q6J("ngModel",n.model.aside.theme),e.xp6(13),e.Q6J("ngModel",n.model.aside.fixed),e.xp6(9),e.Q6J("ngModel",n.model.aside.minimize),e.xp6(9),e.Q6J("ngModel",n.model.aside.minimized),e.xp6(9),e.Q6J("ngModel",n.model.aside.hoverable),e.xp6(3),e.Q6J("ngClass",e.VKq(62,d,"Footer"===n.activeTab)),e.xp6(5),e.Q6J("ngModel",n.model.footer.width),e.xp6(11),e.Q6J("disabled",n.configLoading||n.resetLoading),e.xp6(1),e.Q6J("ngIf",!n.configLoading),e.xp6(1),e.Q6J("ngIf",n.configLoading),e.xp6(1),e.Q6J("disabled",n.configLoading||n.resetLoading),e.xp6(1),e.Q6J("ngIf",!n.resetLoading),e.xp6(1),e.Q6J("ngIf",n.resetLoading))},directives:[m.d$,s.mk,r._Y,r.JL,r.F,r.Wl,r.JJ,r.On,r.EJ,r.YN,r.Kr,s.O5],encapsulation:2}),l})();var C=a(7621);let M=(()=>{class l{}return l.\u0275fac=function(t){return new(t||l)},l.\u0275mod=e.oAB({type:l}),l.\u0275inj=e.cJS({imports:[[s.ez,r.u5,m.vi,C.HK,p.Bz.forChild([{path:"",component:v}])]]}),l})()}}]);