<td class="no-wrap d-flex">
    <div class="btn-group">
        <button id="editButton" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false">
            Action
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('admin.api-keys.edit', $model->id) }}"><i class="fa fa-pencil"></i>
                    Edit</a></li>
        </ul>
    </div>
</td>
