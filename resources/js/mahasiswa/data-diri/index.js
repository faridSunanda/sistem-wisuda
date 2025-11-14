(function () {
    const MAX_FILE_SIZE = 2 * 1024 * 1024;
    const fotoInput = document.getElementById("foto-profil");
    const previewFoto = document.getElementById("preview-foto");
    const placeholderIcon = document.getElementById("placeholder-icon");

    function handleFotoPreview(event) {
        const file = event.target.files[0];
        if (!file) return;

        if (file.size > MAX_FILE_SIZE) {
            if (typeof Toast !== "undefined") {
                Toast.fire({
                    icon: "error",
                    title: "Ukuran file maksimal 2MB",
                });
            } else {
                alert("Ukuran file maksimal 2MB");
            }
            event.target.value = "";
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            previewFoto.src = e.target.result;
            previewFoto.classList.remove("hidden");
            placeholderIcon.classList.add("hidden");
        };
        reader.readAsDataURL(file);
    }

    const dosenSelect = document.getElementById("dosen-select");
    const dosenList = document.getElementById("dosen-list");
    const tambahDosenBtn = document.getElementById("tambah-dosen");

    function addDosen() {
        const selectedOption = dosenSelect.options[dosenSelect.selectedIndex];

        if (!selectedOption.value) {
            if (typeof Toast !== "undefined") {
                Toast.fire({
                    icon: "warning",
                    title: "Silakan pilih dosen terlebih dahulu.",
                });
            } else {
                alert("Silakan pilih dosen terlebih dahulu.");
            }
            return;
        }

        const dosenNama = selectedOption.text;

        const existingDosen = Array.from(
            dosenList.querySelectorAll('input[name="dosen_pembimbing[]"]')
        ).some((input) => input.value === dosenNama);

        if (existingDosen) {
            if (typeof Toast !== "undefined") {
                Toast.fire({
                    icon: "error",
                    title: "Dosen pembimbing sudah ditambahkan.",
                });
            } else {
                alert("Dosen pembimbing sudah ditambahkan.");
            }
            return;
        }

        // Buat item baru
        const dosenItem = document.createElement("div");
        dosenItem.className =
            "flex items-center justify-between p-3 bg-gray-50 rounded-lg";
        dosenItem.innerHTML = `
                <span class="text-sm text-gray-700">${dosenNama}</span>
                {{-- Input hidden ini mengirim NAMA, sesuai dengan migrasi Anda --}}
                <input type="hidden" name="dosen_pembimbing[]" value="${dosenNama}">
                <button type="button" class="text-red-600 hover:text-red-700 text-sm" onclick="this.parentElement.remove()">
                    Hapus
                </button>
            `;
        dosenList.appendChild(dosenItem);
        dosenSelect.value = "";
    }

    function initEventListeners() {
        if (fotoInput) {
            fotoInput.addEventListener("change", handleFotoPreview);
        }
        if (tambahDosenBtn) {
            tambahDosenBtn.addEventListener("click", addDosen);
        }
    }

    document.addEventListener("DOMContentLoaded", initEventListeners);
})();
