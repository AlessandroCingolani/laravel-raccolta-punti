<aside>

    <ul id="link-menu" class="text-start py-3 px-0 d-none d-md-block">
        <li class="my-3">
            <a href="{{ route('dashboard') }}" @class(['active' => Route::is('dashboard')])>
                Home</a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.customers.index') }}" @class([
                'active' =>
                    Route::is('admin.customers.index') ||
                    Route::is('admin.search-customer') ||
                    Route::is('admin.customers.show') ||
                    Route::is('admin.order-by'),
            ])>I Tuoi Clienti
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
            <a href="{{ route('admin.coupons-used') }}" @class(['active' => Route::is('admin.coupons-used')])>Visualizza acquisti con coupon
            </a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.purchases.create') }}" @class(['active' => Route::is('admin.purchases.create')])>Aggiungi acquisto
            </a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.gift_vouchers.create') }}" @class(['active' => Route::is('admin.gift_vouchers.create')])>Aggiungi buono regalo
            </a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.gift_vouchers.index') }}" @class([
                'active' =>
                    Route::is('admin.gift_vouchers.index') ||
                    Route::is('admin.search-gift-customers') ||
                    Route::is('admin.gift-expired') ||
                    Route::is('admin.gift-used') ||
                    Route::is('admin.gift_vouchers.show'),
            ])>Visualizza buoni
                regalo
            </a>
        </li>
    </ul>

    <ul id="link-menu-sm" class="text-center py-3 px-0 d-md-none">
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
            <a href="{{ route('admin.coupons-used') }}" @class(['active' => Route::is('admin.coupons-used')])><i
                    class="fa-solid fa-ticket"></i>
            </a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.purchases.create') }}" @class(['active' => Route::is('admin.purchases.create')])><i
                    class="fa-solid fa-cart-plus"></i>
            </a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.gift_vouchers.create') }}" @class(['active' => Route::is('admin.gift_vouchers.create')])>
                <i class="fa-solid fa-gift"></i><i class="fa-solid fa-plus fs-6"></i>
            </a>
        </li>
        <li class="my-3">
            <a href="{{ route('admin.gift_vouchers.index') }}" @class(['active' => Route::is('admin.gift_vouchers.index')])>
                <i class="fa-solid fa-gift"></i>
            </a>
        </li>
    </ul>

</aside>
