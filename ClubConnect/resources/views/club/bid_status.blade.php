<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bid Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .accepted {
            background-color: #c8e6c9;

        }
        .declined {
    background-color: #FFD2D2; /* Light red for declined rows */
}


    </style>
</head>
<body>
<h1>Bid Status</h1>
<table>
    <thead>
        <tr>
            <th>Player Name</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($bids as $bid)
        <tr class="{{ $bid->is_declined ? 'declined' : ($bid->is_accepted ? 'accepted' : 'pending') }}">
            <td>{{ $bid->player->name }}</td>
            <td>
                @if ($bid->is_declined)
                    Declined
                @elseif ($bid->is_accepted)
                    Accepted
                @else
                    Pending
                @endif
            </td>
        </tr>
    @endforeach
</tbody>


</table>
</body>
</html>
