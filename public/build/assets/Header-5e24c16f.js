var fe=Object.defineProperty;var ke=(r,o,v)=>o in r?fe(r,o,{enumerable:!0,configurable:!0,writable:!0,value:v}):r[o]=v;var Q=(r,o,v)=>(ke(r,typeof o!="symbol"?o+"":o,v),v);import{a2 as I,a3 as ye,a4 as _e,a5 as we,a6 as L,r as u,u as le,x as j,B as xe,N as Ce,w as Se,l as W,o as i,c as n,E as f,z as B,b as s,a1 as X,a,s as N,F as w,d as S,t as m,v as M,a0 as Pe,q as P,G as Y,e as Ee,J as Ve,m as Be,$ as Ne}from"./app-a4a1e86e.js";import{m as Fe,a as Ue,b as Ae,d as Ie,f as Me}from"./mdi-4b5bd968.js";import{_ as q,b as z,c as De}from"./CardBoxModal-b424615d.js";import{A as $e,_ as Te}from"./SectionMain-62dd47b2.js";import{_ as Le}from"./BaseIcon-127a6522.js";import{u as Z}from"./pieces-a65603a2.js";import{u as ee}from"./services-1d3c2c5c.js";class je{constructor(){Q(this,"apiUrl","/prices")}create(o,v,b){return I.post(this.apiUrl,{service_id:o,piece_id:v,price:b})}getList(){return I.get(this.apiUrl+"/")}edit(o,v,b,h){return I.put(this.apiUrl+"/"+o,{service_id:v,piece_id:b,price:h})}delete(o){return I.delete(this.apiUrl+"/"+o)}}const D=new je,te=ye("price",{state:()=>({selectedPrice:null,pieceSelected:!1,prices:[],pieceFetchError:null,loading:!1}),actions:{clear(){this.selectedPrice=null,this.pieceSelected=!1,this.loading=!1},setSelectedPrice(r){console.log(r),this.selectedPrice=r,r?this.pieceSelected=!0:this.pieceSelected=!1},fetch(){this.loading=!0,D.getList().then(r=>{this.prices=r.data}).catch(r=>{this.pieceFetchError=r}).finally(()=>{this.loading=!1})},fetchLocalData(){_e.get("data-sources/pieces.json",{baseURL:window.location.origin}).then(r=>{console.log(r.data),this.prices=r.data})},async create(r,o,v){this.loading=!0;try{const b=await D.create(r,o,v);return console.log("@#@#",b),await this.fetch(),console.log("@#@#1111",b),b.data}catch(b){return this.pieceFetchError=b,b}finally{this.loading=!1}},async edit(r,o,v,b){this.loading=!0;try{const h=await D.edit(r,o,v,b);console.log("@#@#@@@",h);const H=this.prices.findIndex(c=>c.id===r);return await this.fetch(),h.data}catch(h){return this.pieceFetchError=h,h}finally{this.loading=!1}},async delete(r){this.loading=!0;try{return await D.delete(r),this.prices=this.prices.filter(o=>o.id!==r),!0}catch(o){return this.pieceFetchError=o,o}finally{this.loading=!1}}}}),qe=a("label",{for:"countries",class:"block mt-10 mb-2 text-xs font-semibold text-gray-600 dark:text-white"},"Service",-1),ze=["value"],He=a("label",{for:"countries",class:"block mb-2 text-xs font-semibold text-gray-600 dark:text-white"},"Piece",-1),Re=["value"],Ge={key:0},Je={class:"text-red-500 text-sm"},Ke={key:1},Oe={class:"text-green-400 text-sm"},Qe=a("label",{for:"countries",class:"block mb-2 text-xs font-semibold text-gray-600 dark:text-white"},"Service",-1),We=["value"],Xe=a("label",{for:"countries",class:"block mb-2 text-xs font-semibold text-gray-600 dark:text-white"},"Piece",-1),Ye=["value"],Ze=a("label",{class:"block mb-2 text-xs font-semibold text-gray-600 dark:text-white"},"Price",-1),et={class:"border border-gray-400 rounded-lg"},tt=["value"],lt={key:0},at={class:"text-red-500 text-sm"},rt={key:1},ot={class:"text-green-400 text-sm"},st=a("p",null,"Are you sure?",-1),it={key:0,class:"p-3 bg-gray-100/50 dark:bg-slate-800"},nt=["onClick"],ct={key:0},dt={colspan:"6"},ut={class:"w-full flex justify-center"},vt={class:"before:hidden whitespace-nowrap"},bt={"data-label":"Created",class:"whitespace-nowrap"},ht={class:"text-gray-500 dark:text-slate-400"},mt={"data-label":"Service"},gt={"data-label":"Piece"},pt={"data-label":"Title"},ft={"data-label":"PieceId"},Et={__name:"TableDataClient",props:{checkable:Boolean,setModalActive:{type:Boolean,default:!1}},emits:{},setup(r,{expose:o,emit:v}){const b=we().shape({price:L().min(1).required(),piece:L().min(1).required(),service:L().min(1).required()}),h=u(!1);o({showNewModal:()=>{pe(),h.value=!0}});const{t:c}=le(),F=u(!1),x=te(),{prices:ae,selectedPrice:E,loading:re}=j(te()),oe=Z(),{pieces:R}=j(Z()),se=ee(),{services:G}=j(ee()),$=u(!1),T=u(5),U=u(1),A=u([]);u(!1);const V=u(!1),C=u(""),y=u(null),_=u(null);u("");const k=u(""),g=u(""),p=u("");xe(()=>{x.fetch(),oe.fetch(),se.fetch()}),Ce(()=>{x.clear()}),u([{label:"Card",value:"card"},{label:"Cash",value:"cash"}]);const J=u([{label:c("prices.table.actions"),id:"actions"},{label:c("prices.table.date"),id:"createdAt"},{label:c("prices.table.service"),id:"service"},{label:c("prices.table.piece"),id:"piece"},{label:c("prices.table.price"),id:"title"},{label:c("prices.table.priceNumber"),id:"id"}]);Se(()=>{J.value.forEach(e=>{switch(e.id){case"actions":e.label=c("prices.table.actions");break;case"createdAt":e.label=c("prices.table.date");break;case"service":e.label=c("prices.table.service");break;case"piece":e.label=c("prices.table.piece");break;case"title":e.label=c("prices.table.price");break;case"id":e.label=c("prices.table.priceNumber");break}})});const K=W(()=>{const e=ae.value.map(l=>({createdAt:l.created_at,service:l.serviceinfo.services_title,piece:l.pieceinfo.piece_title,title:l.price,id:l.id,serviceId:l.serviceinfo.id,pieceId:l.pieceinfo.id}));return e.sort((l,t)=>l[C.value]>t[C.value]?V.value?1:-1:l[C.value]<t[C.value]?V.value?-1:1:0),e}),ie=W(()=>K.value.slice(T.value*(U.value-1),T.value*U.value)),ne=(e,l)=>{const t=[];return e.forEach(d=>{l(d)||t.push(d)}),t},ce=(e,l)=>{e?A.value.push(l):A.value=ne(A.value,t=>t.id===l.id)},de=e=>{C.value===e?V.value=!V.value:(V.value=!0,e!="actions"&&(C.value=e))},ue=e=>{console.log(e),U.value=e.pageNumber,T.value=e.pageSize},ve=e=>{x.setSelectedPrice(e),g.value=e.serviceId,p.value=e.pieceId,k.value=String(e.title),F.value=!0},be=e=>{console.log("price",e),x.setSelectedPrice(e),$.value=!0},O=e=>{k.value=e.target.value},he=()=>{x.create(g.value,p.value,k.value).then(e=>{e.error?(console.log("PriceCreateError",e.error),y.value=e.error,g.value="",p.value="",document.getElementById("NewForm").reset(),k.value="",setTimeout(()=>{y.value=null},500)):e.success&&(k.value="",g.value="",p.value="",document.getElementById("NewForm").reset(),_.value=e.message,setTimeout(()=>{_.value=null,h.value=!1},500))}).catch(e=>{console.log(e)}).finally(()=>{g.value="",p.value="",k.value=""})},me=()=>{console.log(E.value),x.edit(E.value.id,g.value,p.value,k.value).then(e=>{e.error?(console.log("PriceCreateError",e.error),y.value=e.error,g.value="",p.value="",document.getElementById("EditForm").reset(),k.value="",setTimeout(()=>{y.value=null},500)):e.success&&(g.value="",p.value="",document.getElementById("EditForm").reset(),_.value=e.message,F.value=!1,setTimeout(()=>{_.value=null,F.value=!1},500))}).catch(e=>{console.log(e)}).finally(()=>{g.value="",p.value="",k.value=""})},ge=()=>{console.log("deleteId",E.value.id),x.delete(E.value.id).then(e=>{console.log(e)}).catch(e=>{console.log(e)}).finally(()=>{})},pe=()=>{p.value="",g.value="",E.value=null,k.value=""};return(e,l)=>(i(),n(w,null,[f(q,{modelValue:h.value,"onUpdate:modelValue":l[4]||(l[4]=t=>h.value=t),title:s(c)("prices.modal.create.title"),"button-label":s(c)("common.save"),button:"bg-main",onConfirm:he,"has-cancel":"",showModal:!0},{default:B(()=>[f(s(X),{"validation-schema":s(b),id:"NewForm"},{default:B(()=>[a("div",null,[qe,N(a("select",{id:"countries",name:"service","onUpdate:modelValue":l[0]||(l[0]=t=>g.value=t),onChange:l[1]||(l[1]=(...t)=>e.handleSelectionChange&&e.handleSelectionChange(...t)),class:"bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"},[(i(!0),n(w,null,S(s(G),t=>(i(),n("option",{value:t.id},m(t.services_title),9,ze))),256))],544),[[M,g.value]])]),a("div",null,[He,N(a("select",{id:"countries","onUpdate:modelValue":l[2]||(l[2]=t=>p.value=t),name:"piece",onChange:l[3]||(l[3]=(...t)=>e.handleSelectionChange&&e.handleSelectionChange(...t)),class:"bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"},[(i(!0),n(w,null,S(s(R),t=>(i(),n("option",{value:t.id},m(t.piece_title),9,Re))),256))],544),[[M,p.value]])]),f(Pe,{"input-type":"text",name:"price",onInput:O,value:k.value,placeholder:s(c)("prices.modal.create.placeholder"),class:"w-full mt-5 mb-6 font-readex text-base font-light focus:outline-none"},null,8,["value","placeholder"]),y.value?(i(),n("div",Ge,[a("p",Je,m(y.value),1)])):P("",!0),_.value?(i(),n("div",Ke,[a("p",Oe,m(_.value),1)])):P("",!0)]),_:1},8,["validation-schema"])]),_:1},8,["modelValue","title","button-label"]),f(q,{modelValue:F.value,"onUpdate:modelValue":l[9]||(l[9]=t=>F.value=t),title:s(c)("prices.modal.edit.title"),button:"bg-main","button-label":s(c)("common.update"),onConfirm:me,"has-cancel":"",showModal:!0},{default:B(()=>[f(s(X),{"validation-schema":s(b),id:"EditForm"},{default:B(()=>{var t;return[a("div",null,[Qe,N(a("select",{id:"countries","onUpdate:modelValue":l[5]||(l[5]=d=>g.value=d),onChange:l[6]||(l[6]=(...d)=>e.handleSelectionChange&&e.handleSelectionChange(...d)),class:"bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"},[(i(!0),n(w,null,S(s(G),d=>(i(),n("option",{value:d.id},m(d.services_title),9,We))),256))],544),[[M,g.value]])]),a("div",null,[Xe,N(a("select",{id:"countries","onUpdate:modelValue":l[7]||(l[7]=d=>p.value=d),onChange:l[8]||(l[8]=(...d)=>e.handleSelectionChange&&e.handleSelectionChange(...d)),class:"bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"},[(i(!0),n(w,null,S(s(R),d=>(i(),n("option",{value:d.id},m(d.piece_title),9,Ye))),256))],544),[[M,p.value]])]),Ze,a("div",et,[a("input",{type:"text",name:"price",placeholder:"Price",class:"w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9",value:(t=s(E))==null?void 0:t.title,onInput:O},null,40,tt)]),y.value?(i(),n("div",lt,[a("p",at,m(y.value),1)])):P("",!0),_.value?(i(),n("div",rt,[a("p",ot,m(_.value),1)])):P("",!0)]}),_:1},8,["validation-schema"])]),_:1},8,["modelValue","title","button-label"]),f(q,{modelValue:$.value,"onUpdate:modelValue":l[10]||(l[10]=t=>$.value=t),title:s(c)("prices.modal.delete.title"),button:"bg-main","has-cancel":"",onConfirm:ge},{default:B(()=>[st]),_:1},8,["modelValue","title"]),A.value.length?(i(),n("div",it,[(i(!0),n(w,null,S(A.value,t=>(i(),n("span",{key:t.id,class:"inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"},m(t.name),1))),128))])):P("",!0),a("table",null,[a("thead",null,[a("tr",null,[(i(!0),n(w,null,S(J.value,t=>(i(),n("th",{key:t.id,onClick:d=>de(t.id),class:"cursor-pointer"},[N(f(Le,{path:V.value?s(Fe):s(Ue),class:"text-gray-600"},null,8,["path"]),[[Y,t.id===C.value]]),Ee(" "+m(t.label),1)],8,nt))),128)),r.checkable?(i(),n("th",ct)):P("",!0)])]),a("tbody",null,[a("tr",null,[N(a("td",dt,[a("div",ut,[f(s($e),{"animation-duration":1e3,size:60,color:"#4BC49A"})])],512),[[Y,s(re)]])]),(i(!0),n(w,null,S(ie.value,t=>(i(),n("tr",{key:t.id},[a("td",vt,[f(De,{type:"justify-start lg:justify-end","no-wrap":""},{default:B(()=>[f(z,{color:"brandGreen",icon:s(Ae),small:"",border:"border",borderColor:"brandBorder",onClick:d=>be(t)},null,8,["icon","onClick"]),f(z,{color:"white",icon:s(Ie),small:"",border:"border",borderColor:"brandBorder",onClick:()=>ve(t)},null,8,["icon","onClick"])]),_:2},1024)]),a("td",bt,[a("small",ht,m(s(Ve)(new Date(t.createdAt),"dd-MM-yyyy HH:mm")),1)]),a("td",mt,m(t.service),1),a("td",gt,m(t.piece),1),a("td",pt,m(t.title),1),a("td",ft,m(t.id),1),r.checkable?(i(),Be(Te,{key:0,onChecked:l[11]||(l[11]=d=>ce(d,e.client))})):P("",!0)]))),128))])]),f(s(Ne),{modelValue:U.value,"onUpdate:modelValue":l[12]||(l[12]=t=>U.value=t),"total-row":K.value.length,language:"en","page-size-menu":[5,10,20],onChange:ue,class:"my-8"},null,8,["modelValue","total-row"])],64))}},Vt={__name:"Header",props:{},emits:["handleClickNewPiece"],setup(r,{emit:o}){const{t:v}=le();return u(!1),(b,h)=>(i(),n("div",null,[f(z,{label:s(v)("prices.create"),labelColor:"text-white",icon:s(Me),color:"text-white",bgColor:"!bg-main",border:"border",outline:"",borderColor:"brandBorder",onClick:h[0]||(h[0]=H=>o("handleClickNewPiece"))},null,8,["label","icon"])]))}};export{Vt as _,Et as a};
