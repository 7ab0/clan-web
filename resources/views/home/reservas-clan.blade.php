@extends('layout.layout')

@php
    $title = 'Reservas — CLAN';
    $subTitle = 'Reservas';
@endphp

@section('content')

<style>
    /* ===== Ocultar cabecera/footer genéricos del template en esta página =====
       Mismo criterio que Fermento: reversible, no se toca el componente
       compartido, solo se oculta por CSS acá. */
    header.ak-site_header,
    .ak-commmon-hero,
    footer .ak-footer,
    #preloader {
        display: none !important;
    }

    /* ===== Tipografías de marca CLAN (self-hosted, mismos archivos que Fermento) ===== */
    @font-face { font-family: 'ClanCinzel'; src: url('{{ asset('assets/fonts/clan/CinzelDecorative-Bold.ttf') }}') format('truetype'); font-weight: 700; font-style: normal; font-display: swap; }
    @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/clan/poppins/Poppins-Regular.ttf') }}') format('truetype'); font-weight: 400; font-style: normal; font-display: swap; }
    @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/clan/poppins/Poppins-Medium.ttf') }}') format('truetype'); font-weight: 500; font-style: normal; font-display: swap; }
    @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/clan/poppins/Poppins-SemiBold.ttf') }}') format('truetype'); font-weight: 600; font-style: normal; font-display: swap; }

    /* Paleta y tokens sacados 1:1 del archivo Figma "Clan Rest — Reservas"
       (página Mobile, frame "01 — Reserva (panel único)"), escopeados a esta
       página — no toca las variables globales del template (--yellow-color,
       etc.) que usa el resto del sitio. */
    .reservasclan-page {
        --rc-negro: #141414;
        --rc-dorado: #fbb12f;
        --rc-gris-900: #252525;
        --rc-gris-500: #8c8c8c;
        --rc-gris-200: #e5e5e5;
        --rc-blanco: #ffffff;
        --rc-heading-font: 'ClanCinzel', serif;
        --rc-body-font: 'ClanPoppins', sans-serif;

        background: var(--rc-negro);
        min-height: 100vh;
        padding: 32px 16px 64px;
        display: flex;
        justify-content: center;
    }
    .reservasclan-panel { width: 100%; max-width: 430px; }

    .reservasclan-header { padding-bottom: 20px; }
    .reservasclan-brand {
        font-family: var(--rc-heading-font); font-weight: 700; font-size: 16px;
        color: var(--rc-dorado); margin: 0;
    }
    .reservasclan-subbrand {
        font-family: var(--rc-body-font); font-weight: 400; font-size: 11px;
        color: var(--rc-gris-500); margin: 2px 0 0;
    }

    .reservasclan-title {
        font-family: var(--rc-heading-font); font-weight: 700; font-size: 22px;
        color: var(--rc-blanco); margin: 0 0 24px; line-height: 1.3;
    }

    .reservasclan-section { margin-bottom: 24px; }
    .reservasclan-label {
        display: block; font-family: var(--rc-body-font); font-weight: 600; font-size: 12px;
        color: var(--rc-dorado); letter-spacing: .05em; text-transform: uppercase; margin-bottom: 10px;
    }
    .reservasclan-field-label {
        display: block; font-family: var(--rc-body-font); font-weight: 500; font-size: 12px;
        color: var(--rc-gris-500); margin-bottom: 8px;
    }

    .reservasclan-row { display: flex; gap: 12px; }
    .reservasclan-col-fecha { flex: 0 0 210px; min-width: 0; }
    .reservasclan-col-hora { flex: 1; min-width: 0; }

    .reservasclan-input, .reservasclan-select, .reservasclan-textarea {
        width: 100%; background: var(--rc-gris-900); border: 1px solid var(--rc-gris-500);
        border-radius: 10px; color: var(--rc-blanco); font-family: var(--rc-body-font);
        font-size: 14px; font-weight: 500; padding: 14px 16px; box-sizing: border-box;
    }
    .reservasclan-input::placeholder, .reservasclan-textarea::placeholder { color: var(--rc-gris-200); font-weight: 400; }
    .reservasclan-input:focus, .reservasclan-select:focus, .reservasclan-textarea:focus {
        outline: none; border-color: var(--rc-dorado);
    }
    .reservasclan-select {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='8' viewBox='0 0 14 8' fill='none'%3E%3Cpath d='M1 1L7 7L13 1' stroke='%23fbb12f' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 16px center; padding-right: 40px;
    }
    .reservasclan-select option { color: var(--rc-negro); }
    .reservasclan-textarea { resize: vertical; min-height: 76px; font-weight: 400; }
    .reservasclan-note-warning {
        font-family: var(--rc-body-font); font-size: 12px; color: var(--rc-dorado); margin: 8px 0 0;
    }

    /* Grilla de mesas */
    .reservasclan-tables-row { display: flex; gap: 8px; margin-bottom: 8px; }
    .reservasclan-tables-row:last-child { margin-bottom: 0; }
    .reservasclan-table-btn {
        flex: 1; height: 56px; border-radius: 8px; background: var(--rc-gris-900);
        border: 1px solid var(--rc-gris-500); display: flex; flex-direction: column;
        align-items: center; justify-content: center; gap: 2px; cursor: pointer;
        font-family: var(--rc-body-font); transition: all .15s ease; padding: 0;
    }
    .reservasclan-table-btn .n { font-size: 12px; font-weight: 500; color: var(--rc-gris-200); }
    .reservasclan-table-btn .cap { font-size: 10px; font-weight: 400; color: var(--rc-gris-500); }
    .reservasclan-table-btn:hover:not(:disabled) { border-color: var(--rc-dorado); }
    .reservasclan-table-btn.is-selected { background: var(--rc-dorado); border-color: var(--rc-dorado); }
    .reservasclan-table-btn.is-selected .n, .reservasclan-table-btn.is-selected .cap { color: var(--rc-negro); }
    .reservasclan-table-btn:disabled {
        background: var(--rc-negro); border-color: var(--rc-gris-900); cursor: not-allowed;
    }
    .reservasclan-table-btn:disabled .n, .reservasclan-table-btn:disabled .cap { color: var(--rc-gris-900); }
    .reservasclan-tables-hint { font-family: var(--rc-body-font); font-size: 12px; color: var(--rc-gris-500); margin-top: 4px; }

    /* Stepper de personas */
    .reservasclan-stepper {
        display: flex; align-items: center; justify-content: space-between;
        background: var(--rc-gris-900); border: 1px solid var(--rc-gris-500);
        border-radius: 10px; padding: 10px 16px;
    }
    .reservasclan-stepper-btn {
        width: 32px; height: 32px; border-radius: 16px; background: var(--rc-negro);
        border: 1px solid var(--rc-gris-500); color: var(--rc-gris-200); font-family: var(--rc-body-font);
        font-weight: 600; font-size: 16px; display: flex; align-items: center; justify-content: center;
        cursor: pointer; line-height: 1;
    }
    .reservasclan-stepper-btn:disabled { opacity: .4; cursor: not-allowed; }
    .reservasclan-stepper-value { font-family: var(--rc-body-font); font-weight: 600; font-size: 14px; color: var(--rc-blanco); }

    /* Resumen de reserva (seña) */
    .reservasclan-summary-card {
        background: var(--rc-negro); border: 1px solid var(--rc-dorado); border-radius: 12px;
        padding: 20px; text-align: center;
    }
    .reservasclan-summary-amount { font-family: var(--rc-heading-font); font-weight: 700; font-size: 28px; color: var(--rc-dorado); margin: 0; }
    .reservasclan-summary-detail { font-family: var(--rc-body-font); font-weight: 400; font-size: 12px; color: var(--rc-gris-200); margin: 6px 0 0; }
    .reservasclan-summary-footnote {
        font-family: var(--rc-body-font); font-weight: 400; font-size: 12px; color: var(--rc-gris-500);
        margin: 10px 0 0; line-height: 1.5;
    }

    .reservasclan-submit {
        width: 100%; background: var(--rc-dorado); border: none; border-radius: 12px;
        padding: 16px; font-family: var(--rc-body-font); font-weight: 600; font-size: 15px;
        color: var(--rc-negro); cursor: pointer;
    }
    .reservasclan-submit:disabled { opacity: .55; cursor: not-allowed; }

    .reservasclan-alert-error {
        border: 1px solid #b95a4a; color: #f3c9c1; padding: 14px 18px; margin-bottom: 20px;
        border-radius: 10px; font-family: var(--rc-body-font); font-size: 13px;
    }

    /* Panel de confirmación (frame "02 — Confirmación" del Figma) */
    .reservasclan-confirm { display: none; flex-direction: column; align-items: center; text-align: center; }
    .reservasclan-confirm.is-visible { display: flex; }
    .reservasclan-confirm-check {
        width: 64px; height: 64px; border-radius: 32px; background: var(--rc-dorado);
        display: flex; align-items: center; justify-content: center; margin-bottom: 16px;
    }
    .reservasclan-confirm-check span { font-family: var(--rc-body-font); font-weight: 600; font-size: 28px; color: var(--rc-negro); }
    .reservasclan-confirm-title { font-family: var(--rc-heading-font); font-weight: 700; font-size: 22px; color: var(--rc-blanco); margin: 0 0 12px; }
    .reservasclan-confirm-copy { font-family: var(--rc-body-font); font-size: 13px; color: var(--rc-gris-500); margin: 0 0 20px; line-height: 1.5; }
    .reservasclan-confirm-card {
        background: var(--rc-gris-900); border-radius: 10px; padding: 16px; width: 100%;
        box-sizing: border-box; margin-bottom: 32px; text-align: left;
    }
    .reservasclan-confirm-row { display: flex; align-items: center; justify-content: space-between; font-family: var(--rc-body-font); font-size: 13px; }
    .reservasclan-confirm-row + .reservasclan-confirm-row { margin-top: 10px; }
    .reservasclan-confirm-row .k { color: var(--rc-gris-500); font-weight: 400; }
    .reservasclan-confirm-row .v { color: var(--rc-gris-200); font-weight: 500; }
    .reservasclan-confirm-done {
        width: 100%; background: var(--rc-dorado); border: none; border-radius: 12px;
        padding: 16px; font-family: var(--rc-body-font); font-weight: 600; font-size: 15px;
        color: var(--rc-negro); cursor: pointer; text-decoration: none; display: block; text-align: center;
        box-sizing: border-box;
    }

    @media (max-width: 460px) {
        .reservasclan-row { flex-direction: column; }
        .reservasclan-col-fecha, .reservasclan-col-hora { flex: 1 1 auto; }
    }
</style>

<div class="reservasclan-page">
    <div class="reservasclan-panel" id="reservasClanRoot"
         data-csrf="{{ csrf_token() }}"
         data-disponibilidad-url="{{ route('reservas.clan.disponibilidad') }}"
         data-store-url="{{ route('reservas.clan.store') }}"
         data-today="{{ $today }}">

        <div class="reservasclan-header">
            <p class="reservasclan-brand">CLAN</p>
            <p class="reservasclan-subbrand">Reservas</p>
        </div>

        <div id="reservasClanForm">
            <h1 class="reservasclan-title">Reserva tu mesa</h1>

            <div id="reservasClanError" class="reservasclan-alert-error" style="display:none;"></div>

            <div class="reservasclan-section">
                <span class="reservasclan-label">Fecha y hora</span>
                <div class="reservasclan-row">
                    <div class="reservasclan-col-fecha">
                        <span class="reservasclan-field-label">Fecha</span>
                        <input type="date" id="rcFecha" class="reservasclan-input" value="{{ $today }}" min="{{ $today }}">
                    </div>
                    <div class="reservasclan-col-hora">
                        <span class="reservasclan-field-label">Hora</span>
                        <select id="rcHora" class="reservasclan-select">
                            @foreach ($timeSlots as $slot)
                                <option value="{{ $slot['value'] }}">{{ $slot['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p id="rcClosedWarning" class="reservasclan-note-warning" style="display:none;">
                    Clan está cerrado los martes — elige otra fecha.
                </p>
            </div>

            <div class="reservasclan-section">
                <span class="reservasclan-label">Mesa</span>
                <div id="rcTablesGrid"></div>
                <p id="rcTablesHint" class="reservasclan-tables-hint"></p>
            </div>

            <div class="reservasclan-section">
                <span class="reservasclan-label">Tus datos</span>
                <span class="reservasclan-field-label">Nombre completo</span>
                <input type="text" id="rcNombre" class="reservasclan-input" placeholder="Nombre y apellido" style="margin-bottom:16px;">
                <span class="reservasclan-field-label">WhatsApp</span>
                <input type="tel" id="rcTelefono" class="reservasclan-input" placeholder="+51 9xx xxx xxx" style="margin-bottom:16px;">
                <span class="reservasclan-field-label">Personas</span>
                <div class="reservasclan-stepper" style="margin-bottom:16px;">
                    <button type="button" id="rcPaxMinus" class="reservasclan-stepper-btn">−</button>
                    <span class="reservasclan-stepper-value"><span id="rcPaxValue">2</span> personas</span>
                    <button type="button" id="rcPaxPlus" class="reservasclan-stepper-btn">+</button>
                </div>
                <span class="reservasclan-field-label">Notas (opcional)</span>
                <textarea id="rcNotas" class="reservasclan-textarea" placeholder="Alergias, ocasión especial, etc."></textarea>
            </div>

            <div class="reservasclan-section">
                <span class="reservasclan-label">Reserva</span>
                <div class="reservasclan-summary-card">
                    <p class="reservasclan-summary-amount">S/ <span id="rcDepositAmount">60</span></p>
                    <p class="reservasclan-summary-detail"><span id="rcPaxSummary">2</span> personas × S/30 por persona</p>
                </div>
                <p class="reservasclan-summary-footnote">La seña se coordina por WhatsApp — no se cobra en este formulario.</p>
            </div>

            <button type="button" id="rcSubmit" class="reservasclan-submit">Confirmar reserva</button>
        </div>

        <div id="reservasClanConfirm" class="reservasclan-confirm">
            <div class="reservasclan-confirm-check"><span>&#10003;</span></div>
            <h2 class="reservasclan-confirm-title">Reserva confirmada</h2>
            <p class="reservasclan-confirm-copy">Te contactaremos por WhatsApp para coordinar tu seña.</p>
            <div class="reservasclan-confirm-card">
                <div class="reservasclan-confirm-row">
                    <span class="k">Fecha y hora</span>
                    <span class="v" id="rcConfirmFechaHora"></span>
                </div>
                <div class="reservasclan-confirm-row">
                    <span class="k">Mesa</span>
                    <span class="v" id="rcConfirmMesa"></span>
                </div>
                <div class="reservasclan-confirm-row">
                    <span class="k">Código</span>
                    <span class="v" id="rcConfirmCodigo"></span>
                </div>
            </div>
            <a href="{{ route('reservas.clan.show') }}" class="reservasclan-confirm-done">Listo</a>
        </div>
    </div>
</div>

<script>
(function () {
    // Layout físico de Clan (7 mesas) — hardcodeado acá porque hoy no hay un
    // endpoint público de "listar mesas" en La Comanda, solo el de
    // disponibilidad (que ya filtra por fecha/hora/pax). Mismos datos que
    // prisma/seed.ts de La Comanda (Fase "mesas reales de Clan"): si el
    // layout físico cambia ahí, hay que actualizar esta lista a mano.
    var CLAN_TABLES = [
        { label: 'Mesa 1', capacityMin: 1, capacityMax: 4 },
        { label: 'Mesa 2', capacityMin: 1, capacityMax: 2 },
        { label: 'Mesa 3', capacityMin: 1, capacityMax: 4 },
        { label: 'Mesa 4', capacityMin: 1, capacityMax: 4 },
        { label: 'Mesa 5', capacityMin: 1, capacityMax: 4 },
        { label: 'Mesa 6', capacityMin: 1, capacityMax: 4 },
        { label: 'Mesa 7', capacityMin: 1, capacityMax: 4 }
    ];
    var DEPOSIT_PER_PERSON = 30;
    var MIN_PAX = 1;
    var MAX_PAX = 50;
    var CLOSED_WEEKDAY = 2; // martes (Date#getDay())

    var root = document.getElementById('reservasClanRoot');
    var csrfToken = root.dataset.csrf;
    var disponibilidadUrl = root.dataset.disponibilidadUrl;
    var storeUrl = root.dataset.storeUrl;

    var fechaInput = document.getElementById('rcFecha');
    var horaSelect = document.getElementById('rcHora');
    var closedWarning = document.getElementById('rcClosedWarning');
    var tablesGrid = document.getElementById('rcTablesGrid');
    var tablesHint = document.getElementById('rcTablesHint');
    var paxValueEl = document.getElementById('rcPaxValue');
    var paxSummaryEl = document.getElementById('rcPaxSummary');
    var paxMinusBtn = document.getElementById('rcPaxMinus');
    var paxPlusBtn = document.getElementById('rcPaxPlus');
    var depositAmountEl = document.getElementById('rcDepositAmount');
    var nombreInput = document.getElementById('rcNombre');
    var telefonoInput = document.getElementById('rcTelefono');
    var notasInput = document.getElementById('rcNotas');
    var submitBtn = document.getElementById('rcSubmit');
    var errorBox = document.getElementById('reservasClanError');

    var formPanel = document.getElementById('reservasClanForm');
    var confirmPanel = document.getElementById('reservasClanConfirm');

    var state = {
        pax: 2,
        availableByLabel: {}, // label -> { id, capacityMin, capacityMax }
        selectedTableId: null,
        selectedTableLabel: null,
        groupExceedsSingleTable: false,
        fetchToken: 0
    };

    function showError(message) {
        errorBox.textContent = message;
        errorBox.style.display = 'block';
    }
    function clearError() {
        errorBox.style.display = 'none';
        errorBox.textContent = '';
    }

    function isClosedDay() {
        var d = new Date(fechaInput.value + 'T00:00:00');
        return d.getDay() === CLOSED_WEEKDAY;
    }

    function renderTablesGrid() {
        tablesGrid.innerHTML = '';
        var rows = [CLAN_TABLES.slice(0, 4), CLAN_TABLES.slice(4)];
        rows.forEach(function (rowTables) {
            var rowEl = document.createElement('div');
            rowEl.className = 'reservasclan-tables-row';
            rowTables.forEach(function (table) {
                var available = state.availableByLabel[table.label];
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'reservasclan-table-btn';
                btn.disabled = !available;
                if (state.selectedTableLabel === table.label && available) {
                    btn.classList.add('is-selected');
                }
                btn.innerHTML = '<span class="n">' + table.label + '</span><span class="cap">' +
                    (available ? available.capacityMax : table.capacityMax) + ' pax</span>';
                if (available) {
                    btn.addEventListener('click', function () {
                        state.selectedTableId = available.id;
                        state.selectedTableLabel = table.label;
                        renderTablesGrid();
                    });
                }
                rowEl.appendChild(btn);
            });
            tablesGrid.appendChild(rowEl);
        });

        if (state.groupExceedsSingleTable) {
            tablesHint.textContent = 'Tu grupo no entra en una sola mesa — te contactamos por WhatsApp para acomodarte con mesas combinadas.';
        } else if (!state.selectedTableId) {
            tablesHint.textContent = 'Elige una mesa disponible para tu fecha, hora y número de personas.';
        } else {
            tablesHint.textContent = 'Elegiste la ' + state.selectedTableLabel + '.';
        }
    }

    function updateDepositSummary() {
        paxValueEl.textContent = state.pax;
        paxSummaryEl.textContent = state.pax;
        depositAmountEl.textContent = state.pax * DEPOSIT_PER_PERSON;
    }

    function fetchAvailability() {
        clearError();
        if (isClosedDay()) {
            closedWarning.style.display = 'block';
            state.availableByLabel = {};
            state.selectedTableId = null;
            state.selectedTableLabel = null;
            renderTablesGrid();
            submitBtn.disabled = true;
            return;
        }
        closedWarning.style.display = 'none';
        submitBtn.disabled = false;

        var token = ++state.fetchToken;
        var params = new URLSearchParams({ fecha: fechaInput.value, hora: horaSelect.value, pax: String(state.pax) });

        fetch(disponibilidadUrl + '?' + params.toString(), {
            headers: { 'Accept': 'application/json' }
        })
            .then(function (res) { return res.json().then(function (body) { return { ok: res.ok, body: body }; }); })
            .then(function (result) {
                if (token !== state.fetchToken) return; // respuesta vieja, ignorar
                if (!result.ok) {
                    showError(result.body.error || 'No pudimos consultar la disponibilidad.');
                    state.availableByLabel = {};
                    state.selectedTableId = null;
                    state.selectedTableLabel = null;
                    renderTablesGrid();
                    return;
                }
                var byLabel = {};
                (result.body.tables || []).forEach(function (t) { byLabel[t.label] = t; });
                state.availableByLabel = byLabel;
                state.groupExceedsSingleTable = !!result.body.groupExceedsSingleTable;
                if (state.selectedTableLabel && !byLabel[state.selectedTableLabel]) {
                    state.selectedTableId = null;
                    state.selectedTableLabel = null;
                }
                renderTablesGrid();
            })
            .catch(function () {
                if (token !== state.fetchToken) return;
                showError('No pudimos conectar con el sistema de reservas. Intenta de nuevo.');
            });
    }

    fechaInput.addEventListener('change', fetchAvailability);
    horaSelect.addEventListener('change', fetchAvailability);

    paxMinusBtn.addEventListener('click', function () {
        if (state.pax <= MIN_PAX) return;
        state.pax -= 1;
        updateDepositSummary();
        fetchAvailability();
    });
    paxPlusBtn.addEventListener('click', function () {
        if (state.pax >= MAX_PAX) return;
        state.pax += 1;
        updateDepositSummary();
        fetchAvailability();
    });

    function showConfirmation(reservation) {
        var fechaHora = new Date(fechaInput.value + 'T' + horaSelect.value + ':00');
        var fechaLabel = fechaHora.toLocaleDateString('es-PE', { weekday: 'short', day: 'numeric', month: 'short' });
        var horaLabel = horaSelect.options[horaSelect.selectedIndex].text;
        document.getElementById('rcConfirmFechaHora').textContent = fechaLabel + ' · ' + horaLabel;
        document.getElementById('rcConfirmMesa').textContent = state.selectedTableLabel
            ? state.selectedTableLabel + ' · ' + state.pax + ' pax'
            : 'Sin asignar — te confirmamos por WhatsApp';
        // Código de referencia solo visual (no es una clave de búsqueda real,
        // Daniel identifica la reserva por nombre/WhatsApp en su panel) —
        // derivado del id real para que el cliente tenga algo corto que citar.
        var shortCode = String(reservation.id || '').replace(/[^a-zA-Z0-9]/g, '').slice(-4).toUpperCase() || '0000';
        document.getElementById('rcConfirmCodigo').textContent = 'CLAN-' + shortCode;

        formPanel.style.display = 'none';
        confirmPanel.classList.add('is-visible');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    submitBtn.addEventListener('click', function () {
        clearError();

        if (!nombreInput.value.trim()) { showError('Escribe tu nombre completo.'); return; }
        if (!telefonoInput.value.trim()) { showError('Escribe tu número de WhatsApp.'); return; }
        if (isClosedDay()) { showError('Clan está cerrado los martes — elige otra fecha.'); return; }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Confirmando…';

        fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify((function () {
                // notes es opcional pero NO nullable en el endpoint de La
                // Comanda (z.string().optional(), sin .nullable()) — hay que
                // omitir la clave entera si está vacío, nunca mandar null.
                var payload = {
                    fecha: fechaInput.value,
                    hora: horaSelect.value,
                    partySize: state.pax,
                    tableId: state.selectedTableId,
                    customerName: nombreInput.value.trim(),
                    customerPhone: telefonoInput.value.trim(),
                    depositAmount: state.pax * DEPOSIT_PER_PERSON
                };
                var notes = notasInput.value.trim();
                if (notes) payload.notes = notes;
                return payload;
            })())
        })
            .then(function (res) { return res.json().then(function (body) { return { ok: res.ok, body: body }; }); })
            .then(function (result) {
                if (!result.ok) {
                    showError(result.body.error || 'No pudimos crear tu reserva.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Confirmar reserva';
                    return;
                }
                showConfirmation(result.body);
            })
            .catch(function () {
                showError('No pudimos conectar con el sistema de reservas. Intenta de nuevo.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Confirmar reserva';
            });
    });

    function preselectNextAvailableSlot() {
        // Si la fecha por defecto es hoy, el primer horario del día (13:30)
        // puede ya haber pasado — arrancar en el primer slot todavía
        // disponible evita mostrar "Ese horario ya pasó" apenas se carga la
        // página.
        if (fechaInput.value !== root.dataset.today) return;
        var now = new Date();
        var nowMinutes = now.getHours() * 60 + now.getMinutes();
        for (var i = 0; i < horaSelect.options.length; i++) {
            var opt = horaSelect.options[i];
            var parts = opt.value.split(':');
            var slotMinutes = parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
            if (slotMinutes >= nowMinutes) {
                horaSelect.value = opt.value;
                return;
            }
        }
        // Ya pasó el último horario de hoy: deja el último slot seleccionado,
        // el servidor lo va a rechazar y el cliente puede cambiar de fecha.
        horaSelect.selectedIndex = horaSelect.options.length - 1;
    }

    preselectNextAvailableSlot();
    renderTablesGrid();
    updateDepositSummary();
    fetchAvailability();
})();
</script>

@endsection
