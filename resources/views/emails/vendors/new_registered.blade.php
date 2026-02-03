<!DOCTYPE html>
<html>
<head>
    <title>New Vendor Registration</title>
</head>
<body>
    <h1>New Vendor Registered</h1>
    <p>A new vendor has registered on the platform.</p>
    <p><strong>Legal Name:</strong> {{ $vendor->legal_name }}</p>
    <p><strong>Email:</strong> {{ $vendor->email }}</p>
    <p><strong>Phone:</strong> {{ $vendor->phone }}</p>
    <p><strong>Store Name:</strong> {{ $vendor->profile->store_name }}</p>
    <p>Please login to the admin panel to review and approve verification.</p>
</body>
</html>
