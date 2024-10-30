<!DOCTYPE html>
<html>

<head>
    <title>Coupons</title>
    <style>
        /* Stili generali per la card e la struttura */
        .container-fluid {
            width: 100%;
            padding: 0;
            background-color: #f4f8fb;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .card {
            max-width: 400px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: #fff;
            margin: 30px auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 2rem;
            text-align: center;
        }

        .card-title {
            font-size: 1.75rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #fff;
        }

        .card-subtitle {
            font-size: 1.1rem;
            color: #cce1ff;
        }

        .display-4 {
            font-size: 3rem;
            font-weight: 700;
            color: #ffc107;
            margin: 1.5rem 0;
        }

        .card-text {
            font-size: 1rem;
            color: #cce1ff;
            margin-bottom: 1.5rem;
        }


        /* Linea di separazione personalizzata */
        hr {
            margin: 1.5rem 0;
            border: 0;
            height: 1px;
            background: #cce1ff;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="card-title">Ciao {{ $lead->recipient_name . ' ' . $lead->recipient_surname }}</h3>
                <h5 class="card-subtitle mb-2">Hai un totale di:</h5>
                <p class="card-text display-4">{{ $lead->customer_points }} coupons</p>
                <hr>
                <p class="card-text">Grazie per la tua fedeltà!</p>
            </div>
        </div>
    </div>
</body>

</html>
