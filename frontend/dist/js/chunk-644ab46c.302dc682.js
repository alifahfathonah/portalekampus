(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-644ab46c"],{"0160":function(t,e,i){},"969c":function(t,e,i){"use strict";i.r(e);var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("AdminLayout",[i("ModuleHeader",{scopedSlots:t._u([{key:"icon",fn:function(){return[t._v(" mdi-monitor-dashboard ")]},proxy:!0},{key:"name",fn:function(){return[t._v(" DASHBOARD ")]},proxy:!0},{key:"breadcrumbs",fn:function(){return[i("v-breadcrumbs",{staticClass:"pa-0",attrs:{items:t.breadcrumbs},scopedSlots:t._u([{key:"divider",fn:function(){return[i("v-icon",[t._v("mdi-chevron-right")])]},proxy:!0}])})]},proxy:!0},{key:"desc",fn:function(){return[i("v-alert",{attrs:{color:"cyan",border:"left","colored-border":"",type:"info"}},[t._v(" dashboard untuk memperoleh ringkasan informasi. ")])]},proxy:!0}])}),"mahasiswabaru"==t.dashboard?i("v-container",[i("DashboardMB")],1):t._e()],1)},n=[],r=(i("96cf"),i("1da1")),s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-row",{attrs:{"no-gutters":""}},[i("v-col",{attrs:{cols:"12"}},[i("v-timeline",{attrs:{"align-top":"",dense:t.$vuetify.breakpoint.smAndDown}},[i("v-timeline-item",{attrs:{color:"deep-orange lighten-2",icon:"mdi-email-alert","fill-dot":""}},[i("v-card",{attrs:{color:"deep-orange lighten-2",dark:""}},[i("v-card-title",{staticClass:"title"},[t._v("Verifikasi Email")]),i("v-card-text",{staticClass:"white text--primary"},[i("p",[t._v("Email belum diverifikasi, silahkan diverifikasi !!!.")]),i("v-btn",{staticClass:"mx-0",attrs:{color:"deep-orange lighten-2",outlined:""}},[t._v(" Kirim Ulang ")])],1)],1)],1),i("v-timeline-item",{attrs:{color:"purple darken-1",icon:"mdi-book-variant","fill-dot":""}},[i("v-card",{attrs:{color:"purple darken-1",dark:""}},[i("v-card-title",{staticClass:"title"},[t._v("Formulir Pendaftaran")]),i("v-card-text",{staticClass:"white text--primary"},[i("p",[t._v(" Isi formulir pendaftaran dan lengkapi persyaratannya yaitu: "),i("ul",[i("li",[t._v("Scan Pas Foto")]),i("li",[t._v("Scan Ijazah Terakhir")]),i("li",[t._v("Scan KTP")]),i("li",[t._v("Scan Kartu Keluarga")])])]),i("v-btn",{staticClass:"mx-0 mr-2",attrs:{color:"purple darken-1",outlined:"",to:"/spmb/formulirpendaftaran"}},[t._v(" Isi Formulir ")]),i("v-btn",{staticClass:"mx-0",attrs:{color:"purple darken-1",outlined:"",to:"/spmb/persyaratan"}},[t._v(" Upload Persyaratan ")])],1)],1)],1),i("v-timeline-item",{attrs:{color:"red lighten-2",icon:"mdi-account-cash","fill-dot":""}},[i("v-card",{attrs:{color:"red lighten-2",dark:""}},[i("v-card-title",{staticClass:"title"},[t._v("Konfirmasi Pembayaran")]),i("v-card-text",{staticClass:"white text--primary"},[i("p",[t._v("Konfirmasi Pembayaran uang pembayaran")]),i("v-btn",{staticClass:"mx-0",attrs:{color:"red lighten-2",outlined:""}},[t._v(" Button ")])],1)],1)],1),i("v-timeline-item",{attrs:{color:"indigo",icon:"mdi-buffer","fill-dot":""}},[i("v-card",{attrs:{color:"indigo",dark:""}},[i("v-card-title",{staticClass:"title"},[t._v("Ujian Online")]),i("v-card-text",{staticClass:"white text--primary"},[i("p",[t._v("Ujian Online")]),i("v-btn",{staticClass:"mx-0",attrs:{color:"indigo",outlined:""}},[t._v(" Button ")])],1)],1)],1),i("v-timeline-item",{attrs:{color:"green lighten-1",icon:"mdi-airballoon","fill-dot":""}},[i("v-card",{attrs:{color:"green lighten-1",dark:""}},[i("v-card-title",{staticClass:"title"},[t._v("Hasil Ujian")]),i("v-card-text",{staticClass:"white text--primary"},[i("p",[t._v("Hasil Ujian")]),i("v-btn",{staticClass:"mx-0",attrs:{color:"green lighten-1",outlined:""}},[t._v(" Button ")])],1)],1)],1)],1)],1)],1)},o=[],l={name:"DashboardMahasiswaBaru",created:function(){this.initialize()},data:function(){return{}},methods:{initialize:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:case"end":return t.stop()}}),t)})));function e(){return t.apply(this,arguments)}return e}()}},c=l,d=i("2877"),u=i("6544"),m=i.n(u),h=i("8336"),v=i("b0af"),p=i("99d9"),f=i("62ad"),b=i("0fd9"),g=i("5530"),_=(i("0160"),i("58df")),x=i("7560"),k=Object(_["a"])(x["a"]).extend({name:"v-timeline",provide:function(){return{timeline:this}},props:{alignTop:Boolean,dense:Boolean,reverse:Boolean},computed:{classes:function(){return Object(g["a"])({"v-timeline--align-top":this.alignTop,"v-timeline--dense":this.dense,"v-timeline--reverse":this.reverse},this.themeClasses)}},render:function(t){return t("div",{staticClass:"v-timeline",class:this.classes},this.$slots.default)}}),y=(i("c96a"),i("9d26")),C=i("a9ad"),B=Object(_["a"])(C["a"],x["a"]),D=B.extend().extend({name:"v-timeline-item",inject:["timeline"],props:{color:{type:String,default:"primary"},fillDot:Boolean,hideDot:Boolean,icon:String,iconColor:String,large:Boolean,left:Boolean,right:Boolean,small:Boolean},computed:{hasIcon:function(){return!!this.icon||!!this.$slots.icon}},methods:{genBody:function(){return this.$createElement("div",{staticClass:"v-timeline-item__body"},this.$slots.default)},genIcon:function(){return this.$slots.icon?this.$slots.icon:this.$createElement(y["a"],{props:{color:this.iconColor,dark:!this.theme.isDark,small:this.small}},this.icon)},genInnerDot:function(){var t=this.setBackgroundColor(this.color);return this.$createElement("div",Object(g["a"])({staticClass:"v-timeline-item__inner-dot"},t),[this.hasIcon&&this.genIcon()])},genDot:function(){return this.$createElement("div",{staticClass:"v-timeline-item__dot",class:{"v-timeline-item__dot--small":this.small,"v-timeline-item__dot--large":this.large}},[this.genInnerDot()])},genDivider:function(){var t=[];return this.hideDot||t.push(this.genDot()),this.$createElement("div",{staticClass:"v-timeline-item__divider"},t)},genOpposite:function(){return this.$createElement("div",{staticClass:"v-timeline-item__opposite"},this.$slots.opposite)}},render:function(t){var e=[this.genBody(),this.genDivider()];return this.$slots.opposite&&e.push(this.genOpposite()),t("div",{staticClass:"v-timeline-item",class:Object(g["a"])({"v-timeline-item--fill-dot":this.fillDot,"v-timeline-item--before":this.timeline.reverse?this.right:this.left,"v-timeline-item--after":this.timeline.reverse?this.left:this.right},this.themeClasses)},e)}}),w=Object(d["a"])(c,s,o,!1,null,null,null),$=w.exports;m()(w,{VBtn:h["a"],VCard:v["a"],VCardText:p["c"],VCardTitle:p["d"],VCol:f["a"],VRow:b["a"],VTimeline:k,VTimelineItem:D});var O=i("a1b3"),j=i("e477"),E={name:"Dashboard",created:function(){this.TOKEN=this.$route.params.token,this.breadcrumbs=[{text:"HOME",disabled:!1,href:"/dashboard/"+this.TOKEN},{text:"DASHBOARD",disabled:!0,href:"#"}],this.initialize()},data:function(){return{breadcrumbs:[],TOKEN:null,dashboard:null}},methods:{initialize:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(){var e,i=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(e=this.$store.getters["uiadmin/getDefaultDashboard"],null!=e){t.next=7;break}return t.next=4,this.$ajax.get("/auth/me",{headers:{Authorization:"Bearer "+this.TOKEN}}).then((function(t){var e=t.data;i.dashboard=e.role[0],i.$store.dispatch("uiadmin/changeDashboard",i.dashboard)}));case 4:this.$store.dispatch("uiadmin/init",this.$ajax),t.next=8;break;case 7:this.dashboard=e;case 8:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}()},computed:{},components:{AdminLayout:O["a"],ModuleHeader:j["a"],DashboardMB:$}},V=E,T=i("0798"),I=i("2bc5"),S=i("a523"),K=i("132d"),A=Object(d["a"])(V,a,n,!1,null,null,null);e["default"]=A.exports;m()(A,{VAlert:T["a"],VBreadcrumbs:I["a"],VContainer:S["a"],VIcon:K["a"]})}}]);