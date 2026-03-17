<style>
/* Simple toast container */
.custom-toast-container{position:fixed;top:1rem;right:1rem;z-index:1080;display:flex;flex-direction:column;gap:0.5rem}
.custom-toast{min-width:220px;padding:10px 14px;border-radius:6px;color:#fff;box-shadow:0 2px 8px rgba(0,0,0,0.12);font-weight:600;transition:opacity .25s}
.custom-toast.success{background:#28a745}
.custom-toast.error{background:#dc3545}
.custom-toast.info{background:#17a2b8}
.custom-toast.warning{background:#ffc107;color:#000}

/* Simple confirm modal */
.confirm-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.45);display:flex;align-items:center;justify-content:center;z-index:1090}
.confirm-box{background:#fff;padding:20px;border-radius:8px;max-width:420px;width:100%;box-shadow:0 8px 24px rgba(0,0,0,0.2)}
.confirm-title{font-size:18px;font-weight:700;margin-bottom:6px}
.confirm-text{margin-bottom:12px;color:#555}
.confirm-actions{display:flex;gap:8px;justify-content:flex-end}
.confirm-actions button{min-width:80px;padding:6px 10px;border-radius:6px;border:0}
.confirm-actions .btn-cancel{background:#e9ecef}
.confirm-actions .btn-confirm{background:#007bff;color:#fff}
</style>
<div id="__custom_toast_container" class="custom-toast-container" aria-hidden="true"></div>
<template id="__custom_confirm_template">
    <div class="confirm-overlay" role="dialog" aria-modal="true">
        <div class="confirm-box">
            <div class="confirm-title"></div>
            <div class="confirm-text"></div>
            <div class="confirm-actions">
                <button type="button" class="btn-cancel">Cancel</button>
                <button type="button" class="btn-confirm">OK</button>
            </div>
        </div>
    </div>
</template>
<script>
    window.SwalToast = function(type = 'success', title = '', text = ''){
        const container = document.getElementById('__custom_toast_container');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = `custom-toast ${type}`;
        toast.innerHTML = `<div>${title}</div>${text?`<div style="font-weight:400;font-size:13px;margin-top:4px">${text}</div>`:''}`;
        container.appendChild(toast);
        container.setAttribute('aria-hidden','false');
        setTimeout(()=>{ toast.style.opacity = '0'; toast.addEventListener('transitionend', ()=> toast.remove()); }, 3000);
    };

    window.confirmAction = function(opts = {}){
        const { title = 'Are you sure?', text = '', confirmButtonText = 'Yes', cancelButtonText = 'Cancel' } = opts;
        return new Promise(resolve => {
            const tpl = document.getElementById('__custom_confirm_template');
            const node = tpl.content.firstElementChild.cloneNode(true);
            node.querySelector('.confirm-title').textContent = title;
            node.querySelector('.confirm-text').textContent = text;
            node.querySelector('.btn-confirm').textContent = confirmButtonText;
            node.querySelector('.btn-cancel').textContent = cancelButtonText;
            function cleanup(result){
                document.body.removeChild(node);
                resolve(result);
            }
            node.querySelector('.btn-cancel').addEventListener('click', ()=> cleanup(false));
            node.querySelector('.btn-confirm').addEventListener('click', ()=> cleanup(true));
            document.body.appendChild(node);
        });
    };

    document.addEventListener('DOMContentLoaded', function(){
        const flash = {!! json_encode(session()->only(['success','error','warning','info'])) !!};
        Object.entries(flash).forEach(([type, message]) => {
            if (!message) return;
            const icon = type === 'error' ? 'error' : type;
            SwalToast(icon, message, '');
        });

        document.querySelectorAll('.logout-link').forEach(el => {
            el.addEventListener('click', function(e){
                e.preventDefault();
                const title = el.dataset.confirmTitle || 'Confirm logout';
                const text = el.dataset.confirmText || '';
                const confirmButtonText = el.dataset.confirmButton || 'Logout';
                confirmAction({ title, text, confirmButtonText }).then(ok => {
                    if (ok) {
                        const form = document.getElementById('logout-form-navbar');
                        if (form) form.submit();
                    }
                });
            });
        });

        document.querySelectorAll('[data-confirm]').forEach(el => {
            el.addEventListener('click', function(e){
                e.preventDefault();
                const title = el.dataset.confirmTitle || 'Are you sure?';
                const text = el.dataset.confirmText || '';
                const confirmButtonText = el.dataset.confirmButton || 'Yes';
                const method = (el.dataset.confirmMethod || el.dataset.method || 'POST').toUpperCase();
                confirmAction({ title, text, confirmButtonText }).then(ok => {
                    if (!ok) return;
                    if (el.dataset.confirmTarget) {
                        const target = document.querySelector(el.dataset.confirmTarget);
                        if (target && target.tagName === 'FORM') { target.submit(); return; }
                    }
                    if (el.tagName === 'A' && el.href) {
                        if (method === 'GET') { window.location = el.href; return; }
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = el.href;
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        if (token) {
                            const inputToken = document.createElement('input');
                            inputToken.type = 'hidden';
                            inputToken.name = '_token';
                            inputToken.value = token;
                            form.appendChild(inputToken);
                        }
                        if (method !== 'POST') {
                            const inputMethod = document.createElement('input');
                            inputMethod.type = 'hidden';
                            inputMethod.name = '_method';
                            inputMethod.value = method;
                            form.appendChild(inputMethod);
                        }
                        document.body.appendChild(form);
                        form.submit();
                        return;
                    }
                    const parentForm = el.closest('form');
                    if (parentForm) { parentForm.submit(); return; }
                    const href = el.getAttribute('href');
                    if (href) window.location = href;
                });
            });
        });
    });
</script>
