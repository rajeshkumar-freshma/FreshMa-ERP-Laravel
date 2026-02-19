<div class="row mb-2">
    <div class="col-md-1">
        <select name="paginate" id="paginate_dropdown" class="form-select form-select-sm form-select-solid">
            <option value="10" {{ Request::get("paginate") == 10 ? "selected" : ""}}>10</option>
            <option value="25" {{ Request::get("paginate") == 25 ? "selected" : ""}}>25</option>
            <option value="50" {{ Request::get("paginate") == 50 ? "selected" : ""}}>50</option>
            <option value="100" {{ Request::get("paginate") == 100 ? "selected" : ""}}>100</option>
            <option value="500" {{ Request::get("paginate") == 500 ? "selected" : ""}}>500</option>
        </select>
    </div>
</div>
