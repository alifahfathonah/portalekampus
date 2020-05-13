(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-ef749cae"],{"4bd4":function(t,a,e){"use strict";e("4de4"),e("7db0"),e("4160"),e("caad"),e("07ac"),e("2532"),e("159b");var n=e("5530"),i=e("58df"),r=e("7e2b"),o=e("3206");a["a"]=Object(i["a"])(r["a"],Object(o["b"])("form")).extend({name:"v-form",inheritAttrs:!1,props:{lazyValidation:Boolean,value:Boolean},data:function(){return{inputs:[],watchers:[],errorBag:{}}},watch:{errorBag:{handler:function(t){var a=Object.values(t).includes(!0);this.$emit("input",!a)},deep:!0,immediate:!0}},methods:{watchInput:function(t){var a=this,e=function(t){return t.$watch("hasError",(function(e){a.$set(a.errorBag,t._uid,e)}),{immediate:!0})},n={_uid:t._uid,valid:function(){},shouldValidate:function(){}};return this.lazyValidation?n.shouldValidate=t.$watch("shouldValidate",(function(i){i&&(a.errorBag.hasOwnProperty(t._uid)||(n.valid=e(t)))})):n.valid=e(t),n},validate:function(){return 0===this.inputs.filter((function(t){return!t.validate(!0)})).length},reset:function(){this.inputs.forEach((function(t){return t.reset()})),this.resetErrorBag()},resetErrorBag:function(){var t=this;this.lazyValidation&&setTimeout((function(){t.errorBag={}}),0)},resetValidation:function(){this.inputs.forEach((function(t){return t.resetValidation()})),this.resetErrorBag()},register:function(t){this.inputs.push(t),this.watchers.push(this.watchInput(t))},unregister:function(t){var a=this.inputs.find((function(a){return a._uid===t._uid}));if(a){var e=this.watchers.find((function(t){return t._uid===a._uid}));e&&(e.valid(),e.shouldValidate()),this.watchers=this.watchers.filter((function(t){return t._uid!==a._uid})),this.inputs=this.inputs.filter((function(t){return t._uid!==a._uid})),this.$delete(this.errorBag,a._uid)}}},render:function(t){var a=this;return t("form",{staticClass:"v-form",attrs:Object(n["a"])({novalidate:!0},this.attrs$),on:{submit:function(t){return a.$emit("submit",t)}}},this.$slots.default)}})},aaec:function(t,a,e){"use strict";e.r(a);var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("AdminLayout",[e("ModuleHeader",{scopedSlots:t._u([{key:"icon",fn:function(){return[t._v(" mdi-home ")]},proxy:!0},{key:"name",fn:function(){return[t._v(" FAKULTAS ")]},proxy:!0},{key:"breadcrumbs",fn:function(){return[e("v-breadcrumbs",{staticClass:"pa-0",attrs:{items:t.breadcrumbs},scopedSlots:t._u([{key:"divider",fn:function(){return[e("v-icon",[t._v("mdi-chevron-right")])]},proxy:!0}])})]},proxy:!0},{key:"desc",fn:function(){return[e("v-alert",{attrs:{color:"cyan",border:"left","colored-border":"",type:"info"}},[t._v(" Halaman untuk mengelola nama-nama fakultas pada perguruan tinggi ")])]},proxy:!0}])}),e("v-container",[e("v-row",{staticClass:"mb-4",attrs:{"no-gutters":""}},[e("v-col",{attrs:{cols:"12"}},[e("v-card",[e("v-card-text",[e("v-text-field",{attrs:{"append-icon":"mdi-database-search",label:"Search","single-line":"","hide-details":""},model:{value:t.search,callback:function(a){t.search=a},expression:"search"}})],1)],1)],1)],1),e("v-row",{staticClass:"mb-4",attrs:{"no-gutters":""}},[e("v-col",{attrs:{cols:"12"}},[e("v-data-table",{staticClass:"elevation-1",attrs:{headers:t.headers,items:t.datatable,search:t.search,"item-key":"kode_fakultas","sort-by":"nama_fakultas","show-expand":"",expanded:t.expanded,"single-expand":!0,loading:t.datatableLoading,"loading-text":"Loading... Please wait"},on:{"update:expanded":function(a){t.expanded=a},"click:row":t.dataTableRowClicked},scopedSlots:t._u([{key:"top",fn:function(){return[e("v-toolbar",{attrs:{flat:"",color:"white"}},[e("v-toolbar-title",[t._v("DATA TABLE")]),e("v-divider",{staticClass:"mx-4",attrs:{inset:"",vertical:""}}),e("v-spacer"),e("v-dialog",{attrs:{"max-width":"500px",persistent:""},scopedSlots:t._u([{key:"activator",fn:function(a){var n=a.on;return[e("v-btn",t._g({staticClass:"mb-2",attrs:{color:"primary",dark:""}},n),[t._v("TAMBAH")])]}}]),model:{value:t.dialogfrm,callback:function(a){t.dialogfrm=a},expression:"dialogfrm"}},[e("v-form",{ref:"frmdata",attrs:{"lazy-validation":""},model:{value:t.form_valid,callback:function(a){t.form_valid=a},expression:"form_valid"}},[e("v-card",[e("v-card-title",[e("span",{staticClass:"headline"},[t._v(t._s(t.formTitle))])]),e("v-card-text",[e("v-text-field",{attrs:{label:"KODE FAKULTAS",filled:"",rules:t.rule_kode_fakultas},model:{value:t.formdata.kode_fakultas,callback:function(a){t.$set(t.formdata,"kode_fakultas",a)},expression:"formdata.kode_fakultas"}}),e("v-text-field",{attrs:{label:"NAMA FAKULTAS",filled:"",rules:t.rule_nama_fakultas},model:{value:t.formdata.nama_fakultas,callback:function(a){t.$set(t.formdata,"nama_fakultas",a)},expression:"formdata.nama_fakultas"}})],1),e("v-card-actions",[e("v-spacer"),e("v-btn",{attrs:{color:"blue darken-1",text:""},on:{click:function(a){return a.stopPropagation(),t.closedialogfrm(a)}}},[t._v("BATAL")]),e("v-btn",{attrs:{color:"blue darken-1",text:"",loading:t.btnLoading,disabled:!t.form_valid||t.btnLoading},on:{click:function(a){return a.stopPropagation(),t.save(a)}}},[t._v(" SIMPAN ")])],1)],1)],1)],1),e("v-dialog",{attrs:{"max-width":"500px",persistent:""},model:{value:t.dialogdetailitem,callback:function(a){t.dialogdetailitem=a},expression:"dialogdetailitem"}},[e("v-card",[e("v-card-title",[e("span",{staticClass:"headline"},[t._v("DETAIL DATA")])]),e("v-card-text",[e("v-row",{attrs:{"no-gutters":""}},[e("v-col",{attrs:{xs:"12",sm:"6",md:"6"}},[e("v-card",{attrs:{flat:""}},[e("v-card-title",[t._v("KODE FAKULTAS :")]),e("v-card-subtitle",[t._v(" "+t._s(t.formdata.kode_fakultas)+" ")])],1)],1),t.$vuetify.breakpoint.xsOnly?e("v-responsive",{attrs:{width:"100%"}}):t._e(),e("v-col",{attrs:{xs:"12",sm:"6",md:"6"}},[e("v-card",{attrs:{flat:""}},[e("v-card-title",[t._v("NAMA FAKULTAS :")]),e("v-card-subtitle",[t._v(" "+t._s(t.formdata.nama_fakultas)+" ")])],1)],1),t.$vuetify.breakpoint.xsOnly?e("v-responsive",{attrs:{width:"100%"}}):t._e()],1)],1),e("v-card-actions",[e("v-spacer"),e("v-btn",{attrs:{color:"blue darken-1",text:""},on:{click:function(a){return a.stopPropagation(),t.closedialogdetailitem(a)}}},[t._v("KELUAR")])],1)],1)],1)],1)]},proxy:!0},{key:"item.actions",fn:function(a){var n=a.item;return[e("v-icon",{staticClass:"mr-2",attrs:{small:""},on:{click:function(a){return a.stopPropagation(),t.viewItem(n)}}},[t._v(" mdi-eye ")]),e("v-icon",{staticClass:"mr-2",attrs:{small:""},on:{click:function(a){return a.stopPropagation(),t.editItem(n)}}},[t._v(" mdi-pencil ")]),e("v-icon",{attrs:{small:"",loading:t.btnLoading,disabled:t.btnLoading},on:{click:function(a){return a.stopPropagation(),t.deleteItem(n)}}},[t._v(" mdi-delete ")])]}},{key:"expanded-item",fn:function(a){var n=a.headers,i=a.item;return[e("td",{staticClass:"text-center",attrs:{colspan:n.length}},[e("v-col",{attrs:{cols:"12"}},[e("strong",[t._v("ID:")]),t._v(t._s(i.kode_fakultas)+" ")])],1)]}},{key:"no-data",fn:function(){return[t._v(" Data belum tersedia ")]},proxy:!0}])})],1)],1)],1)],1)},i=[],r=(e("c975"),e("a434"),e("5530")),o=(e("96cf"),e("1da1")),s=e("2f62"),d=e("a1b3"),l=e("e477"),u={name:"PAGE",created:function(){this.breadcrumbs=[{text:"HOME",disabled:!1,href:"/dashboard/"+this.ACCESS_TOKEN},{text:"DATA MASTER",disabled:!1,href:"#"},{text:"FAKULTAS",disabled:!0,href:"#"}],this.initialize()},data:function(){return{btnLoading:!1,datatableLoading:!1,expanded:[],datatable:[],headers:[{text:"KODE FAKULTAS",value:"kode_fakultas",width:150},{text:"NAMA FAKULTAS",value:"nama_fakultas"},{text:"AKSI",value:"actions",sortable:!1,width:100}],search:"",dialogfrm:!1,dialogdetailitem:!1,form_valid:!0,formdata:{kode_fakultas:"",nama_fakultas:""},formdefault:{kode_fakultas:"",nama_fakultas:""},editedIndex:-1,rule_kode_fakultas:[function(t){return!!t||"Kode Fakultas mohon untuk diisi !!!"},function(t){return/^[1-9]{1}[0-9]{1,14}$/.test(t)||"Kode Fakultas hanya boleh angka"}],rule_nama_fakultas:[function(t){return!!t||"Mohon Nama Fakultas untuk di isi !!!"},function(t){return/^[A-Za-z\s]*$/.test(t)||"Nama Fakultas hanya boleh string dan spasi"}]}},methods:{initialize:function(){var t=Object(o["a"])(regeneratorRuntime.mark((function t(){var a=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return this.datatableLoading=!0,t.next=3,this.$ajax.get("/datamaster/fakultas",{headers:{Authorization:this.TOKEN}}).then((function(t){var e=t.data;a.datatable=e.fakultas,a.datatableLoading=!1})).catch((function(){a.datatableLoading=!1}));case 3:case"end":return t.stop()}}),t,this)})));function a(){return t.apply(this,arguments)}return a}(),dataTableRowClicked:function(t){t===this.expanded[0]?this.expanded=[]:this.expanded=[t]},viewItem:function(t){this.formdata=t,this.dialogdetailitem=!0},editItem:function(t){this.editedIndex=this.datatable.indexOf(t),this.formdata=Object.assign({},t),this.dialogfrm=!0},save:function(){var t=Object(o["a"])(regeneratorRuntime.mark((function t(){var a=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(!this.$refs.frmdata.validate()){t.next=9;break}if(this.btnLoading=!0,!(this.editedIndex>-1)){t.next=7;break}return t.next=5,this.$ajax.post("/datamaster/fakultas/"+this.formdata.kode_fakultas,{_method:"PUT",kode_fakultas:this.formdata.kode_fakultas,nama_fakultas:this.formdata.nama_fakultas},{headers:{Authorization:this.TOKEN}}).then((function(t){var e=t.data;Object.assign(a.datatable[a.editedIndex],e.fakultas),a.closedialogfrm(),a.btnLoading=!1})).catch((function(){a.btnLoading=!1}));case 5:t.next=9;break;case 7:return t.next=9,this.$ajax.post("/datamaster/fakultas/store",{kode_fakultas:this.formdata.kode_fakultas,nama_fakultas:this.formdata.nama_fakultas},{headers:{Authorization:this.TOKEN}}).then((function(t){var e=t.data;a.datatable.push(e.fakultas),a.btnLoading=!1,a.closedialogfrm()})).catch((function(){a.btnLoading=!1}));case 9:case"end":return t.stop()}}),t,this)})));function a(){return t.apply(this,arguments)}return a}(),deleteItem:function(t){var a=this;this.$root.$confirm.open("Delete","Apakah Anda ingin menghapus data fakultas dengan kode "+t.kode_fakultas+" ?",{color:"red"}).then((function(e){e&&(a.btnLoading=!0,a.$ajax.post("/datamaster/fakultas/"+t.kode_fakultas,{_method:"DELETE"},{headers:{Authorization:a.TOKEN}}).then((function(){var e=a.datatable.indexOf(t);a.datatable.splice(e,1),a.btnLoading=!1})).catch((function(){a.btnLoading=!1})))}))},closedialogdetailitem:function(){var t=this;this.dialogdetailitem=!1,setTimeout((function(){t.formdata=Object.assign({},t.formdefault),t.editedIndex=-1}),300)},closedialogfrm:function(){var t=this;this.dialogfrm=!1,this.$refs.frmdata.resetValidation(),setTimeout((function(){t.formdata=Object.assign({},t.formdefault),t.editedIndex=-1}),300)}},computed:Object(r["a"])({},Object(s["b"])("auth",{ACCESS_TOKEN:"AccessToken",TOKEN:"Token"}),{formTitle:function(){return-1===this.editedIndex?"TAMBAH DATA":"UBAH DATA"}}),components:{AdminLayout:d["a"],ModuleHeader:l["a"]}},c=u,f=e("2877"),m=e("6544"),h=e.n(m),v=e("0798"),b=e("2bc5"),k=e("8336"),p=e("b0af"),_=e("99d9"),g=e("62ad"),x=e("a523"),A=e("8fea"),T=e("169a"),w=e("ce7e"),y=e("4bd4"),L=e("132d"),V=e("6b53"),O=e("0fd9"),E=e("2fa4"),C=e("8654"),$=e("71d9"),K=e("2a7f"),S=Object(f["a"])(c,n,i,!1,null,null,null);a["default"]=S.exports;h()(S,{VAlert:v["a"],VBreadcrumbs:b["a"],VBtn:k["a"],VCard:p["a"],VCardActions:_["a"],VCardSubtitle:_["b"],VCardText:_["c"],VCardTitle:_["d"],VCol:g["a"],VContainer:x["a"],VDataTable:A["a"],VDialog:T["a"],VDivider:w["a"],VForm:y["a"],VIcon:L["a"],VResponsive:V["a"],VRow:O["a"],VSpacer:E["a"],VTextField:C["a"],VToolbar:$["a"],VToolbarTitle:K["a"]})}}]);