<x-default-layout>
    <!--begin::Card header-->
    @include('pages.partials.form_header', ['header_name' => 'Assign Role TO User Setup'])
    <!--begin::Card header-->
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body--> 
        <div class="card-body pt-6">
            @include('pages.roles._table')
        </div>
    </div>
</x-default-layout>
