<!DOCTYPE html>
<html>

<head>
    <title>Coupons</title>
    <style>
        .container-fluid {
            width: 100%;
            margin-right: auto;
            margin-left: auto;
        }

        .card {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.25rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            width: 18rem;
            margin: auto;
        }

        .card-body {
            -webkit-box-flex: 1;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            padding: 1.25rem;
        }

        .card-title {
            margin-bottom: 0.75rem;
            font-size: 1.5rem;
            color: #007bff;
        }

        .card-subtitle {
            margin-top: -0.375rem;
            margin-bottom: 0.5rem;
            font-size: 1rem;
            color: #6c757d;
        }

        .card-text {
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1rem;
            color: #6c757d;
        }

        .display-4 {
            font-size: 2.5rem;
            font-weight: 300;
            line-height: 1.2;
            color: #dc3545;
        }

        .text-center {
            text-align: center;
        }

        .text-primary {
            color: #007bff !important;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .text-secondary {
            color: #6c757d !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        hr {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-primary">Ciao {{ $lead->recipient_name . ' ' . $lead->recipient_surname }}
                </h3>
                <h5 class="card-subtitle mb-2 text-muted">CORRI DA NOI SOLO OGGI IL 20% DI SCONTO</h5>
                <p class="card-text display-4  text-danger">{{ $lead->type }}</p>
                <hr>
                <p class="card-text text-secondary">Grazie per la tua fedeltà!</p>
            </div>
        </div>
    </div>
</body>

</html>
