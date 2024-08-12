import{d as h,g as a,a as l,u as r,b as e,c,w as o,F as d,h as u,o as s,Z as p,e as n,k as i}from"./app-Cs4v3zg9.js";const g={class:"bg-gray-50 duration-700 ease-in-out text-black/50 dark:text-black/50"},f={class:"relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"},m={class:"relative w-full min-h-screen"},x={class:"flex items-center justify-center min-h-screen space-x-6"},_=e("div",{class:"duration-1000 ease-in-out min-h-screen pl-3 text-white [clip-path:polygon(0_0,_100%_0,_64%_100%,_0%_100%)] w-2/3 flex bg-blue-800 justify-left items-center"},[e("h1",{class:"text-5xl font-bold"},"Welcome to Guest Manager 2.0")],-1),b={key:0,class:"min-h-screen flex justify-start w-1/3 pr-30 items-center space-x-2"},v=e("main",{class:"mt-6"},[e("div",{class:"grid gap-6 lg:grid-cols-2 lg:gap-8"})],-1),F=h({__name:"Welcome",props:{canLogin:{type:Boolean},canRegister:{type:Boolean},laravelVersion:{},phpVersion:{}},setup(k){return(t,y)=>(s(),a(d,null,[l(r(p),{title:"Welcome"}),e("div",g,[e("div",f,[e("div",m,[e("div",x,[_,t.canLogin?(s(),a("nav",b,[t.$page.props.auth.user?(s(),c(r(i),{key:0,href:t.route("dashboard"),class:"rounded-md px-3 py-2 text-black ring-1 ring-transparent duration-700 ease-in-out hover:text-white-500"},{default:o(()=>[n(" Dashboard ")]),_:1},8,["href"])):(s(),a(d,{key:1},[l(r(i),{href:t.route("login"),class:"rounded-md px-3 py-2 text-black ring-1 ring-transparent duration-700 ease-in-out hover:px-5 hover:py-4 hover:text-white-500 bg-blue-800 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-black/80 dark:focus-visible:ring-white"},{default:o(()=>[n(" Log in ")]),_:1},8,["href"]),t.canRegister?(s(),c(r(i),{key:0,href:t.route("register"),class:"rounded-md px-3 py-2 text-black ring-1 ring-transparent bg-blue-800 duration-700 ease-in-out hover:px-5 hover:py-4 hover:text-white-500 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-black/80 dark:focus-visible:ring-white"},{default:o(()=>[n(" Register ")]),_:1},8,["href"])):u("",!0)],64))])):u("",!0)]),v])])])],64))}});export{F as default};
