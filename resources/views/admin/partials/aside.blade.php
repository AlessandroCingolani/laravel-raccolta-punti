<aside>

    <ul id="link-menu" class="text-start py-3 px-4 d-none d-md-block">
        <li class="my-3">
            <a href="{{ route('dashboard') }}" @class(['active' => Route::is('dashboard')])>
                Home</a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.customers.index') }}" @class(['active' => Route::is('admin.customers.index')])>I Tuoi Clienti
            </a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.customers.create') }}" @class(['active' => Route::is('admin.customers.create')])>Aggiungi Cliente
            </a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.purchases.index') }}" @class(['active' => Route::is('admin.purchases.index')])>Visualizza tutti gli acquisti
            </a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.purchases.create') }}" @class(['active' => Route::is('admin.purchases.create')])>Aggiungi acquisto
            </a>
        </li>

    </ul>

    <ul id="link-menu-sm" class="text-center py-3 px-2 d-md-none">
        <li class="my-3">
            <a href="{{ route('dashboard') }}" @class(['active' => Route::is('dashboard')])>
                <i class="fa-solid fa-house"></i></a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.customers.index') }}" @class(['active' => Route::is('admin.customers.index')])><i
                    class="fa-solid fa-user-group"></i>
            </a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.customers.create') }}" @class(['active' => Route::is('admin.customers.create')])><i
                    class="fa-solid fa-user-plus"></i>
            </a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.purchases.index') }}" @class(['active' => Route::is('admin.purchases.index')])><i
                    class="fa-solid fa-cart-shopping"></i>
            </a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.purchases.create') }}" @class(['active' => Route::is('admin.purchases.create')])><i
                    class="fa-solid fa-cart-plus"></i>
            </a>
        </li>

    </ul>

</aside>
