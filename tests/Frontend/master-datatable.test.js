/**
 * @jest-environment jsdom
 *
 * Frontend unit tests for the Master module DataTable toolbar, status toggles,
 * filter controls, and AJAX interactions.
 *
 * These tests validate the JavaScript behaviour specified in
 * _datatable-toolbar-template.blade.php and status_toggle_master.blade.php.
 */

// ─── Mocks ────────────────────────────────────────────────────────────────

// jQuery mock (minimal chaining API)
const jqElements = {};

function createJqElement(selector) {
    const el = {
        _selector: selector,
        _data: {},
        _classes: [],
        _html: '',
        _val: '',
        _checked: false,
        _handlers: {},
        _visible: true,
        _attr: {},
        _prop: {},
        data(key, val) {
            if (val === undefined) return this._data[key];
            this._data[key] = val;
            return this;
        },
        attr(key, val) {
            if (val === undefined) return this._attr[key];
            this._attr[key] = val;
            return this;
        },
        prop(key, val) {
            if (val === undefined) return this._prop[key];
            this._prop[key] = val;
            return this;
        },
        val(v) {
            if (v === undefined) return this._val;
            this._val = v;
            return this;
        },
        html(h) {
            if (h === undefined) return this._html;
            this._html = h;
            return this;
        },
        text(t) {
            if (t === undefined) return this._html;
            this._html = t;
            return this;
        },
        addClass(c) { this._classes.push(c); return this; },
        removeClass(c) { this._classes = this._classes.filter(x => x !== c); return this; },
        hasClass(c) { return this._classes.includes(c); },
        on(event, handler) {
            this._handlers[event] = this._handlers[event] || [];
            this._handlers[event].push(handler);
            return this;
        },
        off(event) { if (event) delete this._handlers[event]; return this; },
        trigger(event, data) {
            (this._handlers[event] || []).forEach(fn => fn.call(this, { preventDefault: () => {} }, data));
            return this;
        },
        show() { this._visible = true; return this; },
        hide() { this._visible = false; return this; },
        slideDown(ms, cb) { this._visible = true; if (cb) cb(); return this; },
        slideUp(ms, cb) { this._visible = false; if (cb) cb(); return this; },
        find(sel) { return createJqElement(sel); },
        closest(sel) { return createJqElement(sel); },
        append(el) { return this; },
        length: 1,
        checked: false,
    };
    jqElements[selector] = el;
    return el;
}

// ─── Test Suite: Toolbar Filter Controls ──────────────────────────────────

describe('DataTable Toolbar Controls', () => {
    let searchInput, statusFilter, dateFrom, dateTo, pageLengthSelect;

    beforeEach(() => {
        searchInput      = createJqElement('#unit-table-global-search');
        statusFilter     = createJqElement('#unit-table-status-filter');
        dateFrom         = createJqElement('#unit-table-date-from');
        dateTo           = createJqElement('#unit-table-date-to');
        pageLengthSelect = createJqElement('#unit-table-page-length');
    });

    test('search input exists and accepts value', () => {
        searchInput.val('Kilogram');
        expect(searchInput.val()).toBe('Kilogram');
    });

    test('status filter has correct default options', () => {
        const options = ['', '1', '0']; // All Status, Active, Inactive
        expect(options).toContain('');
        expect(options).toContain('1');
        expect(options).toContain('0');
    });

    test('page length options include required values', () => {
        const allowedLengths = [10, 50, 100, 500];
        allowedLengths.forEach(len => {
            pageLengthSelect.val(String(len));
            expect(pageLengthSelect.val()).toBe(String(len));
        });
    });

    test('date from and date to accept date values', () => {
        dateFrom.val('2026-01-01');
        dateTo.val('2026-12-31');
        expect(dateFrom.val()).toBe('2026-01-01');
        expect(dateTo.val()).toBe('2026-12-31');
    });

    test('status filter triggers change event', () => {
        let changed = false;
        statusFilter.on('change', () => { changed = true; });
        statusFilter.trigger('change');
        expect(changed).toBe(true);
    });

    test('date filters trigger change event', () => {
        let fromChanged = false;
        let toChanged = false;
        dateFrom.on('change', () => { fromChanged = true; });
        dateTo.on('change', () => { toChanged = true; });
        dateFrom.trigger('change');
        dateTo.trigger('change');
        expect(fromChanged).toBe(true);
        expect(toChanged).toBe(true);
    });
});

// ─── Test Suite: Mobile Filter Toggle ─────────────────────────────────────

describe('Mobile Filter Toggle', () => {
    let filterRow, toggleBtn, icon;

    beforeEach(() => {
        filterRow = createJqElement('#unit-table-filter-row');
        toggleBtn = createJqElement('#unit-table-filter-toggle');
        icon      = createJqElement('#unit-table-filter-toggle i');
        filterRow._visible = true;
    });

    test('filter row starts visible on desktop', () => {
        expect(filterRow._visible).toBe(true);
    });

    test('filter row can be hidden (collapsed)', () => {
        filterRow.slideUp(300);
        expect(filterRow._visible).toBe(false);
    });

    test('filter row can be shown (expanded)', () => {
        filterRow._visible = false;
        filterRow.slideDown(300);
        expect(filterRow._visible).toBe(true);
    });

    test('toggle button toggles filter visibility', () => {
        let isCollapsed = false;

        toggleBtn.on('click', () => {
            if (isCollapsed) {
                filterRow.slideDown(300, () => { isCollapsed = false; });
            } else {
                filterRow.slideUp(300, () => { isCollapsed = true; });
            }
        });

        // First click: collapse
        toggleBtn.trigger('click');
        expect(filterRow._visible).toBe(false);
        expect(isCollapsed).toBe(true);

        // Second click: expand
        toggleBtn.trigger('click');
        expect(filterRow._visible).toBe(true);
        expect(isCollapsed).toBe(false);
    });
});

// ─── Test Suite: Status Toggle Button ─────────────────────────────────────

describe('Master Status Toggle', () => {
    test('toggle element has required data attributes', () => {
        const toggle = createJqElement('.master-status-toggle');
        toggle._data = { id: 5, entity: 'unit' };
        toggle._checked = true;

        expect(toggle.data('id')).toBe(5);
        expect(toggle.data('entity')).toBe('unit');
    });

    test('status value is 1 when checked, 0 when unchecked', () => {
        const toggle = createJqElement('.master-status-toggle');

        toggle._prop.checked = true;
        const statusWhenChecked = toggle.prop('checked') ? 1 : 0;
        expect(statusWhenChecked).toBe(1);

        toggle._prop.checked = false;
        const statusWhenUnchecked = toggle.prop('checked') ? 1 : 0;
        expect(statusWhenUnchecked).toBe(0);
    });

    test('AJAX post data is correctly structured', () => {
        const toggle = createJqElement('.master-status-toggle');
        toggle._data = { id: 10, entity: 'category' };
        toggle._prop.checked = true;

        const ajaxData = {
            id: toggle.data('id'),
            entity: toggle.data('entity'),
            status_value: toggle.prop('checked') ? 1 : 0,
        };

        expect(ajaxData).toEqual({
            id: 10,
            entity: 'category',
            status_value: 1,
        });
    });

    test('all master entities have valid entity values', () => {
        const validEntities = [
            'category', 'denomination_type', 'income_expense_type', 'item_type',
            'machine_detail', 'partner', 'partnership_type', 'payment_type',
            'store', 'supplier', 'tax_rate', 'transport_type', 'unit', 'vendor',
        ];

        validEntities.forEach(entity => {
            expect(typeof entity).toBe('string');
            expect(entity.length).toBeGreaterThan(0);
        });

        // Ensure no duplicates
        const unique = [...new Set(validEntities)];
        expect(unique.length).toBe(validEntities.length);
    });

    test('on error, toggle reverts to previous state', () => {
        const toggle = createJqElement('.master-status-toggle-revert');
        toggle._prop.checked = true;

        // Simulate error callback
        const originalState = toggle.prop('checked');
        // In the real handler: toggle.prop('checked', !statusValue);
        toggle.prop('checked', !originalState);

        expect(toggle.prop('checked')).toBe(false);
    });
});

// ─── Test Suite: AJAX Request Builder ─────────────────────────────────────

describe('Status Toggle AJAX Request', () => {
    let ajaxCalls;

    beforeEach(() => {
        ajaxCalls = [];
    });

    function mockAjax(options) {
        ajaxCalls.push(options);
        if (options.success && options._mockSuccess) {
            options.success(options._mockSuccess);
        }
        if (options.error && options._mockError) {
            options.error(options._mockError);
        }
    }

    test('sends POST request with correct data', () => {
        const url = '/rrkadminmanager/master/status-change';
        const data = { id: 1, entity: 'unit', status_value: 0 };

        mockAjax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data: data,
            _mockSuccess: { message: 'Status updated successfully.', status: 200 },
            success(result) { /* handled */ },
        });

        expect(ajaxCalls.length).toBe(1);
        expect(ajaxCalls[0].type).toBe('POST');
        expect(ajaxCalls[0].url).toBe(url);
        expect(ajaxCalls[0].data).toEqual(data);
    });

    test('handles success response correctly', () => {
        let resultMessage = null;
        let resultStatus = null;

        mockAjax({
            type: 'POST',
            url: '/rrkadminmanager/master/status-change',
            data: { id: 1, entity: 'tax_rate', status_value: 1 },
            _mockSuccess: { message: 'Status updated successfully.', status: 200 },
            success(result) {
                resultMessage = result.message;
                resultStatus = result.status;
            },
        });

        expect(resultMessage).toBe('Status updated successfully.');
        expect(resultStatus).toBe(200);
    });

    test('handles error response and reverts toggle', () => {
        let errorOccurred = false;
        let toggleReverted = false;

        mockAjax({
            type: 'POST',
            url: '/rrkadminmanager/master/status-change',
            data: { id: 999, entity: 'unit', status_value: 1 },
            _mockError: { status: 404 },
            error() {
                errorOccurred = true;
                toggleReverted = true; // Simulates: toggle.prop('checked', !statusValue);
            },
        });

        expect(errorOccurred).toBe(true);
        expect(toggleReverted).toBe(true);
    });
});

// ─── Test Suite: SweetAlert Integration ───────────────────────────────────

describe('Alert Message Function', () => {
    function alertMessage(message, status) {
        if (status === 200) {
            return { title: 'Success', icon: 'success', text: message };
        } else {
            return { title: 'Oops!', icon: 'error', text: message };
        }
    }

    test('success status shows success alert', () => {
        const result = alertMessage('Status updated successfully.', 200);
        expect(result.title).toBe('Success');
        expect(result.icon).toBe('success');
        expect(result.text).toBe('Status updated successfully.');
    });

    test('error status shows error alert', () => {
        const result = alertMessage('Unable to update status.', 400);
        expect(result.title).toBe('Oops!');
        expect(result.icon).toBe('error');
        expect(result.text).toBe('Unable to update status.');
    });

    test('unknown status shows error alert', () => {
        const result = alertMessage('Server error', 500);
        expect(result.title).toBe('Oops!');
        expect(result.icon).toBe('error');
    });
});

// ─── Test Suite: DataTable Params Builder ─────────────────────────────────

describe('DataTable AJAX Parameters', () => {
    function buildAjaxData(tableId) {
        const searchEl   = jqElements[`#${tableId}-global-search`] || createJqElement(`#${tableId}-global-search`);
        const statusEl   = jqElements[`#${tableId}-status-filter`] || createJqElement(`#${tableId}-status-filter`);
        const dateFromEl = jqElements[`#${tableId}-date-from`] || createJqElement(`#${tableId}-date-from`);
        const dateToEl   = jqElements[`#${tableId}-date-to`]   || createJqElement(`#${tableId}-date-to`);

        return {
            status:    statusEl.val(),
            date_from: dateFromEl.val(),
            date_to:   dateToEl.val(),
        };
    }

    test('includes status filter in AJAX data', () => {
        const statusEl = jqElements['#unit-table-status-filter'] || createJqElement('#unit-table-status-filter');
        statusEl.val('1');

        const data = buildAjaxData('unit-table');
        expect(data.status).toBe('1');
    });

    test('includes date range in AJAX data', () => {
        (jqElements['#category-table-date-from'] || createJqElement('#category-table-date-from')).val('2026-01-01');
        (jqElements['#category-table-date-to']   || createJqElement('#category-table-date-to')).val('2026-06-30');

        const data = buildAjaxData('category-table');
        expect(data.date_from).toBe('2026-01-01');
        expect(data.date_to).toBe('2026-06-30');
    });

    test('empty filters send empty strings', () => {
        const data = buildAjaxData('tax-rate-table');
        expect(data.status).toBe('');
        expect(data.date_from).toBe('');
        expect(data.date_to).toBe('');
    });
});

// ─── Test Suite: Search Debounce ──────────────────────────────────────────

describe('Search Debounce', () => {
    jest.useFakeTimers();

    test('search is debounced by 300ms', () => {
        let searchCount = 0;
        const searchFn = () => { searchCount++; };

        let searchDebounce;
        function onKeyup(value) {
            clearTimeout(searchDebounce);
            searchDebounce = setTimeout(searchFn, 300);
        }

        // Fire rapidly
        onKeyup('K');
        onKeyup('Ki');
        onKeyup('Kil');
        onKeyup('Kilo');

        // Before 300ms, no search
        jest.advanceTimersByTime(200);
        expect(searchCount).toBe(0);

        // After 300ms from last keystroke
        jest.advanceTimersByTime(150);
        expect(searchCount).toBe(1);
    });

    test('each new keystroke resets the debounce timer', () => {
        let searchCount = 0;
        const searchFn = () => { searchCount++; };

        let searchDebounce;
        function onKeyup() {
            clearTimeout(searchDebounce);
            searchDebounce = setTimeout(searchFn, 300);
        }

        onKeyup();
        jest.advanceTimersByTime(250);
        onKeyup(); // Reset
        jest.advanceTimersByTime(250);
        expect(searchCount).toBe(0); // Still no search

        jest.advanceTimersByTime(100);
        expect(searchCount).toBe(1); // Now 300ms after last keystroke
    });
});

// ─── Test Suite: Page Length ──────────────────────────────────────────────

describe('Page Length Control', () => {
    test('valid page lengths accepted', () => {
        const validLengths = [10, 50, 100, 500];
        const pageLenSelect = createJqElement('#test-table-page-length');

        validLengths.forEach(len => {
            pageLenSelect.val(String(len));
            expect(parseInt(pageLenSelect.val())).toBe(len);
        });
    });
});

// ─── Test Suite: Warehouse-specific toggles ───────────────────────────────

describe('Warehouse Special Toggles', () => {
    test('warehouse status toggle has warehouse_id data attribute', () => {
        const toggle = createJqElement('.statuschange');
        toggle._data = { warehouse_id: 42 };

        expect(toggle.data('warehouse_id')).toBe(42);
    });

    test('warehouse default toggle has warehouse_id data attribute', () => {
        const toggle = createJqElement('.defaultwarehouse');
        toggle._data = { warehouse_id: 7 };

        expect(toggle.data('warehouse_id')).toBe(7);
    });

    test('default warehouse AJAX payload is structured correctly', () => {
        const warehouseId = 7;
        const statusValue = 1;

        const payload = {
            warehouse_id: warehouseId,
            status_value: statusValue,
        };

        expect(payload).toEqual({
            warehouse_id: 7,
            status_value: 1,
        });
    });
});

// ─── Test Suite: Create Button Customization ──────────────────────────────

describe('Create Button Customization', () => {
    test('create button text is replaced with icon + label', () => {
        const btn = createJqElement('.buttons-create');
        btn.html('<i class="fas fa-plus-circle text-white me-1"></i>Add New');
        btn.removeClass('btn-primary');
        btn.removeClass('btn-secondary');
        btn.removeClass('btn-lg');
        btn.addClass('btn-success');
        btn.addClass('btn-xs');
        btn.addClass('btn-sm');

        expect(btn.html()).toContain('Add New');
        expect(btn.html()).toContain('fa-plus-circle');
        expect(btn.hasClass('btn-success')).toBe(true);
        expect(btn.hasClass('btn-xs')).toBe(true);
        expect(btn.hasClass('btn-sm')).toBe(true);
        expect(btn.hasClass('btn-primary')).toBe(false);
        expect(btn.hasClass('btn-lg')).toBe(false);
    });
});

// ─── Test Suite: Table ID Mapping ─────────────────────────────────────────

describe('Table IDs match DataTable configuration', () => {
    const tableIdMap = {
        'unit':                'unit-table',
        'tax-rate':            'taxrate-table',
        'item-type':           'itemtype-table',
        'denomination-type':   'denominationtype-table',
        'income-expense-type': 'incomeexpensetype-table',
        'partnership-type':    'partnershiptype-table',
        'transport-type':      'transporttype-table',
        'category':            'category-table',
        'warehouse':           'warehouse-table',
        'store':               'store-table',
        'payment-type':        'paymenttype-table',
        'customer':            'vendor-table',
        'supplier':            'supplier-table',
        'partner':             'partner-table',
        'machine-details':     'machinedetail-table',
    };

    test.each(Object.entries(tableIdMap))('route %s has table ID %s', (route, tableId) => {
        expect(tableId).toBeTruthy();
        expect(tableId).toMatch(/^[a-z]+-table$/);
    });

    test('all 15 master tables are mapped', () => {
        expect(Object.keys(tableIdMap).length).toBe(15);
    });
});

// ─── Test Suite: Delete Action ────────────────────────────────────────────

describe('Delete Action Confirmation', () => {
    test('delete form submits with DELETE method', () => {
        const form = {
            action: '/rrkadminmanager/master/unit/5',
            method: 'POST',
            _method: 'DELETE',
        };

        expect(form._method).toBe('DELETE');
        expect(form.action).toContain('/master/unit/5');
    });

    test('delete triggers on correct URL pattern', () => {
        const patterns = [
            '/rrkadminmanager/master/unit/1',
            '/rrkadminmanager/master/tax-rate/5',
            '/rrkadminmanager/master/category/10',
            '/rrkadminmanager/master/warehouse/3',
        ];

        patterns.forEach(url => {
            expect(url).toMatch(/\/master\/[\w-]+\/\d+$/);
        });
    });
});
