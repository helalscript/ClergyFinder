<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration update</title>
</head>
<body style="font-family: sans-serif; line-height: 1.5;">
    <p>Hello {{ $user->name }},</p>
    <p>Your clergy registration was not approved at this time.</p>
    @if(!empty($reason))
        <p><strong>Reason:</strong> {{ $reason }}</p>
    @endif
    <p>If you have questions, please contact support.</p>
</body>
</html>
