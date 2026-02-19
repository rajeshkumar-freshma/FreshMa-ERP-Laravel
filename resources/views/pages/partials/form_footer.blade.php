<div class="card mt-2">
    <div class="card-footer d-flex justify-content-end py-6 px-9">
        <a href="{{ $back_url }}"><button type="button" class="btn btn-sm btn-danger btn-active-light-primary me-2">{{ __('Go Back') }}</button></a>

        @if (!isset($show_reset))
            <button type="reset" class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Reset') }}</button>
        @endif

        @if (!isset($show_save))
            <button type="submit" class="btn btn-sm btn-primary me-2" id="item_details_submit" name="submission_type" value={{ config('app.submission_type')[0]['value'] }}>
                @include('partials.general._button-indicator', ['label' => __(config('app.submission_type')[0]['name'])])
            </button>
        @endif
        @if ($is_save)
            <button type="submit" class="btn btn-sm btn-success me-2" id="item_details_submit" name="submission_type" value={{ config('app.submission_type')[1]['value'] }}>
                @include('partials.general._button-indicator', ['label' => __(config('app.submission_type')[1]['name'])])
            </button>
        @endif
        @if (isset($is_bulk_product_transfer) && $is_bulk_product_transfer)
            <button type="submit" class="btn btn-sm btn-info" id="distribution_submit" name="submission_type" value='distribution_submit'>
                @include('partials.general._button-indicator', ['label' => __('Convert to Distribution')])
            </button>
        @endif
    </div>
</div>
