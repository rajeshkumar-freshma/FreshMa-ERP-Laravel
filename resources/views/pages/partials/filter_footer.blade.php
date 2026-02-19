<div class="card-footer d-flex justify-content-end py-6 px-9">
    <button type="submit" class="btn btn-sm btn-primary me-2" id="item_details_submit">
        @include('partials.general._button-indicator', ['label' => __("Search")])
    </button>
    <a href="{{ $clear_url }}"><button type="button" class="btn btn-sm btn-danger btn-active-light-primary me-2">{{ __('Clear') }}</button></a>
</div>