/**
 * Pax TV Launcher - Client Interactions & Modal Engine
 */

function openModal(modalId) {
    // Immediately close and hide any active modal
    document.querySelectorAll('.paxtv-modal-overlay').forEach(m => {
        if (m.id !== modalId) {
            m.classList.remove('active');
            m.style.display = 'none';
            m.setAttribute('aria-hidden', 'true');
        }
    });

    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
        // Force reflow for transition
        void modal.offsetHeight;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modal) {
    if (modal) {
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
        setTimeout(() => {
            modal.style.display = 'none';
            // Only restore scroll if no other active modals
            if (!document.querySelector('.paxtv-modal-overlay.active')) {
                document.body.style.overflow = '';
            }
        }, 200);
    }
}

window.openModal = openModal;
window.closeModal = closeModal;

document.addEventListener('DOMContentLoaded', function () {

    // Trigger popup on click
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-open-popup]');
        if (trigger) {
            e.preventDefault();
            const modalId = trigger.getAttribute('data-open-popup');
            openModal(modalId);
            return;
        }

        // Check anchor links targeting modal IDs
        const anchor = e.target.closest('a[href^="#"]');
        if (anchor) {
            const href = anchor.getAttribute('href');
            if (href === '#request-demo') {
                e.preventDefault();
                openModal('popup-demo');
            } else if (href === '#hotel-tv') {
                e.preventDefault();
                openModal('popup-hotel-tv');
            } else if (href === '#system-integration') {
                e.preventDefault();
                openModal('popup-integration');
            } else if (href === '#ongoing-support') {
                e.preventDefault();
                openModal('popup-support');
            }
        }

        // Close button click
        if (e.target.closest('.paxtv-modal-close')) {
            const modal = e.target.closest('.paxtv-modal-overlay');
            closeModal(modal);
            return;
        }

        // Click outside dialog to close
        if (e.target.classList.contains('paxtv-modal-overlay')) {
            closeModal(e.target);
            return;
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.paxtv-modal-overlay.active');
            if (activeModal) {
                closeModal(activeModal);
            }
        }
    });

    // 2. Mobile Nav Toggle
    const menuToggle = document.querySelector('.elementor-menu-toggle');
    const dropdownNav = document.querySelector('.elementor-nav-menu--dropdown');
    if (menuToggle && dropdownNav) {
        menuToggle.addEventListener('click', function () {
            dropdownNav.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            }
        });
    }

    // 3. Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href').substring(1);
            const targetEl = document.getElementById(targetId);
            if (targetEl) {
                e.preventDefault();
                targetEl.scrollIntoView({ behavior: 'smooth' });
                if (dropdownNav && dropdownNav.classList.contains('active')) {
                    dropdownNav.classList.remove('active');
                }
            }
        });
    });
});

