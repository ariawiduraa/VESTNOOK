// Custom Confirmation Modal VESTNOOK with Inline Styles (Tailwind compiler independent)

window.confirmAction = function(form, event, message, type = 'danger') {
    event.preventDefault();
    
    showCustomConfirmModal(message, type, function() {
        form.submit();
    });
    
    return false;
};

function showCustomConfirmModal(message, type, onConfirm) {
    // 1. Create Modal elements dynamically if they don't exist
    let overlay = document.getElementById('custom-confirm-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'custom-confirm-overlay';
        overlay.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); transition: all 0.3s ease; opacity: 0; pointer-events: none;';
        
        const card = document.createElement('div');
        card.id = 'custom-confirm-card';
        card.style.cssText = 'background: #1e293b; border: 1px solid #334155; border-radius: 16px; max-width: 380px; width: 100%; padding: 24px; margin: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); transform: scale(0.95); transition: all 0.3s ease; opacity: 0; text-align: center; font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;';
        
        card.innerHTML = `
            <!-- Icon -->
            <div id="confirm-icon-container" style="margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; height: 48px; width: 48px; border-radius: 50%;">
                <svg id="confirm-icon" style="height: 24px; width: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            
            <!-- Title -->
            <h3 style="font-size: 18px; font-weight: 700; color: #ffffff; margin-top: 0; margin-bottom: 8px;">Konfirmasi Tindakan</h3>
            
            <!-- Message -->
            <p id="confirm-message" style="font-size: 14px; color: #cbd5e1; margin-top: 0; margin-bottom: 24px; line-height: 1.5; text-align: center;"></p>
            
            <!-- Actions -->
            <div style="display: flex; justify-content: center; gap: 12px;">
                <button type="button" id="confirm-btn-cancel" style="padding: 10px 20px; font-size: 13px; font-weight: 600; color: #94a3b8; background: #334155; border: 1px solid #475569; border-radius: 9999px; transition: all 0.2s; cursor: pointer;">
                    Batal
                </button>
                <button type="button" id="confirm-btn-ok" style="padding: 10px 20px; font-size: 13px; font-weight: 600; color: #ffffff; border: none; border-radius: 9999px; transition: all 0.2s; cursor: pointer;">
                    Ya, Lanjutkan
                </button>
            </div>
        `;
        
        overlay.appendChild(card);
        document.body.appendChild(overlay);
        
        // Add button hover transitions
        const btnCancel = document.getElementById('confirm-btn-cancel');
        btnCancel.addEventListener('mouseenter', () => {
            btnCancel.style.background = '#475569';
            btnCancel.style.color = '#f1f5f9';
        });
        btnCancel.addEventListener('mouseleave', () => {
            btnCancel.style.background = '#334155';
            btnCancel.style.color = '#94a3b8';
        });
    }
    
    const card = document.getElementById('custom-confirm-card');
    const messageEl = document.getElementById('confirm-message');
    const btnCancel = document.getElementById('confirm-btn-cancel');
    const btnOk = document.getElementById('confirm-btn-ok');
    const iconContainer = document.getElementById('confirm-icon-container');
    const iconSvg = document.getElementById('confirm-icon');
    
    // 2. Configure modal based on type
    messageEl.textContent = message;
    
    if (type === 'danger') {
        btnOk.style.background = '#dc2626';
        btnOk.style.boxShadow = '0 4px 12px rgba(220, 38, 38, 0.25)';
        iconContainer.style.background = 'rgba(239, 68, 68, 0.1)';
        iconSvg.style.color = '#ef4444';
        
        // Hover effects
        btnOk.onmouseenter = () => btnOk.style.background = '#b91c1c';
        btnOk.onmouseleave = () => btnOk.style.background = '#dc2626';
    } else {
        btnOk.style.background = '#2563eb';
        btnOk.style.boxShadow = '0 4px 12px rgba(37, 99, 235, 0.25)';
        iconContainer.style.background = 'rgba(59, 130, 246, 0.1)';
        iconSvg.style.color = '#3b82f6';
        
        // Hover effects
        btnOk.onmouseenter = () => btnOk.style.background = '#1d4ed8';
        btnOk.onmouseleave = () => btnOk.style.background = '#2563eb';
    }
    
    // 3. Setup event listeners
    const cleanUp = () => {
        // Hide animation
        overlay.style.opacity = '0';
        overlay.style.pointerEvents = 'none';
        card.style.transform = 'scale(0.95)';
        card.style.opacity = '0';
        
        // Remove listeners
        btnCancel.onclick = null;
        btnOk.onclick = null;
    };
    
    btnCancel.onclick = function() {
        cleanUp();
    };
    
    btnOk.onclick = function() {
        cleanUp();
        onConfirm();
    };
    
    // 4. Show animation (with tiny timeout to trigger transition)
    setTimeout(() => {
        overlay.style.opacity = '1';
        overlay.style.pointerEvents = 'auto';
        card.style.transform = 'scale(1)';
        card.style.opacity = '1';
    }, 20);
}
