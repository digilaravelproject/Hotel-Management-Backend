<script>
    let html5QrCode = null;
    let qrScriptLoaded = false;

    function openAddDeviceModal(mode = 'quick') {
        document.body.style.overflow = 'hidden';
        document.body.classList.add('overflow-hidden');
        document.getElementById('pairModal').classList.remove('hidden');
        switchModalMode(mode);
    }

    // Keep backwards compatibility for any openPairModal calls
    function openPairModal() {
        openAddDeviceModal('quick');
    }

    function closePairModal() {
        stopScanner();
        document.getElementById('pairModal').classList.add('hidden');
        document.getElementById('quickFormAlert').classList.add('hidden');
        document.getElementById('pairFormAlert').classList.add('hidden');
        document.getElementById('quickAddForm').reset();
        document.getElementById('pairForm').reset();
        document.body.style.overflow = '';
        document.body.classList.remove('overflow-hidden');
    }

    function closePairModalOnBackdrop(event) {
        if (event.target.id === 'pairModal') {
            closePairModal();
        }
    }

    function switchModalMode(mode) {
        const quickBtn = document.getElementById('tabQuickBtn');
        const pairBtn = document.getElementById('tabPairBtn');
        const quickForm = document.getElementById('quickAddForm');
        const pairWrapper = document.getElementById('pairModeWrapper');
        const title = document.getElementById('deviceModalTitle');
        const subtitle = document.getElementById('deviceModalSubtitle');

        if (mode === 'pair') {
            pairBtn.className = 'flex-1 py-2 text-xs font-bold rounded-xl bg-white text-indigo-600 shadow-sm transition-all flex items-center justify-center space-x-1.5';
            quickBtn.className = 'flex-1 py-2 text-xs font-bold rounded-xl text-slate-500 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5';
            quickForm.classList.add('hidden');
            pairWrapper.classList.remove('hidden');
            title.innerText = 'Pair TV Screen';
            subtitle.innerText = 'Connect TV via 8-digit screen code';
            switchPairSubTab('manual');
        } else {
            quickBtn.className = 'flex-1 py-2 text-xs font-bold rounded-xl bg-white text-indigo-600 shadow-sm transition-all flex items-center justify-center space-x-1.5';
            pairBtn.className = 'flex-1 py-2 text-xs font-bold rounded-xl text-slate-500 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5';
            pairWrapper.classList.add('hidden');
            quickForm.classList.remove('hidden');
            title.innerText = 'Add TV Device';
            subtitle.innerText = 'Quick provision room television';
            stopScanner();
            setTimeout(() => {
                document.getElementById('quickRoomNo')?.focus();
            }, 50);
        }
    }

    function switchPairSubTab(tab) {
        const manualBtn = document.getElementById('tabManualBtn');
        const scanBtn = document.getElementById('tabScanBtn');
        const qrBox = document.getElementById('qrScannerBox');
        const codeInputWrapper = document.getElementById('pairCodeInputWrapper');

        if (tab === 'scan') {
            scanBtn.className = 'flex-1 py-1.5 font-bold rounded-lg bg-white text-indigo-600 shadow-2xs transition-all flex items-center justify-center space-x-1.5';
            manualBtn.className = 'flex-1 py-1.5 font-bold rounded-lg text-slate-500 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5';
            qrBox.classList.remove('hidden');
            codeInputWrapper.classList.add('hidden');
            startScanner();
        } else {
            manualBtn.className = 'flex-1 py-1.5 font-bold rounded-lg bg-white text-indigo-600 shadow-2xs transition-all flex items-center justify-center space-x-1.5';
            scanBtn.className = 'flex-1 py-1.5 font-bold rounded-lg text-slate-500 hover:text-slate-900 transition-all flex items-center justify-center space-x-1.5';
            qrBox.classList.add('hidden');
            codeInputWrapper.classList.remove('hidden');
            stopScanner();
            document.getElementById('pairCodeInput')?.focus();
        }
    }

    function ensureQrScriptLoaded(callback) {
        if (typeof Html5Qrcode !== 'undefined') {
            callback();
            return;
        }
        if (qrScriptLoaded) {
            let interval = setInterval(() => {
                if (typeof Html5Qrcode !== 'undefined') {
                    clearInterval(interval);
                    callback();
                }
            }, 50);
            return;
        }
        qrScriptLoaded = true;
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
        script.onload = callback;
        script.onerror = () => {
            const alertBox = document.getElementById('pairFormAlert');
            if (alertBox) {
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-amber-50 border border-amber-200 text-amber-800';
                alertBox.innerText = 'Camera scanner script unavailable. Please enter code manually.';
                alertBox.classList.remove('hidden');
            }
        };
        document.head.appendChild(script);
    }

    function startScanner() {
        ensureQrScriptLoaded(() => {
            if (html5QrCode && html5QrCode.isScanning) return;

            html5QrCode = new Html5Qrcode("qrReader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 220, height: 220 } },
                (decodedText) => {
                    let text = decodedText.trim();
                    let code = '';
                    if (text.includes('code=')) {
                        code = text.split('code=')[1].split('&')[0].split('#')[0];
                    } else if (text.includes('/')) {
                        const parts = text.split('/');
                        code = parts[parts.length - 1];
                    } else {
                        code = text;
                    }
                    code = code.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
                    if (code.length > 4) {
                        code = code.substring(0, 4) + '-' + code.substring(4, 8);
                    }
                    document.getElementById('pairCodeInput').value = code;
                    switchPairSubTab('manual');
                    document.getElementById('roomNoInput').focus();
                    
                    const alertBox = document.getElementById('pairFormAlert');
                    alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-indigo-50 border border-indigo-200 text-indigo-800';
                    alertBox.innerText = 'QR Scanned Code: ' + code + '. Now assign room number to connect.';
                    alertBox.classList.remove('hidden');
                },
                (errorMessage) => {}
            ).catch((err) => {
                const alertBox = document.getElementById('pairFormAlert');
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-amber-50 border border-amber-200 text-amber-800';
                alertBox.innerText = 'Camera access denied or unavailable. Please enter code manually.';
                alertBox.classList.remove('hidden');
            });
        });
    }

    function stopScanner() {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
            }).catch(err => console.error(err));
        }
    }

    document.getElementById('pairCodeInput')?.addEventListener('input', function (e) {
        let val = e.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
        if (val.length > 4) {
            val = val.substring(0, 4) + '-' + val.substring(4, 8);
        }
        e.target.value = val;
    });

    // ⚡ Fast Quick Add Form Submission
    async function submitQuickAddForm(event) {
        event.preventDefault();
        const roomNo = document.getElementById('quickRoomNo').value.trim();
        const brand = document.getElementById('quickBrand').value;
        const model = document.getElementById('quickModel').value.trim();
        const alertBox = document.getElementById('quickFormAlert');
        const submitBtn = document.getElementById('quickSubmitBtn');

        alertBox.classList.add('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Adding TV Device...';

        try {
            const response = await fetch("{{ route('hotel.devices.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    room_no: roomNo,
                    brand: brand,
                    model: model
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800';
                alertBox.innerHTML = '<i class="fa-solid fa-circle-check text-emerald-600 mr-1.5"></i> ' + data.message;
                alertBox.classList.remove('hidden');

                setTimeout(() => {
                    window.location.reload();
                }, 400);
            } else {
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800';
                alertBox.innerText = data.message || 'Failed to add TV device. Please check room number.';
                alertBox.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-bolt text-amber-300 mr-2"></i> Add TV Device';
            }
        } catch (err) {
            alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800';
            alertBox.innerText = 'Network error occurred. Please try again.';
            alertBox.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-bolt text-amber-300 mr-2"></i> Add TV Device';
        }
    }

    // 📱 Pair Screen Form Submission
    async function submitPairForm(event) {
        event.preventDefault();
        const code = document.getElementById('pairCodeInput').value.trim();
        const roomNo = document.getElementById('roomNoInput').value.trim();
        const alertBox = document.getElementById('pairFormAlert');
        const submitBtn = document.getElementById('pairSubmitBtn');

        alertBox.classList.add('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Connecting...';

        try {
            const response = await fetch("{{ route('hotel.devices.pair') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    pair_code: code,
                    room_no: roomNo
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800';
                alertBox.innerHTML = '<i class="fa-solid fa-circle-check text-emerald-600 mr-1.5"></i> ' + data.message;
                alertBox.classList.remove('hidden');

                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800 space-y-2';
                let msgHtml = `<div>${data.message || 'Pairing failed. Please check the code.'}</div>`;
                if (roomNo) {
                    msgHtml += `
                        <div class="pt-1 border-t border-rose-200/80">
                            <button type="button" onclick="quickRegisterFromPair('${roomNo}')" class="w-full py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center space-x-1.5">
                                <i class="fa-solid fa-bolt text-amber-300"></i>
                                <span>Add Room ${roomNo} Directly Now</span>
                            </button>
                        </div>
                    `;
                }
                alertBox.innerHTML = msgHtml;
                alertBox.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Connect & Pair TV</span>';
            }
        } catch (err) {
            alertBox.className = 'p-3 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800';
            alertBox.innerText = 'Network error occurred. Please try again.';
            alertBox.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Connect & Pair TV</span>';
        }
    }

    // Direct fallback from pair tab to instant quick registration
    function quickRegisterFromPair(roomNo) {
        switchModalMode('quick');
        document.getElementById('quickRoomNo').value = roomNo;
        const fakeEvent = new Event('submit', { cancelable: true });
        submitQuickAddForm(fakeEvent);
    }

    let currentModalDevice = null;

    // Modal Details functions
    function showDeviceDetails(device) {
        currentModalDevice = device;
        document.getElementById('modalSubtitle').innerText = 'Room ' + device.room_no + ' • ' + device.hotel_name;
        document.getElementById('modalLicenseKey').innerText = device.license_key || 'N/A';
        
        // Ownership details for key
        document.getElementById('modalKeyHotelName').innerText = device.hotel_name || 'N/A';
        document.getElementById('modalKeyOwner').innerText = device.owner_name || 'N/A';
        document.getElementById('modalKeyPlan').innerText = device.plan_name || 'Standard Plan';
        document.getElementById('modalKeyExpiry').innerText = device.expiry_date || 'Active';
        document.getElementById('modalKeyDistributor').innerText = device.distributor_name || 'Direct';

        document.getElementById('modalHotelName').innerText = device.hotel_name || 'N/A';
        document.getElementById('modalRoomNo').innerText = 'Room ' + device.room_no;
        document.getElementById('modalDeviceId').innerText = device.device_id || 'N/A';
        
        const hw = (device.brand || '') + ' ' + (device.model || '');
        document.getElementById('modalHardware').innerText = hw.trim() ? hw.trim() : 'Generic Smart TV';
        document.getElementById('modalOsVersion').innerText = device.os_version ? 'Android ' + device.os_version : 'Android OS';
        
        document.getElementById('modalIpAddress').innerText = 'IP: ' + (device.ip_address || 'N/A');
        document.getElementById('modalMacAddress').innerText = 'MAC: ' + (device.mac_address || 'N/A');
        document.getElementById('modalConnectedAt').innerText = device.connected_at || 'N/A';

        // Reset copy button state
        document.getElementById('copyKeyIcon').className = 'fa-solid fa-copy text-[11px]';
        document.getElementById('copyKeyText').innerText = 'Copy';

        // Lock background scroll and open modal
        document.body.style.overflow = 'hidden';
        document.body.classList.add('overflow-hidden');
        document.getElementById('deviceDetailsModal').classList.remove('hidden');
    }

    function disconnectFromModal() {
        if (!currentModalDevice || !currentModalDevice.disconnect_url) return;
        const deviceLabel = 'Room ' + currentModalDevice.room_no;
        confirmDisconnect(currentModalDevice.disconnect_url, deviceLabel);
    }

    function confirmDisconnect(actionUrl, deviceLabel) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Disconnect Device?',
                text: 'Are you sure you want to disconnect ' + deviceLabel + '? The TV will need to be re-paired to reconnect.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Disconnect',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'rounded-xl font-bold text-xs px-5 py-2.5',
                    cancelButton: 'rounded-xl font-bold text-xs px-5 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('disconnectDeviceForm');
                    form.action = actionUrl;
                    form.submit();
                }
            });
        } else {
            if (confirm('Disconnect ' + deviceLabel + '? The TV will need to be re-paired to reconnect.')) {
                const form = document.getElementById('disconnectDeviceForm');
                form.action = actionUrl;
                form.submit();
            }
        }
    }

    function closeDeviceDetailsModal() {
        document.getElementById('deviceDetailsModal').classList.add('hidden');
        document.body.style.overflow = '';
        document.body.classList.remove('overflow-hidden');
    }

    function closeDeviceModalOnBackdrop(event) {
        if (event.target.id === 'deviceDetailsModal') {
            closeDeviceDetailsModal();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeviceDetailsModal();
            closePairModal();
        }
    });

    function copyLicenseKey() {
        const key = document.getElementById('modalLicenseKey').innerText;
        if (!key || key === 'N/A' || key === '---') return;

        navigator.clipboard.writeText(key).then(() => {
            const icon = document.getElementById('copyKeyIcon');
            const text = document.getElementById('copyKeyText');
            icon.className = 'fa-solid fa-check text-[11px]';
            text.innerText = 'Copied!';

            setTimeout(() => {
                icon.className = 'fa-solid fa-copy text-[11px]';
                text.innerText = 'Copy';
            }, 2000);
        });
    }

    function copyToClipboard(text, btnElement) {
        if (!text || text === 'N/A' || text === '---') return;
        navigator.clipboard.writeText(text).then(() => {
            const originalHtml = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="fa-solid fa-check text-emerald-500"></i>';
            setTimeout(() => {
                btnElement.innerHTML = originalHtml;
            }, 1800);
        });
    }
</script>
