@if($child_category->getChildrenCategory)
    @foreach($child_category->getChildrenCategory as $key => $childcat)
        <option value="{{ $childcat->id }}" {{ (@$childcat->id == old('parent_id', @$category->parent_id))?'selected':'' }}>|---{{ ucFirst($childcat->name) }}</option>
        @include('pages.master.category.child_category',['child_category' => $childcat])
    @endforeach
@endif
