export function initActions(config, table) {
    const { previewPptUrl, downloadPptUrl, pindahkanKeUrl, detailUrl, editUrl, deleteUrl } = config;

    window.lihatData = function(id) {
        if (detailUrl) {
            window.location.href = detailUrl.replace(':id', id);
        } else {
            window.location.href = `/admin/download-ppt/${id}`;
        }
    };

    window.editData = function(id) {
        if (editUrl) {
            window.location.href = editUrl.replace(':id', id);
        } else {
            window.location.href = `/admin/download-ppt/${id}/edit`;
        }
    };

    window.hapusData = function (id, buttonEl) {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Anda tidak akan dapat mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = deleteUrl ? deleteUrl.replace(':id', id) : `/admin/download-ppt/${id}`;
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const button = buttonEl;
                if (!button) {
                    console.warn('⚠️ Button element tidak ditemukan.');
                    return;
                }

                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;

                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token || '',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message || 'Data berhasil dihapus.',
                                confirmButtonColor: '#435ebe'
                            }).then(() => {
                                if (typeof table !== 'undefined' && table) {
                                    table.draw();
                                } else {
                                    location.reload();
                                }
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan saat menghapus data.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Terjadi kesalahan.',
                            confirmButtonColor: '#435ebe'
                        });
                    })
                    .finally(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    });
            }
        });
    };

    // Select All Checkbox
    $('#selectAllCheckbox, #selectAllHeader').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.wisudawan-checkbox').prop('checked', isChecked);
        $('#selectAllCheckbox, #selectAllHeader').prop('checked', isChecked);
    });

    // Individual checkbox change
    $(document).on('change', '.wisudawan-checkbox', function() {
        const totalCheckboxes = $('.wisudawan-checkbox').length;
        const checkedCheckboxes = $('.wisudawan-checkbox:checked').length;
        
        $('#selectAllCheckbox, #selectAllHeader').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // Preview PPT Button
    $('#previewPptBtn').on('click', function() {
        const selectedIds = getSelectedIds();
        
        if (selectedIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pilih minimal satu wisudawan untuk preview PPT.',
                confirmButtonColor: '#435ebe'
            });
            return;
        }

        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

        $.ajax({
            url: previewPptUrl,
            method: 'POST',
            data: {
                ids: selectedIds,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: 'Fitur preview PPT akan segera tersedia.',
                    confirmButtonColor: '#435ebe'
                });
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat preview PPT.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                    confirmButtonColor: '#435ebe'
                });
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // Download PPT Button
    $('#downloadPptBtn').on('click', function() {
        const selectedIds = getSelectedIds();
        
        if (selectedIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pilih minimal satu wisudawan untuk download PPT.',
                confirmButtonColor: '#435ebe'
            });
            return;
        }

        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>');

        $.ajax({
            url: downloadPptUrl,
            method: 'POST',
            data: {
                ids: selectedIds,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: 'Fitur download PPT akan segera tersedia.',
                    confirmButtonColor: '#435ebe'
                });
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat download PPT.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                    confirmButtonColor: '#435ebe'
                });
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // Pindahkan Ke Button
    $('#pindahkanBtn').on('click', function() {
        const selectedIds = getSelectedIds();
        const targetGroupId = $('#targetGroupId').val();
        const targetSesiId = $('#targetSesiId').val();

        if (selectedIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pilih minimal satu wisudawan untuk dipindahkan.',
                confirmButtonColor: '#435ebe'
            });
            return;
        }

        if (!targetGroupId || !targetSesiId) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pilih group dan sesi tujuan.',
                confirmButtonColor: '#435ebe'
            });
            return;
        }

        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: `Memindahkan ${selectedIds.length} wisudawan ke group dan sesi yang dipilih?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#435ebe',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, pindahkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const $btn = $('#pindahkanBtn');
                const originalHtml = $btn.html();
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

                $.ajax({
                    url: pindahkanKeUrl,
                    method: 'POST',
                    data: {
                        ids: selectedIds,
                        target_group_id: targetGroupId,
                        target_sesi_id: targetSesiId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data wisudawan berhasil dipindahkan.',
                                confirmButtonColor: '#435ebe'
                            }).then(() => {
                                if (typeof table !== 'undefined' && table) {
                                    table.draw();
                                } else {
                                    location.reload();
                                }
                                $('.wisudawan-checkbox').prop('checked', false);
                                $('#selectAllCheckbox, #selectAllHeader').prop('checked', false);
                                $('#targetGroupId, #targetSesiId').val('');
                            });
                        } else {
                            throw new Error(response.message || 'Terjadi kesalahan saat memindahkan data.');
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat memindahkan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message,
                            confirmButtonColor: '#435ebe'
                        });
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            }
        });
    });

    function getSelectedIds() {
        const selectedIds = [];
        $('.wisudawan-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });
        return selectedIds;
    }
}

