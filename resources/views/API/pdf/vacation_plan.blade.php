<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vacation Plan</title>
    <style>
        body {
            font-family: 'Helvetica Neue', sans-serif;
            color: #333;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            border-top: 8px solid #980000;
        }

        header {
            text-align: center;
            margin-bottom: 40px;
            background: #b0bec5;
            color: #000000;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            position: relative;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        header h1 {
            font-size: 36px;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        header .plan-title {
            font-size: 24px;
            font-weight: 300;
        }

        header .plan-id {
            font-size: 16px;
            font-weight: 600;
            color: #000000;
        }

        .details {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .detail-item {
            flex: 1 1 30%;
            margin-bottom: 20px;
        }

        .detail-item h2 {
            font-size: 20px;
            color: #980000;
            margin-bottom: 8px;
            border-bottom: 2px solid #f2f2f2;
            padding-bottom: 5px;
        }

        .detail-item p {
            font-size: 16px;
            color: #666;
        }

        .participants {
            margin-top: 20px;
        }

        .participants h2 {
            font-size: 24px;
            color: #980000;
            margin-bottom: 15px;
            border-bottom: 3px solid #980000;
            padding-bottom: 5px;
        }

        .participants ul {
            list-style-type: none;
            padding-left: 0;
        }

        .participants li {
            font-size: 18px;
            background: #f2f2f2;
            color: #333;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

    </style>

</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <h1>Vacation Plan</h1>
                <p class="plan-title">{{ $vacationPlan->title }}</p>
                <p class="plan-id">Plan ID: {{ $vacationPlan->id }}</p>
            </div>
        </header>
        <section class="details">
            <div class="detail-item">
                <h2>Description</h2>
                <p>{{ $vacationPlan->description }}</p>
            </div>
            <div class="detail-item">
                <h2>Date</h2>
                <p>{{ $vacationPlan->date->format('Y-m-d') }}</p>
            </div>
            <div class="detail-item">
                <h2>Location</h2>
                <p>{{ $vacationPlan->location }}</p>
            </div>
        </section>
        <section class="participants">
            <h2>Participants</h2>
            <ul>
                @foreach ($vacationPlan->participants as $participant)
                    <li>{{ $participant }}</li>
                @endforeach
            </ul>
        </section>
    </div>
</body>

</html>
