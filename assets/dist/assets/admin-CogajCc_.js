class n{constructor(){this.modal=document.getElementById("corbidev-modal"),this.modal||this.create(),this.hide(),this.bind()}create(){const e=document.createElement("div");e.id="corbidev-modal",e.classList.add("hidden"),e.innerHTML=`
            <div class="cdr-modal-overlay">
                <div class="cdr-modal">
                    <div id="cdr-modal-message" class="cdr-modal-message"></div>
                    <div class="cdr-modal-actions">
                        <button id="cdr-modal-close" class="cdr-btn">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        `,document.body.appendChild(e),this.modal=e}bind(){document.addEventListener("click",e=>{e.target.id==="cdr-modal-close"&&this.hide()})}show(e,s="error"){const d=this.modal.querySelector("#cdr-modal-message");d&&(d.textContent=e),this.modal.classList.remove("hidden"),this.modal.dataset.type=s}hide(){this.modal&&this.modal.classList.add("hidden")}}async function i(a,e={}){const d=await(await fetch(window.cdr_ajax.ajax_url,{method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded"},body:new URLSearchParams({action:a,nonce:window.cdr_ajax.nonce,...e})})).text();try{return JSON.parse(d)}catch{throw console.error("INVALID JSON RESPONSE:",d),new Error("Invalid JSON response")}}const r=new n;async function o(a){const e=a.dataset.type,s=a.dataset.owner,d=a.dataset.name;a.disabled=!0,a.innerText="Installation...";try{await i("cdr_install_item",{type:e,owner:s,name:d}),r.show("Installation réussie","success"),a.innerText="Installé",a.classList.add("disabled")}catch(t){r.show(t.message||"Erreur serveur","error"),a.disabled=!1,a.innerText="Installer"}}document.addEventListener("click",a=>{const e=a.target.closest('[data-action="install"]');e&&(a.preventDefault(),o(e))});
