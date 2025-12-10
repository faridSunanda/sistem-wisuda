document.addEventListener("DOMContentLoaded", function () {
    const maxSertifikat = 5;
    // Hitung jumlah sertifikat yang sudah ada
    let sertifikatCount = document.querySelectorAll(
        "#sertifikat-container .sertifikat-entry"
    ).length;

    const addButton = document.getElementById("add-sertifikat");
    const container = document.getElementById("sertifikat-container");

    // Fungsi cek tombol tambah
    function checkAddButtonState() {
        if (sertifikatCount >= maxSertifikat) {
            addButton.style.display = "none";
        } else {
            addButton.style.display = "inline-flex";
        }
    }

    // Fungsi cek tombol hapus
    function toggleDeleteButtons() {
        const allDeleteButtons =
            container.querySelectorAll(".remove-sertifikat");
        if (sertifikatCount > 1) {
            allDeleteButtons.forEach((btn) => btn.classList.remove("hidden"));
        } else {
            allDeleteButtons.forEach((btn) => btn.classList.add("hidden"));
        }
    }

    // Inisialisasi awal
    checkAddButtonState();
    toggleDeleteButtons();

    // Event Listener: Tambah Sertifikat
    addButton.addEventListener("click", function () {
        if (sertifikatCount >= maxSertifikat) {
            alert("Anda hanya dapat menambahkan maksimal 5 sertifikat.");
            return;
        }

        sertifikatCount++;

        const newEntry = document.createElement("div");
        newEntry.classList.add(
            "sertifikat-entry",
            "p-8",
            "bg-white",
            "border",
            "border-gray-200",
            "rounded-lg",
            "relative",
            "transition-shadow",
            "duration-200",
            "hover:shadow-md"
        );

        // [PERUBAHAN]: Menghapus div ikon SVG manual pada input date
        const newEntryHtml = `
            <button type="button" class="remove-sertifikat absolute -top-2 -right-2 w-8 h-8 bg-white border border-red-300 rounded-full flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="nama_sertifikat_${sertifikatCount}" class="block text-sm font-medium text-gray-700 mb-1">Nama Sertifikat</label>
                    <input type="text" name="nama_sertifikat[]" id="nama_sertifikat_${sertifikatCount}" placeholder="Contoh: Web Programming Dasar" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-transparent" required>
                </div>
                <div>
                    <label for="penerbit_${sertifikatCount}" class="block text-sm font-medium text-gray-700 mb-1">Penerbit</label>
                    <input type="text" name="penerbit[]" id="penerbit_${sertifikatCount}" placeholder="Contoh: Universitas Wahid Hasyim" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-transparent" required>
                </div>
                <div>
                    <label for="tanggal_terbit_${sertifikatCount}" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Terbit</label>
                    <input type="date" name="tanggal_terbit[]" id="tanggal_terbit_${sertifikatCount}" placeholder="dd/mm/yyyy" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#435ebe] focus:border-transparent" required>
                </div>
            </div>
        `;

        newEntry.innerHTML = newEntryHtml;
        container.appendChild(newEntry);

        checkAddButtonState();
        toggleDeleteButtons();
    });

    // Event Listener: Hapus Sertifikat
    container.addEventListener("click", function (e) {
        const removeButton = e.target.closest(".remove-sertifikat");

        if (removeButton) {
            if (sertifikatCount > 1) {
                removeButton.closest(".sertifikat-entry").remove();
                sertifikatCount--; // Kurangi counter

                checkAddButtonState();
                toggleDeleteButtons();
            } else {
                alert("Minimal harus ada 1 sertifikat.");
            }
        }
    });
});
