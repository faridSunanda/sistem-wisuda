const SWAL_CONFIG = {
    confirmButtonColor: "#435ebe",
    cancelButtonColor: "#6c757d",
};

export function initActions(config) {
    const { detailUrl, verifyUrl } = config;

    window.lihatData = function (id) {
        const url = detailUrl
            ? detailUrl.replace(":id", id)
            : `/akademik/data-wisudawan/${id}/show`;
        window.location.href = url;
    };

    window.verifikasiData = function (id, currentStatus) {
        const actionText = currentStatus
            ? "membatalkan verifikasi"
            : "memverifikasi";
        const confirmBtnText = currentStatus
            ? "Ya, Batalkan!"
            : "Ya, Verifikasi!";
        const confirmColor = currentStatus ? "#f59e0b" : "#10b981";

        Swal.fire({
            title: "Konfirmasi Status",
            text: `Apakah Anda yakin ingin ${actionText} data wisudawan ini?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: SWAL_CONFIG.cancelButtonColor,
            confirmButtonText: confirmBtnText,
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                processVerification(id, verifyUrl);
            }
        });
    };
}

function processVerification(id, verifyUrl) {
    const url = verifyUrl
        ? verifyUrl.replace(":id", id)
        : `/akademik/data-wisudawan/${id}/verify`;
    const token = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    Swal.fire({
        title: "Memproses...",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token,
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => {
                    if (typeof window.dataWisudawanTable !== "undefined") {
                        window.dataWisudawanTable.draw(false);
                    } else {
                        location.reload();
                    }
                });
            } else {
                throw new Error(data.message);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: error.message || "Terjadi kesalahan sistem.",
            });
        });
}
