<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Order Requests</title>
</head>
<body>
    <h1>Supplier Order Requests</h1>

    <div>
        <a href="{{ route('order-request.create') }}">Create Order Request</a>
    </div>

    <div style="margin-top: 16px;">
        {{ $dataTable->table() }}
    </div>

    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
</body>
</html>
