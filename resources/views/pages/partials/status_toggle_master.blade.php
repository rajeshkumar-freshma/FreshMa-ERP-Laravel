<div class="form-check form-switch m-0 d-inline-flex align-items-center justify-content-center">
    <input
        class="form-check-input master-status-toggle"
        {{ (int) ($model->status ?? 0) === 1 ? 'checked' : '' }}
        type="checkbox"
        role="switch"
        data-id="{{ $model->id }}"
        data-entity="{{ $entity }}"
    >
</div>