(function () {
    'use strict';

    let currentPage = 1;
    let pageSize = 10;
    let filteredRows = [];

    const modal = document.getElementById('akunModal');
    const form = document.getElementById('akunForm');
    const tableBody = document.querySelector('#akunTable tbody');
    const searchInput = document.getElementById('akunSearch');
    const pageSizeSelect = document.getElementById('akunPageSize');
    const pagination = document.getElementById('akunPagination');
    const info = document.getElementById('akunInfo');
    const noResult = document.getElementById('akunNoResult');

    function getRows() {
        if (!tableBody) return [];
        return Array.from(tableBody.querySelectorAll('tr[data-search]'));
    }

    function filterRows() {
        const keyword = (searchInput?.value || '').trim().toLowerCase();
        const rows = getRows();

        filteredRows = rows.filter(function (row) {
            return (row.dataset.search || '').includes(keyword);
        });

        currentPage = 1;
        renderTable();
    }

    function renderTable() {
        const rows = getRows();
        const total = filteredRows.length;
        const totalPages = Math.max(1, Math.ceil(total / pageSize));

        if (currentPage > totalPages) currentPage = totalPages;

        rows.forEach(function (row) {
            row.style.display = 'none';
        });

        const start = (currentPage - 1) * pageSize;
        const end = Math.min(start + pageSize, total);

        filteredRows.slice(start, end).forEach(function (row, index) {
            row.style.display = '';

            const noCell = row.querySelector('.akun-no');
            if (noCell) noCell.textContent = start + index + 1;
        });

        if (info) {
            if (total === 0) {
                info.textContent = 'Menampilkan 0 dari 0 data';
            } else {
                info.textContent = 'Menampilkan ' + (start + 1) + '–' + end + ' dari ' + total + ' data';
            }
        }

        if (noResult) {
            noResult.classList.toggle('show', total === 0 && rows.length > 0);
        }

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        if (!pagination) return;

        pagination.innerHTML = '';

        const previous = createPageButton('‹', currentPage === 1, function () {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        });
        pagination.appendChild(previous);

        const maxVisible = 5;
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);

        if (endPage - startPage < maxVisible - 1) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }

        for (let page = startPage; page <= endPage; page++) {
            const button = createPageButton(String(page), false, function () {
                currentPage = page;
                renderTable();
            });

            if (page === currentPage) button.classList.add('active');
            pagination.appendChild(button);
        }

        const next = createPageButton('›', currentPage === totalPages, function () {
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        });
        pagination.appendChild(next);
    }

    function createPageButton(label, disabled, handler) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'akun-page-btn';
        button.textContent = label;
        button.disabled = disabled;
        button.addEventListener('click', handler);
        return button;
    }

    window.bukaModalTambah = function () {
        if (!modal || !form) return;

        form.reset();
        document.getElementById('formAction').value = 'add';
        document.getElementById('formId').value = '';
        document.getElementById('akunModalTitle').textContent = 'Tambah Akun';
        document.getElementById('akunModalDescription').textContent = 'Buat akun baru untuk mengakses Dashboard FIKES.';
        document.getElementById('submitAkunBtn').textContent = 'Simpan Akun';
        document.getElementById('passwordHelp').textContent = 'Password wajib diisi untuk akun baru.';
        document.getElementById('passwordRequired').style.display = 'inline';
        document.getElementById('confirmationRequired').style.display = 'inline';
        document.getElementById('formPassword').required = true;
        document.getElementById('formPasswordConfirmation').required = true;
        document.getElementById('formRole').value = 'editor';

        openModal();
    };

    window.bukaModalEdit = function (akun) {
        if (!modal || !form || !akun) return;

        form.reset();
        document.getElementById('formAction').value = 'edit';
        document.getElementById('formId').value = akun.id || '';
        document.getElementById('formNama').value = akun.nama || '';
        document.getElementById('formUsername').value = akun.username || '';
        document.getElementById('formRole').value = akun.role || 'editor';
        document.getElementById('formPassword').value = '';
        document.getElementById('formPasswordConfirmation').value = '';
        document.getElementById('akunModalTitle').textContent = 'Edit Akun';
        document.getElementById('akunModalDescription').textContent = 'Perbarui informasi dan hak akses akun.';
        document.getElementById('submitAkunBtn').textContent = 'Simpan Perubahan';
        document.getElementById('passwordHelp').textContent = 'Kosongkan password jika tidak ingin mengubahnya.';
        document.getElementById('passwordRequired').style.display = 'none';
        document.getElementById('confirmationRequired').style.display = 'none';
        document.getElementById('formPassword').required = false;
        document.getElementById('formPasswordConfirmation').required = false;

        openModal();
    };

    function openModal() {
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        window.setTimeout(function () {
            document.getElementById('formNama')?.focus();
        }, 100);
    }

    window.tutupModal = function () {
        if (!modal) return;
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    };

    window.togglePassword = function (inputId, button) {
        const input = document.getElementById(inputId);
        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = '◌';
        } else {
            input.type = 'password';
            button.textContent = '◉';
        }
    };

    window.hapusAkun = async function (id, nama) {
        if (!window.Swal) return;

        const result = await Swal.fire({
            icon: 'warning',
            title: 'Hapus akun?',
            html: 'Akun <strong>' + escapeHtml(nama) + '</strong> akan dihapus secara permanen.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d64747',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
            focusCancel: true
        });

        if (!result.isConfirmed) return;

        document.getElementById('deleteAkunId').value = id;
        document.getElementById('deleteAkunForm').submit();
    };

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterRows);
    }

    if (pageSizeSelect) {
        pageSizeSelect.addEventListener('change', function () {
            pageSize = Number(this.value) || 10;
            currentPage = 1;
            renderTable();
        });
    }

    if (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                window.tutupModal();
            }
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal?.classList.contains('show')) {
            window.tutupModal();
        }
    });

    if (form) {
        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            const action = document.getElementById('formAction').value;
            const nama = document.getElementById('formNama').value.trim();
            const username = document.getElementById('formUsername').value.trim();
            const role = document.getElementById('formRole').value;
            const password = document.getElementById('formPassword').value;
            const confirmation = document.getElementById('formPasswordConfirmation').value;

            if (!nama || !username) {
                Swal.fire({ icon: 'warning', title: 'Data belum lengkap', text: 'Nama dan username wajib diisi.' });
                return;
            }

            if (!/^[A-Za-z0-9._-]{3,50}$/.test(username)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Username tidak valid',
                    text: 'Username hanya boleh berisi huruf, angka, titik, garis bawah, atau tanda hubung.'
                });
                return;
            }

            if (!['admin', 'editor'].includes(role)) {
                Swal.fire({ icon: 'warning', title: 'Role tidak valid', text: 'Silakan pilih role yang tersedia.' });
                return;
            }

            if (action === 'add' && password.length < 6) {
                Swal.fire({ icon: 'warning', title: 'Password terlalu pendek', text: 'Password minimal 6 karakter.' });
                return;
            }

            if (password !== confirmation) {
                Swal.fire({ icon: 'warning', title: 'Konfirmasi berbeda', text: 'Password dan konfirmasi password harus sama.' });
                return;
            }

            const confirm = await Swal.fire({
                icon: 'question',
                title: action === 'add' ? 'Tambah akun?' : 'Simpan perubahan?',
                text: action === 'add'
                    ? 'Akun baru akan ditambahkan ke daftar pengguna.'
                    : 'Data akun akan diperbarui.',
                showCancelButton: true,
                confirmButtonText: action === 'add' ? 'Ya, Tambahkan' : 'Ya, Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true
            });

            if (!confirm.isConfirmed) return;

            const submitButton = document.getElementById('submitAkunBtn');
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';

            form.submit();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        filteredRows = getRows();
        renderTable();

        const flash = window.AKUN_PAGE || {};

        if (flash.status && flash.message && window.Swal) {
            Swal.fire({
                icon: flash.status === 'success' ? 'success' : 'error',
                title: flash.status === 'success' ? 'Berhasil' : 'Gagal',
                text: flash.message,
                timer: flash.status === 'success' ? 1700 : undefined,
                showConfirmButton: flash.status !== 'success'
            });
        }
    });
})();
