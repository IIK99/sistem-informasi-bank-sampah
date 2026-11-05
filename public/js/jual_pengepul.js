document.addEventListener("DOMContentLoaded", function () {
    const selectPengepul = document.getElementById("pengepul_id");
    if (!selectPengepul) return;

    let listKatalog = [];
    let cart = {};

    const inputSearch = document.getElementById("inputSearch");
    const gridKatalog = document.getElementById("gridKatalog");
    const emptyKatalog = document.getElementById("emptyKatalog");
    const emptyKatalogText = document.getElementById("emptyKatalogText");
    const loadingKatalog = document.getElementById("loadingKatalog");
    const tbodyKeranjang = document.getElementById("tbodyKeranjang");
    const inputOperasional = document.getElementById("inputOperasional");
    const labelSubtotal = document.getElementById("labelSubtotal");
    const labelOperasional = document.getElementById("labelOperasional");
    const labelKasBersih = document.getElementById("labelKasBersih");
    const btnSubmit = document.getElementById("btnSubmitKasir");
    const badgeItemCount = document.getElementById("badgeItemCount");

    // 1. Fetch katalog sampah sesuai pengepul
    selectPengepul.addEventListener("change", function () {
        const pengepulId = this.value;
        cart = {};
        renderKeranjang();
        if (gridKatalog) gridKatalog.innerHTML = "";

        if (!pengepulId) {
            if (emptyKatalogText) {
                emptyKatalogText.innerText =
                    "Pilih mitra pengepul di atas untuk memuat katalog sampah yang diterima.";
            }
            if (emptyKatalog) emptyKatalog.classList.remove("d-none");
            if (inputSearch) inputSearch.disabled = true;
            return;
        }

        if (emptyKatalog) emptyKatalog.classList.add("d-none");
        if (loadingKatalog) loadingKatalog.classList.remove("d-none");
        if (inputSearch) inputSearch.disabled = true;

        fetch(`/jual-pengepul/get-sampah/${pengepulId}`)
            .then((res) => res.json())
            .then((data) => {
                if (loadingKatalog) loadingKatalog.classList.add("d-none");
                listKatalog = data;

                if (data.length === 0) {
                    if (emptyKatalogText) {
                        emptyKatalogText.innerHTML =
                            '<span class="text-danger fw-semibold">Pengepul ini belum memiliki daftar harga. Atur tarif di Master Pengepul terlebih dahulu.</span>';
                    }
                    if (emptyKatalog) emptyKatalog.classList.remove("d-none");
                    return;
                }

                if (inputSearch) inputSearch.disabled = false;
                renderKatalog(listKatalog);
            })
            .catch(() => {
                if (loadingKatalog) loadingKatalog.classList.add("d-none");
                if (emptyKatalogText) {
                    emptyKatalogText.innerHTML =
                        '<span class="text-danger">Gagal memuat katalog sampah.</span>';
                }
                if (emptyKatalog) emptyKatalog.classList.remove("d-none");
            });
    });

    // 2. Render kartu katalog di sisi kiri
    function renderKatalog(data) {
        if (!gridKatalog) return;
        gridKatalog.innerHTML = "";
        data.forEach((item) => {
            const col = document.createElement("div");
            col.className = "col-sm-6 col-md-4";
            col.innerHTML = `
                    <div class="card h-100 border rounded-3 p-2 shadow-none hover-card" onclick="tambahKeKeranjang(${item.jenis_sampah_id})" style="cursor: pointer; transition: all 0.2s;">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="badge bg-secondary-subtle text-secondary small" style="font-size: 10px;">${item.kategori}</span>
                            <span class="badge bg-light text-muted border" style="font-size: 10px;">/${item.simbol}</span>
                        </div>
                        <span class="fw-bold small text-dark d-block text-truncate mb-1">${item.nama_jenis}</span>
                        <span class="fw-bold text-primary small">Rp ${Number(item.harga).toLocaleString("id-ID")}</span>
                    </div>
                `;
            gridKatalog.appendChild(col);
        });
    }

    // 3. Filter pencarian katalog
    if (inputSearch) {
        inputSearch.addEventListener("input", function () {
            const query = this.value.toLowerCase();
            const filtered = listKatalog.filter((item) =>
                item.nama_jenis.toLowerCase().includes(query),
            );
            renderKatalog(filtered);
        });
    }

    // 4. Tambah item ke keranjang
    window.tambahKeKeranjang = function (jenisId) {
        const sampah = listKatalog.find((s) => s.jenis_sampah_id === jenisId);
        if (!sampah) return;

        if (cart[jenisId]) {
            cart[jenisId].jumlah = parseFloat(
                (cart[jenisId].jumlah + 1).toFixed(2),
            );
        } else {
            cart[jenisId] = {
                id: sampah.jenis_sampah_id,
                nama: sampah.nama_jenis,
                harga: sampah.harga,
                simbol: sampah.simbol,
                jumlah: 1,
            };
        }
        renderKeranjang();
    };

    // 5. Render tabel keranjang
    function renderKeranjang() {
        if (!tbodyKeranjang) return;
        tbodyKeranjang.innerHTML = "";
        const items = Object.values(cart);

        if (items.length === 0) {
            tbodyKeranjang.innerHTML = `<tr id="rowKeranjangKosong"><td colspan="4" class="text-center py-4 text-muted small">Keranjang masih kosong. Klik kartu sampah di sisi kiri.</td></tr>`;
            kalkulasiFinansial();
            return;
        }

        items.forEach((item, index) => {
            const subtotal = Math.round(item.jumlah * item.harga);
            const tr = document.createElement("tr");
            tr.innerHTML = `
                    <td>
                        <span class="fw-semibold small d-block">${item.nama}</span>
                        <span class="text-muted" style="font-size: 11px;">@ Rp ${Number(item.harga).toLocaleString("id-ID")}</span>
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

        kalkulasiFinansial();
    }

    // 6. Update kuantitas/berat per baris secara live
    window.updateJumlah = function (id, val) {
        if (cart[id]) {
            const berat = parseFloat(val) || 0;
            cart[id].jumlah = berat;

            const subtotalElem = document.getElementById(`subtotal_item_${id}`);
            if (subtotalElem) {
                const subtotal = Math.round(berat * cart[id].harga);
                subtotalElem.innerText = "Rp " + subtotal.toLocaleString("id-ID");
            }

            kalkulasiFinansial();
        }
    };

    window.hapusItemKeranjang = function (id) {
        delete cart[id];
        renderKeranjang();
    };

    // 7. Hitung akumulasi grand total & kas bersih
    function kalkulasiFinansial() {
        let totalKotor = 0;
        let count = 0;

        Object.values(cart).forEach((item) => {
            totalKotor += item.jumlah * item.harga;
            count++;
        });

        const operasional = inputOperasional ? (parseFloat(inputOperasional.value) || 0) : 0;
        const kasBersih = Math.max(0, totalKotor - operasional);

        if (labelSubtotal) labelSubtotal.innerText = "Rp " + Math.round(totalKotor).toLocaleString("id-ID");
        if (labelOperasional) labelOperasional.innerText = "- Rp " + Math.round(operasional).toLocaleString("id-ID");
        if (labelKasBersih) labelKasBersih.innerText = "Rp " + Math.round(kasBersih).toLocaleString("id-ID");
        if (badgeItemCount) badgeItemCount.innerText = `${count} Jenis Sampah`;

        if (btnSubmit) btnSubmit.disabled = count === 0;
    }

    if (inputOperasional) {
        inputOperasional.addEventListener("input", kalkulasiFinansial);
    }

    // 8. Auto-load katalog jika halaman di-refresh dan pengepul sudah terpilih
    if (selectPengepul && selectPengepul.value) {
        selectPengepul.dispatchEvent(new Event("change"));
    }
});
