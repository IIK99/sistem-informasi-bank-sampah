document.addEventListener("DOMContentLoaded", function () {
    const tbodyKeranjang = document.getElementById("tbodyKeranjang");
    const labelTotalNilai = document.getElementById("labelTotalNilai");
    if (!tbodyKeranjang || !labelTotalNilai) return;

    let cart = {};

    const inputSearch = document.getElementById("inputSearch");
    const badgeItemCount = document.getElementById("badgeItemCount");
    const btnSubmit = document.getElementById("btnSubmitSetoran");

    // 1. Filter Pencarian Sampah
    if (inputSearch) {
        inputSearch.addEventListener("input", function () {
            const query = this.value.toLowerCase();
            document.querySelectorAll(".card-sampah-item").forEach((el) => {
                const nama = el.getAttribute("data-nama") || "";
                el.style.display = nama.includes(query) ? "" : "none";
            });
        });
    }

    // 2. Tambah Sampah ke Keranjang
    window.tambahKeKeranjang = function (id, nama, harga, simbol) {
        if (cart[id]) {
            cart[id].jumlah = parseFloat((cart[id].jumlah + 1).toFixed(2));
        } else {
            cart[id] = {
                id: id,
                nama: nama,
                harga: harga,
                simbol: simbol,
                jumlah: 1,
            };
        }
        renderKeranjang();
    };

    // 3. Render Baris Keranjang
    function renderKeranjang() {
        tbodyKeranjang.innerHTML = "";
        const items = Object.values(cart);

        if (items.length === 0) {
            tbodyKeranjang.innerHTML = `<tr id="rowKeranjangKosong"><td colspan="4" class="text-center py-4 text-muted small">Belum ada sampah ditimbang. Klik kartu sampah di sisi kiri.</td></tr>`;
            kalkulasiTotal();
            return;
        }

        items.forEach((item, index) => {
            const subtotal = Math.round(item.jumlah * item.harga);
            const tr = document.createElement("tr");
            tr.innerHTML = `
                    <td>
                        <span class="fw-semibold small d-block">${item.nama}</span>
                        <span class="text-muted" style="font-size: 11px;">@ Rp ${Number(item.harga).toLocaleString("id-ID")} / ${item.simbol}</span>
                        <input type="hidden" name="items[${index}][jenis_sampah_id]" value="${item.id}">
                        <input type="hidden" name="items[${index}][harga_satuan]" value="${item.harga}">
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" step="any" min="0" class="form-control p-1 text-center" 
                                   name="items[${index}][jumlah]" value="${item.jumlah}" 
                                   oninput="updateJumlah(${item.id}, this.value)">
                        </div>
                    </td>
                    <td class="text-end fw-semibold small" id="subtotal_item_${item.id}">
                        Rp ${subtotal.toLocaleString("id-ID")}
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" onclick="hapusItemKeranjang(${item.id})">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </td>
                `;
            tbodyKeranjang.appendChild(tr);
        });

        kalkulasiTotal();
    }

    // 4. Update Timbangan Real-time
    window.updateJumlah = function (id, val) {
        if (cart[id]) {
            const berat = parseFloat(val) || 0;
            cart[id].jumlah = berat;

            const subtotalElem = document.getElementById(`subtotal_item_${id}`);
            if (subtotalElem) {
                const subtotal = Math.round(berat * cart[id].harga);
                subtotalElem.innerText =
                    "Rp " + subtotal.toLocaleString("id-ID");
            }

            kalkulasiTotal();
        }
    };

    window.hapusItemKeranjang = function (id) {
        delete cart[id];
        renderKeranjang();
    };

    // 5. Kalkulasi Total Saldo
    function kalkulasiTotal() {
        let total = 0;
        let count = 0;

        Object.values(cart).forEach((item) => {
            total += item.jumlah * item.harga;
            count++;
        });

        labelTotalNilai.innerText =
            "Rp " + Math.round(total).toLocaleString("id-ID");
        if (badgeItemCount) badgeItemCount.innerText = `${count} Jenis Sampah`;
        if (btnSubmit) btnSubmit.disabled = count === 0;
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const inputCari = document.getElementById("inputCariNasabah");
    const items = document.querySelectorAll(".item-nasabah");
    const labelNasabah = document.getElementById("labelNasabah");
    const hiddenInput = document.getElementById("selectedNasabahId");
    const pesanKosong = document.getElementById("nasabahKosong");
    const dropdownEl = document.getElementById("dropdownNasabah");
    const btnDropdown = document.getElementById("btnPilihNasabah");

    // 1. Fitur Live Filter (Ketik langsung menyaring, hapus teks untuk melihat semua)
    inputCari.addEventListener("input", function () {
        const keyword = this.value.toLowerCase().trim();
        let adaData = false;

        items.forEach((item) => {
            const nama = item.getAttribute("data-nama").toLowerCase();
            const saldo = item.getAttribute("data-saldo").toLowerCase();

            if (nama.includes(keyword) || saldo.includes(keyword)) {
                item.style.setProperty("display", "flex", "important");
                adaData = true;
            } else {
                item.style.setProperty("display", "none", "important");
            }
        });

        pesanKosong.style.display = adaData ? "none" : "block";
    });

    // 2. Fitur Klik Pilihan Nasabah
    items.forEach((item) => {
        item.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            const nama = this.getAttribute("data-nama");
            const saldo = this.getAttribute("data-saldo");

            // Set nilai input form
            hiddenInput.value = id;

            // Perbarui tampilan tombol
            labelNasabah.innerHTML = `<span class="fw-bold text-dark">${nama}</span> <span class="badge bg-success-subtle text-success border border-success-subtle ms-2">Saldo: Rp ${saldo}</span>`;

            // Tandai centang hijau aktif
            document
                .querySelectorAll(".cek-icon")
                .forEach((icon) => icon.classList.add("d-none"));
            this.querySelector(".cek-icon").classList.remove("d-none");

            // Tutup dropdown Bootstrap secara otomatis
            const bsDropdown = bootstrap.Dropdown.getInstance(btnDropdown);
            if (bsDropdown) {
                bsDropdown.hide();
            }

            // Memicu trigger event change jika ada validasi lain
            hiddenInput.dispatchEvent(new Event("change"));
        });
    });

    // 3. Auto-fokus ke kolom cari begitu dropdown dibuka
    dropdownEl.addEventListener("shown.bs.dropdown", function () {
        inputCari.focus();
        inputCari.value = "";
        items.forEach((item) =>
            item.style.setProperty("display", "flex", "important"),
        );
        pesanKosong.style.display = "none";
    });
});
