<!DOCTYPE html>
<html>
<head>
    <title>Vendor Update Notification</title>
</head>
<body>
    <h1>Vendor Account Updated</h1>
    <p>The vendor <strong>{{ $vendor->legal_name }}</strong> has updated their <strong>{{ $updateType }}</strong>.</p>
    <p>Please log in to the admin panel to review and verify the changes.</p>
    <p>
        <a href="{{ route('admin.vendors.show', $vendor->id) }}">Click here to view Vendor Details</a>
    </p>
</body>
</html>
