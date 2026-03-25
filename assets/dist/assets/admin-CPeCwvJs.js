class w{constructor(){this.modal=document.getElementById("corbidev-modal"),this.modal||this.create(),this.hide(),this.bind()}create(){const t=document.createElement("div");t.id="corbidev-modal",t.classList.add("hidden"),t.innerHTML=`
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
        `,document.body.appendChild(t),this.modal=t}bind(){document.addEventListener("click",t=>{t.target.id==="cdr-modal-close"&&this.hide()})}show(t,e="error"){const i=this.modal.querySelector("#cdr-modal-message");i&&(i.textContent=t),this.modal.classList.remove("hidden"),this.modal.dataset.type=e}hide(){this.modal&&this.modal.classList.add("hidden")}}const{__:c}=window.wp.i18n;async function d(a,t={}){var s;if(!window.cdr_ajax)throw new Error(c("Missing AJAX configuration","corbidev"));let e;try{e=await fetch(window.cdr_ajax.ajax_url,{method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"},body:new URLSearchParams({action:a,nonce:window.cdr_ajax.nonce,...t})})}catch(m){throw console.error("Network error:",m),new Error(c("Network error","corbidevrepositories"))}if(!e.ok)throw new Error(`${c("Server error","corbidevrepositories")} (${e.status})`);let i=await e.text(),r;try{r=JSON.parse(i)}catch(m){throw console.error("INVALID JSON RESPONSE:",i),new Error(c("Invalid server response","corbidevrepositories"))}if(!r||typeof r!="object")throw new Error(c("Invalid server response","corbidevrepositories"));if(!r.success)throw new Error(((s=r==null?void 0:r.data)==null?void 0:s.message)||(typeof(r==null?void 0:r.data)=="string"?r.data:null)||c("Unknown error","corbidevrepositories"));return r.data}const{__:o}=window.wp.i18n;function u(){const a=document.getElementById("cdr-repo-form");a&&a.addEventListener("submit",async t=>{t.preventDefault();const e=new FormData(a);await d("cdr_repo_add",{name:e.get("name"),token:e.get("token")}),n(o("Repository added","corbidevrepositories"))}),document.addEventListener("click",async t=>{const e=t.target.closest("[data-action]");if(!e)return;const i=e.dataset.action,r=e.closest("tr");try{switch(i){case"repo-delete":{if(!confirm(o("Delete this repository?","corbidevrepositories")))return;await d("cdr_repo_delete",{name:e.dataset.name}),r==null||r.remove(),n(o("Repository deleted","corbidevrepositories"));break}case"install":{l(e,!0),await d("cdr_install_item",{type:e.dataset.type,owner:e.dataset.owner,name:e.dataset.name}),v(r,"installed",e.dataset),n(o("Installed","corbidevrepositories"));break}case"activate":{l(e,!0),await d("cdr_activate_item",{name:e.dataset.name}),v(r,"active",e.dataset),n(o("Activated","corbidevrepositories"));break}case"deactivate":{l(e,!0),await d("cdr_deactivate_item",{name:e.dataset.name}),v(r,"inactive",e.dataset),n(o("Deactivated","corbidevrepositories"));break}case"delete":{if(!confirm(o("Delete this item?","corbidevrepositories")))return;l(e,!0),await d("cdr_delete_item",{type:e.dataset.type,name:e.dataset.name}),r==null||r.remove(),n(o("Deleted","corbidevrepositories"));break}case"update":{l(e,!0),await d("cdr_update_item",{type:e.dataset.type,owner:e.dataset.owner,name:e.dataset.name}),n(o("Updated","corbidevrepositories"));break}}}catch(s){console.error(s),alert((s==null?void 0:s.message)||o("An error occurred","corbidevrepositories"))}finally{l(e,!1)}})}function v(a,t,e={}){if(!a)return;const i=a.querySelector("td:last-child");i&&(t==="installed"&&(i.innerHTML=`
            <span style="color:green; font-weight:bold;">
                ✔ ${o("Installed","corbidevrepositories")}
            </span>
        `),t==="active"&&(i.innerHTML=`
            <button
                class="button"
                data-action="deactivate"
                data-name="${e.name||""}">
                ${o("Deactivate","corbidevrepositories")}
            </button>
        `),t==="inactive"&&(i.innerHTML=`
            <button
                class="button button-primary"
                data-action="activate"
                data-name="${e.name||""}">
                ${o("Activate","corbidevrepositories")}
            </button>
        `))}function l(a,t){a&&(a.disabled=t,a.classList.toggle("is-loading",t))}function n(a){const t=document.createElement("div");t.className="notice notice-success is-dismissible",t.innerHTML=`<p>${a}</p>`,document.body.prepend(t),setTimeout(()=>{t.remove()},3e3)}const{__:p}=window.wp.i18n;function b(){const a=new w;async function t(e){const i=e.dataset.type,r=e.dataset.owner,s=e.dataset.name;if(!i||!r||!s){a.show("Données manquantes","error");return}e.disabled=!0,e.innerText=p("Install ...","corbidev");try{await d("cdr_install_item",{type:i,owner:r,name:s}),a.show(p("Install success","corbidev"),"success"),e.innerText="Installé",e.classList.add("disabled")}catch(m){a.show(m.message||"Erreur serveur","error"),e.disabled=!1,e.innerText=p("Installed","corbidev")}}document.addEventListener("click",e=>{const i=e.target.closest('[data-action="install"]');i&&(e.preventDefault(),t(i))})}u();b();window.corbidevModal=new w;
