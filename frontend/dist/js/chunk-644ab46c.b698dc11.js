(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-644ab46c"],{"0160":function(t,e,a){},"969c":function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("AdminLayout",[a("ModuleHeader",{scopedSlots:t._u([{key:"icon",fn:function(){return[t._v(" mdi-monitor-dashboard ")]},proxy:!0},{key:"name",fn:function(){return[t._v(" DASHBOARD ")]},proxy:!0},{key:"breadcrumbs",fn:function(){return[a("v-breadcrumbs",{staticClass:"pa-0",attrs:{items:t.breadcrumbs},scopedSlots:t._u([{key:"divider",fn:function(){return[a("v-icon",[t._v("mdi-chevron-right")])]},proxy:!0}])})]},proxy:!0},{key:"desc",fn:function(){return[a("v-alert",{attrs:{color:"cyan",border:"left","colored-border":"",type:"info"}},[t._v(" dashboard untuk memperoleh ringkasan informasi. ")])]},proxy:!0}])}),"mahasiswabaru"==t.dashboard?a("v-container",[a("DashboardMB")],1):"pmb"==t.dashboard?a("v-container",[t.tahun_pendaftaran>0?a("DashboardPMB",{attrs:{tahun_pendaftaran:t.tahun_pendaftaran}}):t._e()],1):t._e()],1)},n=[],r=(a("96cf"),a("1da1")),s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-row",{attrs:{"no-gutters":""}},[a("v-col",{attrs:{cols:"12"}},[a("v-timeline",{attrs:{"align-top":"",dense:t.$vuetify.breakpoint.smAndDown}},[a("v-timeline-item",{attrs:{color:"purple darken-1",icon:"mdi-book-variant","fill-dot":""}},[a("v-card",{attrs:{color:"purple darken-1",dark:""}},[a("v-card-title",{staticClass:"title"},[t._v("Formulir Pendaftaran")]),a("v-card-text",{staticClass:"white text--primary"},[a("p",[t._v(" Isi formulir pendaftaran dan lengkapi persyaratannya yaitu: "),a("ul",[a("li",[t._v("Scan Pas Foto")]),a("li",[t._v("Scan Ijazah Terakhir")]),a("li",[t._v("Scan KTP")]),a("li",[t._v("Scan Kartu Keluarga")])])]),a("v-btn",{staticClass:"mx-0 mr-2",attrs:{color:"purple darken-1",outlined:"",to:"/spmb/formulirpendaftaran"}},[t._v(" Isi Formulir ")]),a("v-btn",{staticClass:"mx-0",attrs:{color:"purple darken-1",outlined:"",to:"/spmb/persyaratan"}},[t._v(" Upload Persyaratan ")])],1)],1)],1),a("v-timeline-item",{attrs:{color:"indigo",icon:"mdi-buffer","fill-dot":""}},[a("v-card",{attrs:{color:"indigo",dark:""}},[a("v-card-title",{staticClass:"title"},[t._v("Ujian Online")]),a("v-card-text",{staticClass:"white text--primary"},[a("p",[t._v("Ujian Online")]),a("v-btn",{staticClass:"mx-0",attrs:{color:"indigo",outlined:""}},[t._v(" Button ")])],1)],1)],1)],1)],1)],1)},o=[],l={name:"DashboardMahasiswaBaru",created:function(){this.initialize()},data:function(){return{}},methods:{initialize:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:case"end":return t.stop()}}),t)})));function e(){return t.apply(this,arguments)}return e}()}},d=l,c=a("2877"),u=a("6544"),h=a.n(u),m=a("8336"),p=a("b0af"),v=a("99d9"),f=a("62ad"),b=a("0fd9"),_=a("5530"),g=(a("0160"),a("58df")),x=a("7560"),y=Object(g["a"])(x["a"]).extend({name:"v-timeline",provide:function(){return{timeline:this}},props:{alignTop:Boolean,dense:Boolean,reverse:Boolean},computed:{classes:function(){return Object(_["a"])({"v-timeline--align-top":this.alignTop,"v-timeline--dense":this.dense,"v-timeline--reverse":this.reverse},this.themeClasses)}},render:function(t){return t("div",{staticClass:"v-timeline",class:this.classes},this.$slots.default)}}),k=(a("c96a"),a("9d26")),C=a("a9ad"),$=Object(g["a"])(C["a"],x["a"]),D=$.extend().extend({name:"v-timeline-item",inject:["timeline"],props:{color:{type:String,default:"primary"},fillDot:Boolean,hideDot:Boolean,icon:String,iconColor:String,large:Boolean,left:Boolean,right:Boolean,small:Boolean},computed:{hasIcon:function(){return!!this.icon||!!this.$slots.icon}},methods:{genBody:function(){return this.$createElement("div",{staticClass:"v-timeline-item__body"},this.$slots.default)},genIcon:function(){return this.$slots.icon?this.$slots.icon:this.$createElement(k["a"],{props:{color:this.iconColor,dark:!this.theme.isDark,small:this.small}},this.icon)},genInnerDot:function(){var t=this.setBackgroundColor(this.color);return this.$createElement("div",Object(_["a"])({staticClass:"v-timeline-item__inner-dot"},t),[this.hasIcon&&this.genIcon()])},genDot:function(){return this.$createElement("div",{staticClass:"v-timeline-item__dot",class:{"v-timeline-item__dot--small":this.small,"v-timeline-item__dot--large":this.large}},[this.genInnerDot()])},genDivider:function(){var t=[];return this.hideDot||t.push(this.genDot()),this.$createElement("div",{staticClass:"v-timeline-item__divider"},t)},genOpposite:function(){return this.$createElement("div",{staticClass:"v-timeline-item__opposite"},this.$slots.opposite)}},render:function(t){var e=[this.genBody(),this.genDivider()];return this.$slots.opposite&&e.push(this.genOpposite()),t("div",{staticClass:"v-timeline-item",class:Object(_["a"])({"v-timeline-item--fill-dot":this.fillDot,"v-timeline-item--before":this.timeline.reverse?this.right:this.left,"v-timeline-item--after":this.timeline.reverse?this.left:this.right},this.themeClasses)},e)}}),w=Object(c["a"])(d,s,o,!1,null,null,null),B=w.exports;h()(w,{VBtn:m["a"],VCard:p["a"],VCardText:v["c"],VCardTitle:v["d"],VCol:f["a"],VRow:b["a"],VTimeline:y,VTimelineItem:D});var O=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-card",[a("v-card-title",{staticClass:"headline"},[t._v(" TOTAL MAHASISWA BARU : "+t._s(t.total_mb)+" ")]),a("v-card-text",[a("v-row",{attrs:{dense:""}},[t._l(t.daftar_prodi,(function(e){return a("v-col",{key:e.id,attrs:{xs:"12",sm:"6",md:"3"}},[a("v-card",{attrs:{color:"#385F73",dark:""}},[a("v-card-title",{staticClass:"headline"},[t._v(" "+t._s(e.nama_prodi)+" ")]),a("v-card-subtitle",{staticClass:"headline"},[t._v(" Jenjang : "+t._s(e.nama_jenjang)+" ")]),a("v-card-text",[t._v(" "+t._s(e.jumlah)+" ")])],1)],1)})),t.$vuetify.breakpoint.xsOnly?a("v-responsive",{attrs:{width:"100%"}}):t._e()],2)],1)],1)},j=[],T=(a("a9e3"),{name:"DashboardPMB",mounted:function(){this.initialize()},props:{tahun_pendaftaran:{type:Number,required:!0}},data:function(){return{datatableLoading:!1,daftar_prodi:[],total_mb:0}},methods:{initialize:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(){var e=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return this.datatableLoading=!0,t.next=3,this.$ajax.post("/dashboard/pmb",{TA:this.tahun_pendaftaran},{headers:{Authorization:this.$store.getters["auth/Token"]}}).then((function(t){var a=t.data;e.daftar_prodi=a.daftar_prodi,e.total_mb=a.total_mb,e.datatableLoading=!1})).catch((function(){e.datatableLoading=!1}));case 3:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}()}}),V=T,A=a("6b53"),E=Object(c["a"])(V,O,j,!1,null,null,null),S=E.exports;h()(E,{VCard:p["a"],VCardSubtitle:v["b"],VCardText:v["c"],VCardTitle:v["d"],VCol:f["a"],VResponsive:A["a"],VRow:b["a"]});var I=a("a1b3"),R=a("e477"),M={name:"Dashboard",created:function(){this.TOKEN=this.$route.params.token,this.breadcrumbs=[{text:"HOME",disabled:!1,href:"/dashboard/"+this.TOKEN},{text:"DASHBOARD",disabled:!0,href:"#"}],this.initialize()},data:function(){return{breadcrumbs:[],TOKEN:null,dashboard:null,tahun_pendaftaran:0}},methods:{initialize:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(){var e,a=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(e=this.$store.getters["uiadmin/getDefaultDashboard"],null!=e){t.next=7;break}return t.next=4,this.$ajax.get("/auth/me",{headers:{Authorization:"Bearer "+this.TOKEN}}).then((function(t){var e=t.data;a.dashboard=e.role[0],a.$store.dispatch("uiadmin/changeDashboard",a.dashboard)}));case 4:this.$store.dispatch("uiadmin/init",this.$ajax),t.next=8;break;case 7:this.dashboard=e;case 8:this.tahun_pendaftaran=this.$store.getters["uiadmin/getTahunPendaftaran"];case 9:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}()},computed:{},components:{AdminLayout:I["a"],ModuleHeader:R["a"],DashboardMB:B,DashboardPMB:S}},z=M,P=a("0798"),K=a("2bc5"),L=a("a523"),H=a("132d"),N=Object(c["a"])(z,i,n,!1,null,null,null);e["default"]=N.exports;h()(N,{VAlert:P["a"],VBreadcrumbs:K["a"],VContainer:L["a"],VIcon:H["a"]})}}]);