
document.addEventListener('DOMContentLoaded',()=>{
  const menu=document.querySelector('.menu');
  const panel=document.querySelector('.mobile-panel');
  if(menu&&panel) menu.addEventListener('click',()=>panel.classList.toggle('open'));
  const dropdowns=document.querySelectorAll('.navdrop');
  dropdowns.forEach(dropdown=>{
    const trigger=dropdown.querySelector('button');
    if(!trigger) return;
    trigger.setAttribute('aria-expanded','false');
    trigger.addEventListener('click',event=>{
      event.preventDefault();
      const isOpen=dropdown.classList.toggle('open');
      trigger.setAttribute('aria-expanded',String(isOpen));
      dropdowns.forEach(other=>{
        if(other===dropdown) return;
        other.classList.remove('open');
        other.querySelector('button')?.setAttribute('aria-expanded','false');
      });
    });
  });
  document.querySelectorAll('[data-year]').forEach(e=>e.textContent=new Date().getFullYear());

  // Simple calculator interactions
  const calc=document.querySelector('#loanCalc');
  if(calc){
    const out=document.querySelector('#payment');
    const run=()=>{
      const p=+document.querySelector('#amount').value||0;
      const annual=(+document.querySelector('#rate').value||0)/100/12;
      const n=(+document.querySelector('#term').value||1)*12;
      const pay=annual? p*annual/(1-Math.pow(1+annual,-n)):p/n;
      out.textContent='$'+pay.toLocaleString(undefined,{maximumFractionDigits:2});
    };
    calc.querySelectorAll('input').forEach(i=>i.addEventListener('input',run)); run();
  }

  // Send login attempts to the project's real Laravel authentication flow.
  const login=document.querySelector('#demoLogin');
  if(login) login.addEventListener('submit',e=>{
    e.preventDefault();
    window.location.href='/user/login';
  });

  // Search demo
  const search=document.querySelector('#siteSearch');
  if(search) search.addEventListener('submit',e=>{
    e.preventDefault();
    const q=document.querySelector('#q').value.trim();
    const target=document.querySelector('#searchResult');
    if(!q){
      target.innerHTML='<p class="muted">Enter a search term.</p>';
      return;
    }

    const pages=[
      ['checking','checking.html','Checking'],
      ['saving','savings.html','Savings'],
      ['loan','loans.html','Loans'],
      ['mobile','mobile-banking.html','Mobile banking'],
      ['business','business.html','Business banking'],
      ['security','security.html','Security center'],
      ['contact','contact.html','Contact RiverWind'],
      ['location','locations.html','Branch locations'],
      ['blog','blog.html','Financial education']
    ];
    const matches=pages.filter(([term])=>q.toLowerCase().includes(term));
    const results=matches.length?matches:[['','blog.html','Financial education'],['','contact.html','Contact RiverWind']];
    target.innerHTML=`<div class="card"><span class="tag">Search results</span><h3>Results for “${escapeHtml(q)}”</h3>${results.map(([,href,label])=>`<p><a href="${href}">${label} →</a></p>`).join('')}</div>`;
  });

  function escapeHtml(value){
    return value.replace(/[&<>"']/g,char=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[char]));
  }
});
