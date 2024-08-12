<!DOCTYPE html>
<html>

<head>
    <title>New Visit Scheduled</title>
</head>

<body>
    <h1>You have a visitor</h1>
    <p>A new visit has been scheduled.</p>
    <p>Dear {{$visit->host->host_name}}, you have an appointment booked with {{ $visit->visitor->full_name }} </p>
</body>

</html>