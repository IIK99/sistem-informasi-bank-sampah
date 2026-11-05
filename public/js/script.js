document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn =
        document.getElementById("sidebarToggle") ||
        document.getElementById("sidebar-toggle");
    const closeBtn = document.getElementById("sidebarCloseBtn");
    const sidebar =
        document.querySelector(".sidebar-wrapper") ||
        document.getElementById("sidebar");
    let backdrop = document.getElementById("sidebarBackdrop");

    // 1. Buat elemen backdrop otomatis jika belum ada di HTML
    if (!backdrop) {
        backdrop = document.createElement("div");
        backdrop.id = "sidebarBackdrop";
        backdrop.className = "sidebar-backdrop d-none";
        document.body.appendChild(backdrop);
    }

    // Fungsi buka/tutup sidebar mobile
    function openSidebar() {
        if (sidebar) sidebar.classList.add("show");
        if (backdrop) backdrop.classList.remove("d-none");
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove("show");
        if (backdrop) backdrop.classList.add("d-none");
    }

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (sidebar.classList.contains("show")) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        backdrop.addEventListener("click", closeSidebar);

        if (closeBtn) {
            closeBtn.addEventListener("click", closeSidebar);
        }

        // Tutup sidebar otomatis di mobile jika link submenu diklik
        sidebar.querySelectorAll(".sidebar-submenu-link, .sidebar-link:not([data-bs-toggle='collapse'])").forEach(function (link) {
            link.addEventListener("click", function () {
                if (window.innerWidth < 992) {
                    closeSidebar();
                }
            });
        });
    }

    // 2. Bersihkan sessionStorage lama agar tidak memaksa menu terbuka secara tiba-tiba
    try {
        Object.keys(sessionStorage).forEach(function (key) {
            if (key.startsWith("menu_open_")) {
                sessionStorage.removeItem(key);
            }
        });
    } catch (err) {}

    // 3. SINKRONISASI ACCORDION MENU (HANYA SATU SUBMENU TERBUKA, TIDAK BERKEDIP)
    const accordionEl = document.getElementById("sidebarMenuAccordion");
    if (accordionEl) {
        accordionEl.addEventListener("show.bs.collapse", function (e) {
            // Ketika satu submenu dibuka, Bootstrap otomatis menutup submenu lainnya
            const trigger = document.querySelector(`[href="#${e.target.id}"], [data-bs-target="#${e.target.id}"]`);
            if (trigger) {
                trigger.setAttribute("aria-expanded", "true");
                trigger.classList.add("active");
            }
        });

        accordionEl.addEventListener("hide.bs.collapse", function (e) {
            const trigger = document.querySelector(`[href="#${e.target.id}"], [data-bs-target="#${e.target.id}"]`);
            if (trigger) {
                trigger.setAttribute("aria-expanded", "false");
                // Hapus class active jika halaman saat ini bukan salah satu submenu di dalamnya
                const activeChild = e.target.querySelector(".sidebar-submenu-link.active");
                if (!activeChild) {
                    trigger.classList.remove("active");
                }
            }
        });
    }
});

// Icon picker handler
document.addEventListener("click", function (e) {
    const item = e.target.closest(".btn-select-icon");
    if (!item) return;

    const group = item.closest(".icon-picker-group");
    if (!group) return;

    const icon = item.getAttribute("data-icon");
    const label = item.getAttribute("data-label");

    // Perbarui nilai input hidden
    group.querySelector(".icon-input").value = icon;

    // Perbarui tampilan preview di tombol dropdown
    group.querySelector(".icon-preview").innerHTML = `
        <i class="bi ${icon} text-success fs-5"></i>
        <span class="small text-dark">${label}</span>
    `;
});

// ==========================================
// 4. LIVE SEARCH DENGAN DEBOUNCE & DROPDOWN SUGGESTION
// ==========================================
document.addEventListener("DOMContentLoaded", function () {
    const liveSearchForms = document.querySelectorAll(".form-live-search");

    liveSearchForms.forEach(function (form) {
        const input = form.querySelector(".live-search-input");
        const suggestionsBox = form.querySelector(".search-suggestions-box");
        let debounceTimer = null;
        let currentAbortController = null;

        if (!input || !suggestionsBox) return;

        function escapeHtml(text) {
            if (!text) return "";
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return String(text).replace(/[&<>"']/g, function (m) { return map[m]; });
        }

        function highlightMatch(text, query) {
            if (!text) return "";
            if (!query) return escapeHtml(text);
            const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, "gi");
            return escapeHtml(text).replace(regex, '<mark class="bg-warning-subtle text-dark px-1 rounded">$1</mark>');
        }

        function filterTableRows(query) {
            const table = document.querySelector("table tbody");
            if (!table) return;
            const rows = table.querySelectorAll("tr");
            const q = query.toLowerCase().trim();
            rows.forEach(function (row) {
                if (row.cells.length <= 1) return;
                const text = row.innerText.toLowerCase();
                if (!q || text.includes(q)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        function hideSuggestions() {
            suggestionsBox.classList.add("d-none");
            suggestionsBox.innerHTML = "";
        }

        function fetchSuggestions(query) {
            if (currentAbortController) {
                currentAbortController.abort();
            }
            currentAbortController = new AbortController();

            suggestionsBox.classList.remove("d-none");
            suggestionsBox.innerHTML = `
                <div class="p-3 text-center text-muted small">
                    <div class="spinner-border spinner-border-sm text-success me-2" role="status"></div>
                    <span>Mencari saran...</span>
                </div>
            `;

            const url = new URL(form.action, window.location.origin);
            url.searchParams.set("search", query);
            url.searchParams.set("ajax", "1");

            fetch(url.toString(), {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                },
                signal: currentAbortController.signal
            })
            .then(function (response) {
                if (!response.ok) throw new Error("Gagal memuat saran");
                return response.json();
            })
            .then(function (data) {
                if (!Array.isArray(data) || data.length === 0) {
                    suggestionsBox.innerHTML = `
                        <div class="p-3 text-center text-muted small">
                            <i class="bi bi-search d-block mb-1 text-secondary fs-5"></i>
                            Tidak ada saran untuk "<strong>${escapeHtml(query)}</strong>"
                        </div>
                    `;
                    return;
                }

                let html = '<div class="list-group list-group-flush rounded-4 overflow-hidden">';
                data.forEach(function (item) {
                    const initial = (item.name || "?").charAt(0).toUpperCase();
                    const username = item.username ? `@${item.username}` : "";
                    const email = item.email || "";
                    const saldoText = item.saldo !== undefined ? ` • Saldo: Rp ${Number(item.saldo).toLocaleString('id-ID')}` : "";

                    html += `
                        <a href="#" class="list-group-item list-group-item-action p-2.5 px-3 d-flex align-items-center gap-2 suggestion-item"
                           data-name="${escapeHtml(item.name)}" data-username="${escapeHtml(item.username || '')}" style="cursor: pointer;">
                            <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                 style="width: 32px; height: 32px; font-size: 13px;">
                                ${initial}
                            </div>
                            <div class="flex-grow-1 text-truncate">
                                <div class="fw-semibold text-dark text-truncate small">
                                    ${highlightMatch(item.name, query)}
                                </div>
                                <div class="text-muted text-truncate" style="font-size: 11px;">
                                    <code class="text-success">${highlightMatch(username, query)}</code>
                                    ${email ? ' • ' + highlightMatch(email, query) : ''}
                                    ${saldoText ? '<span class="text-success fw-semibold">' + saldoText + '</span>' : ''}
                                </div>
                            </div>
                            <i class="bi bi-arrow-up-left text-muted small flex-shrink-0"></i>
                        </a>
                    `;
                });

                html += `
                    <button type="submit" class="list-group-item list-group-item-action text-center py-2 bg-light border-top text-success fw-semibold small">
                        <i class="bi bi-search me-1"></i> Tampilkan semua hasil untuk "<strong>${escapeHtml(query)}</strong>"
                    </button>
                `;
                html += '</div>';

                suggestionsBox.innerHTML = html;
            })
            .catch(function (err) {
                if (err.name === "AbortError") return;
                suggestionsBox.innerHTML = `
                    <div class="p-3 text-center text-muted small">
                        Gagal memuat saran pencarian.
                    </div>
                `;
            });
        }

        input.addEventListener("input", function () {
            const query = input.value.trim();

            filterTableRows(query);

            clearTimeout(debounceTimer);
            if (query.length < 1) {
                hideSuggestions();
                return;
            }

            debounceTimer = setTimeout(function () {
                fetchSuggestions(query);
            }, 300);
        });

        input.addEventListener("focus", function () {
            const query = input.value.trim();
            if (query.length >= 1 && suggestionsBox.classList.contains("d-none")) {
                fetchSuggestions(query);
            }
        });

        suggestionsBox.addEventListener("click", function (e) {
            const item = e.target.closest(".suggestion-item");
            if (!item) return;
            e.preventDefault();
            const selectedVal = item.getAttribute("data-name") || item.getAttribute("data-username");
            input.value = selectedVal;
            hideSuggestions();
            form.submit();
        });

        input.addEventListener("keydown", function (e) {
            if (suggestionsBox.classList.contains("d-none")) return;

            const items = suggestionsBox.querySelectorAll(".suggestion-item");
            if (!items.length) return;

            const activeItem = suggestionsBox.querySelector(".suggestion-item.active");
            let currentIndex = Array.from(items).indexOf(activeItem);

            if (e.key === "ArrowDown") {
                e.preventDefault();
                if (currentIndex < items.length - 1) {
                    if (activeItem) activeItem.classList.remove("active");
                    items[currentIndex + 1].classList.add("active");
                    items[currentIndex + 1].scrollIntoView({ block: "nearest" });
                } else if (currentIndex === -1 && items.length > 0) {
                    items[0].classList.add("active");
                    items[0].scrollIntoView({ block: "nearest" });
                }
            } else if (e.key === "ArrowUp") {
                e.preventDefault();
                if (currentIndex > 0) {
                    if (activeItem) activeItem.classList.remove("active");
                    items[currentIndex - 1].classList.add("active");
                    items[currentIndex - 1].scrollIntoView({ block: "nearest" });
                }
            } else if (e.key === "Enter" && activeItem) {
                e.preventDefault();
                activeItem.click();
            } else if (e.key === "Escape") {
                hideSuggestions();
            }
        });

        document.addEventListener("click", function (e) {
            if (!form.contains(e.target)) {
                hideSuggestions();
            }
        });
    });
});

