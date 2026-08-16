'use strict';

(function () {
    const root = document.documentElement;
    const body = document.body;

    function qs(selector, parent = document) {
        return parent.querySelector(selector);
    }

    function qsa(selector, parent = document) {
        return Array.from(parent.querySelectorAll(selector));
    }

    function closeSidebar() {
        root.classList.remove('drawer-open');
        const sidebar = qs('[data-sidebar]');
        if (sidebar) {
            sidebar.setAttribute('aria-hidden', 'true');
        }
    }

    function openSidebar() {
        root.classList.add('drawer-open');
        const sidebar = qs('[data-sidebar]');
        if (sidebar) {
            sidebar.setAttribute('aria-hidden', 'false');
        }
    }

    function toggleDropdown(button, dropdown) {
        const isOpen = !dropdown.hidden;
        dropdown.hidden = isOpen;
        button.setAttribute('aria-expanded', String(!isOpen));
    }

    function setLoadingState(form) {
        const submit = form.querySelector('button[type="submit"]');
        if (!submit || submit.dataset.loadingBound === 'true') {
            return;
        }
        submit.dataset.loadingBound = 'true';
        form.addEventListener('submit', function () {
            const text = submit.textContent.trim();
            submit.dataset.originalText = text;
            submit.disabled = true;
            submit.classList.add('button-loading');
            if (submit.closest('form')?.action.includes('/login')) {
                submit.textContent = 'Signing In…';
            } else if (submit.closest('form')?.action.includes('/signup')) {
                submit.textContent = 'Creating Account…';
            }
        });
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                submit.disabled = false;
                submit.classList.remove('button-loading');
                submit.textContent = submit.dataset.originalText || text;
            }
        });
    }

    qsa('[data-toast]').forEach(function (toast) {
        const close = qs('[data-toast-close]', toast);
        let removed = false;
        const remove = function () {
            if (removed) return;
            removed = true;
            toast.classList.add('toast-exit');
            setTimeout(function () {
                toast.remove();
            }, 160);
        };
        if (close) {
            close.addEventListener('click', remove);
        }
        setTimeout(remove, 5000);
    });

    qsa('[data-toggle-password]').forEach(function (button) {
        button.addEventListener('click', function () {
            const targetId = button.getAttribute('data-toggle-password');
            const input = targetId ? document.getElementById(targetId) : null;
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(isPassword));
            button.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            const eye = button.querySelector('[data-icon="eye"]');
            const eyeOff = button.querySelector('[data-icon="eye-off"]');
            if (eye && eyeOff) {
                eye.classList.toggle('hidden', isPassword);
                eyeOff.classList.toggle('hidden', !isPassword);
            }
        });
    });

    function syncConditionalGroup(group) {
        const target = group.getAttribute('data-target');
        const value = group.getAttribute('data-value');
        const current = group.querySelector('input[type="radio"]:checked');
        const controls = target ? document.querySelectorAll('[data-conditional="' + target + '"]') : [];
        const isShown = current && current.value === value;
        const radios = group.querySelectorAll('input[type="radio"]');

        controls.forEach(function (control) {
            control.hidden = !isShown;
            control.setAttribute('aria-hidden', String(!isShown));
            const field = control.querySelector('input, select, textarea');
            if (field) {
                field.disabled = !isShown;
                if (!isShown) {
                    field.value = '';
                    field.removeAttribute('required');
                } else if (control.getAttribute('data-required') === 'true') {
                    field.setAttribute('required', 'required');
                }
            }
        });

        radios.forEach(function (radio) {
            const expands = radio.value === value ? isShown : !isShown;
            const controlsId = radio.getAttribute('aria-controls');
            if (controlsId) {
                radio.setAttribute('aria-expanded', String(expands));
            }
        });
    }

    qsa('[data-conditional-group]').forEach(function (group) {
        group.addEventListener('change', function () {
            syncConditionalGroup(group);
        });
        syncConditionalGroup(group);
    });

    qsa('[data-sidebar-open]').forEach(function (button) {
        button.addEventListener('click', openSidebar);
    });

    qsa('[data-drawer-backdrop], [data-sidebar-close]').forEach(function (button) {
        button.addEventListener('click', closeSidebar);
    });

    qsa('[data-user-dropdown]').forEach(function (button) {
        const dropdown = button.nextElementSibling;
        if (!dropdown) return;
        button.addEventListener('click', function () {
            toggleDropdown(button, dropdown);
        });
    });

    document.addEventListener('click', function (event) {
        qsa('[data-user-dropdown]').forEach(function (button) {
            const dropdown = button.nextElementSibling;
            if (!dropdown) return;
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.hidden = true;
                button.setAttribute('aria-expanded', 'false');
            }
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeSidebar();
            qsa('[data-user-dropdown]').forEach(function (button) {
                const dropdown = button.nextElementSibling;
                if (dropdown) {
                    dropdown.hidden = true;
                    button.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });

    qsa('form').forEach(setLoadingState);

    qsa('[data-announcement-close]').forEach(function (button) {
        button.addEventListener('click', function () {
            const bar = button.closest('.announcement-bar');
            if (bar) bar.remove();
        });
    });

    body.classList.add('js-ready');
})();
