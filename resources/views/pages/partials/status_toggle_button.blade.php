@isset($statuschange)
    <div class="form-check form-switch">
        <input class="form-check-input statuschange" {{ @$model->status == 1 ? 'checked' : '' }} type="checkbox" role="switch" id="flexSwitchCheckStatus" data-warehouse_id="{{ $model->id }}">
    </div>
@endisset

@isset($defaultwarehouse)
    <div class="form-check form-switch">
        <input class="form-check-input defaultwarehouse" {{ @$model->is_default == 1 ? 'checked' : '' }} type="checkbox" role="switch" id="flexSwitchCheckDefault" data-warehouse_id="{{ $model->id }}">
    </div>
@endisset
