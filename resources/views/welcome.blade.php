<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard</title>
    <style>
        body {
            background: #f8f9fa;
            color: #333;
            font-family: sans-serif;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        tr:nth-child(even) {
            background: #f1f1f1;
        }

        tr.highlight {
            background: #d1e7dd;
            font-weight: bold;
        }

        form {
            margin-bottom: 15px;
        }

        input, select, button {
            padding: 6px 10px;
            font-size: 14px;
            margin-right: 5px;
        }

        button {
            background: #e0e0e0;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        button:hover {
            background: #d0d0d0;
        }
    </style>
</head>
<body>

    <form method="POST" action="{{ route('leaderboard.recalculate') }}">
        @csrf
        <button type="submit">Recalculate</button>
    </form>

    <form method="GET" action="{{ route('leaderboard.index') }}">
        <label>User ID:</label>
        <input type="text" name="filter" value="{{ request('filter') }}" />
        <button type="submit">Filter</button>
    </form>

    <form method="GET" action="{{ route('leaderboard.index') }}">
        <label>Sort by:</label>
        <select name="sort_by">
            <option value="">--</option>
            <option value="day" {{ $sort_by === 'day' ? 'selected' : '' }}>Day</option>
            <option value="month" {{ $sort_by === 'month' ? 'selected' : '' }}>Month</option>
            <option value="year" {{ $sort_by === 'year' ? 'selected' : '' }}>Year</option>
        </select>
        <button type="submit">Sort</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Points</th>
                <th>Rank</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr class="{{ $filter == $user->id ? 'highlight' : '' }}">
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $pointsMap[$user->id] }}</td>
                <td>#{{ $user->rank }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</body>
</html>
