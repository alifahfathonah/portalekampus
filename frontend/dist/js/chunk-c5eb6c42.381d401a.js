(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-c5eb6c42"],{"632e":function(e,t,a){"use strict";var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("v-app-bar",{attrs:{app:""}},[a("v-toolbar-title",[a("span",{staticClass:"hidden-sm-and-down"},[e._v(e._s(e.namaPTAlias))])]),a("v-spacer"),a("v-btn",{staticClass:"mr-2",attrs:{to:"/",color:"primary",text:"",large:""}},[e._v(" Home ")]),a("v-btn",{staticClass:"mr-2",attrs:{to:"/pmb",color:"primary",text:"",large:""}},[e._v(" PENDAFTARAN ")]),a("v-btn",{attrs:{to:"/login",color:"primary",text:"",large:""}},[e._v(" Login ")])],1),a("v-content",[e._t("default")],2)],1)},n=[],i=a("5530"),o=a("2f62"),s={name:"FrontLayout",created:function(){this.$store.dispatch("identitas/init",this.$ajax)},computed:Object(i["a"])({},Object(o["b"])("identitas",{namaPTAlias:"getNamaPTAlias"}))},c=s,l=a("2877"),d=a("6544"),u=a.n(d),p=a("40dc"),f=a("8336"),m=a("a75b"),h=a("2fa4"),v=a("2a7f"),y=Object(l["a"])(c,r,n,!1,null,null,null);t["a"]=y.exports;u()(y,{VAppBar:p["a"],VBtn:f["a"],VContent:m["a"],VSpacer:h["a"],VToolbarTitle:v["a"]})},"905d":function(e,t,a){"use strict";a.r(t);var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("FrontLayout",[a("v-container",{staticClass:"fill-height",attrs:{fluid:""}},[a("v-row",{attrs:{align:"center",justify:"center","no-gutters":""}},[a("v-col",{attrs:{xs:"12",sm:"6",md:"4"}},[a("h1",{staticClass:"text-center display-1 font-weight-black primary--text"},[e._v(" PENDAFTARAN MAHASISWA BARU ")]),a("v-alert",{attrs:{outlined:"",dense:"",type:"error",value:e.form_error,icon:"mdi-close-octagon-outline"}},[e._v(" Username atau Password salah, silahkan isi kembali dengan yang benar !!! ")]),a("v-form",{ref:"frmlogin",attrs:{"lazy-validation":""}},[a("v-card",{attrs:{outlined:""}},[a("v-card-text",[a("v-text-field",{attrs:{label:"NAMA LENGKAP",rules:e.rule_name,outlined:"",dense:""},model:{value:e.formdata.name,callback:function(t){e.$set(e.formdata,"name",t)},expression:"formdata.name"}}),a("v-text-field",{attrs:{label:"NOMOR HP",rules:e.rule_nomorhp,outlined:"",dense:""},model:{value:e.formdata.nomor_hp,callback:function(t){e.$set(e.formdata,"nomor_hp",t)},expression:"formdata.nomor_hp"}}),a("v-text-field",{attrs:{label:"EMAIL",rules:e.rule_email,outlined:"",dense:""},model:{value:e.formdata.email,callback:function(t){e.$set(e.formdata,"email",t)},expression:"formdata.email"}}),a("v-text-field",{attrs:{label:"USERNAME",rules:e.rule_username,outlined:"",dense:""},model:{value:e.formdata.username,callback:function(t){e.$set(e.formdata,"username",t)},expression:"formdata.username"}}),a("v-text-field",{attrs:{label:"PASSWORD",type:"password",rules:e.rule_password,outlined:"",dense:""},model:{value:e.formdata.password,callback:function(t){e.$set(e.formdata,"password",t)},expression:"formdata.password"}})],1),a("v-card-actions",{staticClass:"justify-center"},[a("vue-recaptcha",{ref:"recaptcha",attrs:{sitekey:e.sitekey,loadRecaptchaScript:!0},on:{verify:e.onVerify,expired:e.onExpired}})],1),a("v-card-actions",{staticClass:"justify-center"},[a("v-btn",{attrs:{color:"primary",loading:e.btnLoading,disabled:e.btnLoading,block:""},on:{click:e.save}},[e._v(" DAFTAR ")])],1)],1)],1)],1)],1)],1)],1)},n=[],i=(a("96cf"),a("1da1"));function o(){return o=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var a=arguments[t];for(var r in a)Object.prototype.hasOwnProperty.call(a,r)&&(e[r]=a[r])}return e},o.apply(this,arguments)}var s=function(){var e=!1,t=[],a=function(a){if(!e){e=!0;for(var r=0,n=t.length;r<n;r++)t[r](a)}},r=function(a){e?a():t.push(a)},n={resolved:function(){return e},resolve:a,promise:{then:r}};return n},c=Object.prototype.hasOwnProperty;function l(){var e=s();return{notify:function(){e.resolve()},wait:function(){return e.promise},render:function(e,t,a){this.wait().then((function(){a(window.grecaptcha.render(e,t))}))},reset:function(e){"undefined"!==typeof e&&(this.assertLoaded(),this.wait().then((function(){return window.grecaptcha.reset(e)})))},execute:function(e){"undefined"!==typeof e&&(this.assertLoaded(),this.wait().then((function(){return window.grecaptcha.execute(e)})))},checkRecaptchaLoad:function(){c.call(window,"grecaptcha")&&c.call(window.grecaptcha,"render")&&this.notify()},assertLoaded:function(){if(!e.resolved())throw new Error("ReCAPTCHA has not been loaded")}}}var d=l();"undefined"!==typeof window&&(window.vueRecaptchaApiLoaded=d.notify);var u={name:"VueRecaptcha",props:{sitekey:{type:String,required:!0},theme:{type:String},badge:{type:String},type:{type:String},size:{type:String},tabindex:{type:String},loadRecaptchaScript:{type:Boolean,default:!1},recaptchaScriptId:{type:String,default:"__RECAPTCHA_SCRIPT"},recaptchaHost:{type:String,default:"www.google.com"},language:{type:String,default:""}},beforeMount:function(){if(this.loadRecaptchaScript&&!document.getElementById(this.recaptchaScriptId)){var e=document.createElement("script");e.id=this.recaptchaScriptId,e.src="https://"+this.recaptchaHost+"/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit&hl="+this.language,e.async=!0,e.defer=!0,document.head.appendChild(e)}},mounted:function(){var e=this;d.checkRecaptchaLoad();var t=o({},this.$props,{callback:this.emitVerify,"expired-callback":this.emitExpired,"error-callback":this.emitError}),a=this.$slots["default"]?this.$el.children[0]:this.$el;d.render(a,t,(function(t){e.$widgetId=t,e.$emit("render",t)}))},methods:{reset:function(){d.reset(this.$widgetId)},execute:function(){d.execute(this.$widgetId)},emitVerify:function(e){this.$emit("verify",e)},emitExpired:function(){this.$emit("expired")},emitError:function(){this.$emit("error")}},render:function(e){return e("div",{},this.$slots["default"])}},p=u,f=a("632e"),m={name:"PMB",data:function(){return{btnLoading:!1,form_error:!1,sitekey:"6LemEfEUAAAAAOabmlDlsVEv8xXdzNJywGRxiQvN",captcha_response:null,formdata:{name:"",email:"",nomor_hp:"",username:"",password:""},rule_name:[function(e){return!!e||"Nama Mahasiswa mohon untuk diisi !!!"}],rule_nomorhp:[function(e){return!!e||"Nomor HP mohon untuk diisi !!!"}],rule_email:[function(e){return!!e||"Email mohon untuk diisi !!!"}],rule_username:[function(e){return!!e||"Username mohon untuk diisi !!!"}],rule_password:[function(e){return!!e||"Kolom Password mohon untuk diisi !!!"}]}},methods:{save:function(){var e=Object(i["a"])(regeneratorRuntime.mark((function e(){return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:case"end":return e.stop()}}),e)})));function t(){return e.apply(this,arguments)}return t}(),onVerify:function(e){console.log("Verify: "+e)},onExpired:function(){console.log("Expired"),this.captcha_response=""}},components:{FrontLayout:f["a"],VueRecaptcha:p}},h=m,v=a("2877"),y=a("6544"),w=a.n(y),b=a("0798"),g=a("8336"),x=a("b0af"),A=a("99d9"),_=a("62ad"),k=a("a523"),E=a("4bd4"),R=a("0fd9"),V=a("8654"),S=Object(v["a"])(h,r,n,!1,null,null,null);t["default"]=S.exports;w()(S,{VAlert:b["a"],VBtn:g["a"],VCard:x["a"],VCardActions:A["a"],VCardText:A["b"],VCol:_["a"],VContainer:k["a"],VForm:E["a"],VRow:R["a"],VTextField:V["a"]})}}]);