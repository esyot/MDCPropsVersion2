<style>
    @media(orientation: landscape) {
        #footer {
            display: none;
        }
    }
</style>


<footer id="footer" class="flex justify-around fixed bottom-0 py-2 items-center right-0 left-0 bg-blue-400">

    <a href="{{ route('home', ['rentee' => $rentee]) }}"
        class="relative hover:opacity-50 z-40 drop-shadow rounded flex flex-col items-center">
        <i class="fas fa-th-large fa-2x text-white mb-1"></i>
        <small class="text-white">Categories</small>
    </a>

    <a @if ($cartedProperties != 0) href="{{ route('cart', ['rentee' => $rentee]) }}" @endif>
        <button title="Cart" class="relative hover:opacity-50 z-40 drop-shadow rounded flex flex-col items-center">
            @if($cartedProperties != 0)
                <span class="absolute bottom-12 left-8 bg-red-500 text-white rounded-full px-[5px] text-xs">
                    {{ $cartedProperties }}
                </span>
            @endif
            <i class="fas fa-shopping-cart fa-2x text-white mb-1"></i>
            <small class="text-white">Cart</small>
        </button>
    </a>

    <button onclick="confirmLogoutModal()" title="Log-out"
        class="relative hover:opacity-50 z-40 drop-shadow rounded flex flex-col items-center">
        <i class="fa-solid fa-right-from-bracket fa-2x text-white mb-1"></i>
        <small class="text-white">Exit</small>
    </button>


</footer>