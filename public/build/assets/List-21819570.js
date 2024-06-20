import{_ as P,b as z,c as ie,d as ce}from"./CardBoxModal-baf6546f.js";import{A as re,_ as de,a as ve}from"./SectionMain-294f5b0a.js";import{r as s,u as O,x as me,B as fe,N as he,a5 as be,a6 as _e,w as pe,l as q,o as r,c as d,E as u,z as k,b as n,a1 as ge,a0 as ke,a,s as y,a8 as T,t as f,q as V,e as G,F as E,d as j,G as J,J as we,m as ye,$ as Ce}from"./app-c03dcb6d.js";import{m as xe,a as Se,b as Ne,d as Ve,f as $e}from"./mdi-4b5bd968.js";import{_ as Ae}from"./BaseIcon-398b9207.js";import{u as K}from"./services-9a07652a.js";const Be={class:"flex justify-center pt-3 items-center gap-10"},Me={class:"flex items-center gap-2"},Te=a("label",null,"Check",-1),Ee={class:"flex items-center gap-2"},De=a("label",null,"Show",-1),Ue={key:0},Fe={class:"text-red-500 text-sm"},Ie={key:1},Pe={class:"text-green-400 text-sm"},je={class:"border border-gray-400 rounded-lg mx-10"},ze=["value"],He={class:"flex justify-center pt-3 items-center gap-10"},Re={class:"flex items-center gap-2"},Le=a("label",null,"Check",-1),qe={class:"flex items-center gap-2"},Ge=a("label",null,"Show",-1),Je=a("p",null,"Are you sure?",-1),Ke={key:0,class:"p-3 bg-gray-100/50 dark:bg-slate-800"},Oe=["onClick"],Qe={key:0},We={colspan:"6"},Xe={class:"w-full flex justify-center"},Ye={class:"before:hidden whitespace-nowrap"},Ze={"data-label":"Created",class:"whitespace-nowrap"},el={class:"text-gray-500 dark:text-slate-400"},ll={"data-label":"Title"},al={"data-label":"Title"},tl={"data-label":"Title"},sl={"data-label":"PieceId"},ol={__name:"TableDataClient",props:{checkable:Boolean,setModalActive:{type:Boolean,default:!1}},emits:{},setup($,{expose:C,emit:x}){const h=s(!1);C({showNewModal:()=>{document.getElementById("NewForm").reset(),i.value=!1,c.value=!1,h.value=!0}});const{t:o}=O(),D=s(!1),b=K(),{services:Q,selectedService:U,loading:W}=me(K()),F=s(!1),I=s(5),S=s(1),N=s([]);s(!1);const w=s(!1),_=s("");s([]);const v=s(""),i=s(!1),p=s(0),c=s(!1),g=s(0),B=s(null),M=s(null);fe(()=>{b.fetch()}),he(()=>{b.clear()});const X=be().shape({service:_e().min(1).required()}),H=s([{label:o("services.table.actions"),id:"actions"},{label:o("services.table.date"),id:"createdAt"},{label:o("services.table.checked"),id:"checked"},{label:o("services.table.shown"),id:"shown"},{label:o("services.table.service"),id:"title"},{label:o("services.table.serviceNumber"),id:"id"}]);pe(()=>{H.value.forEach(e=>{switch(e.id){case"actions":e.label=o("services.table.actions");break;case"createdAt":e.label=o("services.table.date");break;case"title":e.label=o("services.table.service");break;case"checked":e.label=o("services.table.checked");break;case"shown":e.label=o("services.table.shown");break;case"id":e.label=o("services.table.serviceNumber");break}})});const R=q(()=>{const e=Q.value.map(t=>({createdAt:t.created_at,title:t.services_title,id:t.id,isChecked:t.is_checked,isShown:t.is_shown}));return e.sort((t,l)=>t[_.value]>l[_.value]?w.value?1:-1:t[_.value]<l[_.value]?w.value?-1:1:0),e}),Y=q(()=>R.value.slice(I.value*(S.value-1),I.value*S.value)),Z=(e,t)=>{const l=[];return e.forEach(m=>{t(m)||l.push(m)}),l},ee=(e,t)=>{e?N.value.push(t):N.value=Z(N.value,l=>l.id===t.id)},le=e=>{_.value===e?w.value=!w.value:(w.value=!0,e!="actions"&&(_.value=e))},ae=e=>{console.log(e),S.value=e.pageNumber,I.value=e.pageSize},te=e=>{b.setSelectedService(e),v.value=e.title,p.value=e.isChecked,g.value=e.isShown,e.isChecked==1?i.value=!0:i.value=!1,e.isShown==1?c.value=!0:c.value=!1,D.value=!0},se=e=>{b.setSelectedService(e),F.value=!0},L=e=>{v.value=e.target.value},oe=()=>{i.value?p.value=1:p.value=0,c.value?g.value=1:g.value=0,console.log("newTitile",v.value),b.create(v.value,p.value,g.value).then(e=>{e.error?(console.log("ServiceCreateError",e.error),v.value="",i.value=!1,c.value=!1,document.getElementById("NewForm").reset(),B.value=e.error,setTimeout(()=>{B.value=null},500)):e.success&&(v.value="",i.value=!1,c.value=!1,document.getElementById("NewForm").reset(),M.value=e.message,setTimeout(()=>{M.value=null,h.value=!1},500))}).catch(e=>{console.log(e)}).finally(()=>{v.value="",i.value=!1,c.value=!1})},ne=()=>{i.value?p.value=1:p.value=0,c.value?g.value=1:g.value=0,b.edit(U.value.id,v.value,p.value,g.value).then(e=>{console.log(e),e&&(v.value="",i.value=!1,c.value=!1)}).catch(e=>{console.log(e)}).finally(()=>{v.value="",i.value=!1,c.value=!1})},ue=()=>{b.delete(U.value.id).then(e=>{console.log(e)}).catch(e=>{console.log(e)}).finally(()=>{})};return(e,t)=>(r(),d(E,null,[u(P,{modelValue:h.value,"onUpdate:modelValue":t[2]||(t[2]=l=>h.value=l),title:n(o)("services.modal.create.title"),"button-label":n(o)("common.save"),button:"bg-main",onConfirm:oe,"has-cancel":"",showModal:!0},{default:k(()=>[u(n(ge),{"validation-schema":n(X),id:"NewForm"},{default:k(()=>[u(ke,{"input-type":"text",name:"service",onInput:L,value:v.value,placeholder:n(o)("services.modal.create.placeholder"),class:"w-full mb-6 font-readex text-base font-light focus:outline-none"},null,8,["value","placeholder"]),a("div",Be,[a("div",Me,[y(a("input",{type:"checkbox","onUpdate:modelValue":t[0]||(t[0]=l=>i.value=l)},null,512),[[T,i.value]]),Te]),a("div",Ee,[y(a("input",{type:"checkbox","onUpdate:modelValue":t[1]||(t[1]=l=>c.value=l)},null,512),[[T,c.value]]),De])]),B.value?(r(),d("div",Ue,[a("p",Fe,f(B.value),1)])):V("",!0),M.value?(r(),d("div",Ie,[a("p",Pe,f(M.value),1)])):V("",!0)]),_:1},8,["validation-schema"])]),_:1},8,["modelValue","title","button-label"]),u(P,{modelValue:D.value,"onUpdate:modelValue":t[5]||(t[5]=l=>D.value=l),title:n(o)("services.modal.edit.title"),button:"bg-main","has-cancel":"",onConfirm:ne},{default:k(()=>{var l;return[a("div",je,[a("input",{type:"text",placeholder:"",class:"w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9",value:(l=n(U))==null?void 0:l.title,onInput:L},null,40,ze)]),a("div",He,[a("div",Re,[y(a("input",{type:"checkbox","onUpdate:modelValue":t[3]||(t[3]=m=>i.value=m)},null,512),[[T,i.value]]),Le]),a("div",qe,[y(a("input",{type:"checkbox","onUpdate:modelValue":t[4]||(t[4]=m=>c.value=m)},null,512),[[T,c.value]]),G(),Ge])])]}),_:1},8,["modelValue","title"]),u(P,{modelValue:F.value,"onUpdate:modelValue":t[6]||(t[6]=l=>F.value=l),title:n(o)("services.modal.delete.title"),button:"bg-main","has-cancel":"",onConfirm:ue},{default:k(()=>[Je]),_:1},8,["modelValue","title"]),N.value.length?(r(),d("div",Ke,[(r(!0),d(E,null,j(N.value,l=>(r(),d("span",{key:l.id,class:"inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"},f(l.name),1))),128))])):V("",!0),a("table",null,[a("thead",null,[a("tr",null,[(r(!0),d(E,null,j(H.value,l=>(r(),d("th",{key:l.id,onClick:m=>le(l.id),class:"cursor-pointer"},[y(u(Ae,{path:w.value?n(xe):n(Se),class:"text-gray-600"},null,8,["path"]),[[J,l.id===_.value]]),G(" "+f(l.label),1)],8,Oe))),128)),$.checkable?(r(),d("th",Qe)):V("",!0)])]),a("tbody",null,[a("tr",null,[y(a("td",We,[a("div",Xe,[u(n(re),{"animation-duration":1e3,size:60,color:"#4BC49A"})])],512),[[J,n(W)]])]),(r(!0),d(E,null,j(Y.value,l=>(r(),d("tr",{key:l.id},[a("td",Ye,[u(ie,{type:"justify-start lg:justify-end","no-wrap":""},{default:k(()=>[u(z,{color:"brandGreen",icon:n(Ne),small:"",border:"border",borderColor:"brandBorder",onClick:m=>se(l)},null,8,["icon","onClick"]),u(z,{color:"white",icon:n(Ve),small:"",border:"border",borderColor:"brandBorder",onClick:()=>te(l)},null,8,["icon","onClick"])]),_:2},1024)]),a("td",Ze,[a("small",el,f(n(we)(new Date(l.createdAt),"dd-MM-yyyy HH:mm")),1)]),a("td",ll,f(l.isChecked?"true":"false")+" ",1),a("td",al,f(l.isShown?"true":"false"),1),a("td",tl,f(l.title),1),a("td",sl,f(l.id),1),$.checkable?(r(),ye(de,{key:0,onChecked:t[7]||(t[7]=m=>ee(m,e.client))})):V("",!0)]))),128))])]),u(n(Ce),{modelValue:S.value,"onUpdate:modelValue":t[8]||(t[8]=l=>S.value=l),"total-row":R.value.length,language:"en","page-size-menu":[5,10,20],onChange:ae,class:"my-8"},null,8,["modelValue","total-row"])],64))}},nl={__name:"Header",props:{},emits:["handleClickNewService"],setup($,{emit:C}){const{t:x}=O();return s(!1),(h,A)=>(r(),d("div",null,[u(z,{label:n(x)("services.create"),labelColor:"text-white",icon:n($e),color:"text-white",bgColor:"!bg-main",border:"border",outline:"",borderColor:"brandBorder",onClick:A[0]||(A[0]=o=>C("handleClickNewService"))},null,8,["label","icon"])]))}},ml={__name:"List",setup($){const C=s(!1),x=s(null),h=()=>{console.log("new service"),C.value=!0,x.value.showNewModal()};return(A,o)=>(r(),d("div",null,[u(ve,null,{default:k(()=>[u(nl,{onHandleClickNewService:h}),u(ce,{class:"mb-6 mt-6","has-table":""},{default:k(()=>[u(ol,{ref_key:"tableCompRef",ref:x},null,512)]),_:1})]),_:1})]))}};export{ml as default};