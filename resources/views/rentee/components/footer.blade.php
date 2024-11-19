<style>
    @media(orientation: landscape) {
        #footer {
            display: none;
        }
    }
</style>


<footer id="footer" class="flex justify-around fixed bottom-0 p-4 items-center right-0 left-0 bg-blue-500">

    <a href="{{ route('home', ['rentee' => $rentee]) }}" class="">
        <i class="fas fa-th-large text-white fa-2xl"></i>
    </a>

    <a @if ($cartedProperties != 0) href="{{ route('cart', ['rentee' => $rentee]) }}" @endif>
        <button title="Cart" class="hover:opacity-50 z-40 drop-shadow rounded flex flex-col items-center">
            @if($cartedProperties != 0)
                <span class="absolute bottom-4 left-8 bg-red-500 text-white rounded-full px-[5px] text-xs">
                    {{ $cartedProperties}}
                </span>
            @endif
            <i class="fas fa-shopping-cart fa-2xl text-white"></i>

        </button>
    </a>

    <button onclick="confirmLogoutModal()" title="Log-out">
        <i class="fa-solid fa-right-from-bracket text-white fa-2xl"></i>
    </button>


</footer>