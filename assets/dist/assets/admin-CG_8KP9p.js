class o{constructor(){this.modal=document.getElementById("corbidev-modal"),this.modal||this.create(),this.hide(),this.bind()}create(){const e=document.createElement("div");e.id="corbidev-modal",e.classList.add("hidden"),e.innerHTML=`
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
        `,document.body.appendChild(e),this.modal=e}bind(){document.addEventListener("click",e=>{e.target.id==="cdr-modal-close"&&this.hide()})}show(e,a="error"){const d=this.modal.querySelector("#cdr-modal-message");d&&(d.textContent=e),this.modal.classList.remove("hidden"),this.modal.dataset.type=a}hide(){this.modal&&this.modal.classList.add("hidden")}}async function s(t,e={}){const d=await(await fetch(window.cdr_ajax.ajax_url,{method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded"},body:new URLSearchParams({action:t,nonce:window.cdr_ajax.nonce,...e})})).text();try{return JSON.parse(d)}catch{throw console.error("INVALID JSON RESPONSE:",d),new Error("Invalid JSON response")}}function i(){const t=document.getElementById("cdr-repo-form");t&&t.addEventListener("submit",async e=>{e.preventDefault();const a=new FormData(t);await s("cdr_repo_add",{name:a.get("name"),token:a.get("token")}),location.reload()}),document.addEventListener("click",async e=>{const a=e.target.closest(".cdr-delete");a&&confirm("Supprimer ce dépôt ?")&&(await s("cdr_repo_delete",{name:a.dataset.name}),location.reload())})}i();const r=new o;async function c(t){const e=t.dataset.type,a=t.dataset.owner,d=t.dataset.name;t.disabled=!0,t.innerText="Installation...";try{await s("cdr_install_item",{type:e,owner:a,name:d}),r.show("Installation réussie","success"),t.innerText="Installé",t.classList.add("disabled")}catch(n){r.show(n.message||"Erreur serveur","error"),t.disabled=!1,t.innerText="Installer"}}document.addEventListener("click",t=>{const e=t.target.closest('[data-action="install"]');e&&(t.preventDefault(),c(e))});
