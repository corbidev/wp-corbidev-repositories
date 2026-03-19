import"./app-BZ8KMhW_.js";class r{constructor(){this.modal=document.getElementById("corbidev-modal"),this.modal||this.create(),this.bind()}create(){const a=document.createElement("div");a.id="corbidev-modal",a.innerHTML=`
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
        `,document.body.appendChild(a),this.modal=a}bind(){document.addEventListener("click",a=>{a.target.id==="cdr-modal-close"&&this.hide()})}show(a,d="error"){const s=this.modal.querySelector("#cdr-modal-message");s.textContent=a,this.modal.classList.remove("hidden"),this.modal.dataset.type=d}hide(){this.modal.classList.add("hidden")}}async function o(e,a={}){var t;const s=await(await fetch(window.cdr_ajax.ajax_url,{method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded"},body:new URLSearchParams({action:e,nonce:window.cdr_ajax.nonce,...a})})).json();if(!s.success)throw new Error(((t=s.data)==null?void 0:t.message)||"Erreur inconnue");return s.data}const n=new r;async function c(e){const a=e.dataset.type,d=e.dataset.owner,s=e.dataset.name;e.disabled=!0,e.innerText="Installation...";try{await o("cdr_install_item",{type:a,owner:d,name:s}),n.show("Installation réussie","success"),e.innerText="Installé",e.classList.add("disabled")}catch(t){n.show(t.message||"Erreur serveur","error"),e.disabled=!1,e.innerText="Installer"}}document.addEventListener("click",e=>{const a=e.target.closest('[data-action="install"]');a&&(e.preventDefault(),c(a))});
