<div class="container-fluid">
    <div class="card shadow-sm" style="width: 18rem;">
        <div class="card-body">
            <h3 class="card-title text-primary">Ciao {{ $lead->name }}</h3>
            <h5 class="card-subtitle mb-2 text-muted">Hai un totale di:</h5>
            <p class="card-text display-4 text-center text-danger">{{ $lead->customer_points }} coupons</p>
            <hr>
            <p class="card-text text-secondary">Grazie per la tua fedeltà!</p>
        </div>
    </div>
</div>
