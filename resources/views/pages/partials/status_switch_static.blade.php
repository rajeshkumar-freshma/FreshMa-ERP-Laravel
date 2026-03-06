<div class="form-check form-switch m-0 d-inline-flex align-items-center justify-content-center">
    <input class="form-check-input" {{ (int) ($status ?? 0) === 1 ? 'checked' : '' }} type="checkbox" role="switch" disabled>
</div>
