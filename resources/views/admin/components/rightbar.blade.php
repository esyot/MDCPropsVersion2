<!-- Sidebar -->
<div id="sidebar-right"
    class="shadow-md fixed top-0 right-0 h-full w-64 bg-white transform translate-x-full z-50 {{ $setting->transition == true ? 'transition-transform duration-[500ms] ease-in-out' : '' }}">
    <div class="p-4">
        <h2 class="text-2xl font-bold">Display Settings</h2>

        <!-- Dark Mode Toggle -->
        <form action="{{ route('darkMode', ['id' => $setting->id]) }}" method="POST">
            @csrf
            <div class="flex items-start mt-4">
                <div
                    class="px-2.5 py-1 m-2 text-4xl {{ $setting->darkMode == true ? 'bg-gray-500' : 'bg-blue-500' }} rounded-full">
                    <div class="fa-solid fa-moon text-white"></div>
                </div>
                <div class="flex flex-col flex-wrap">
                    <p class="font-bold">Dark mode</p>
                    <p>Modify App's appearance to minimize glare and give your eyes some relief.</p>
                    <div class="flex items-center justify-between mt-2">
                        <label class="slider">
                            <input id="dark-mode-slider" type="checkbox" name="action" class="sr-only" {{ $setting->darkMode ? 'checked' : '' }} onchange="this.form.submit()">
                            <div class="slider-track">
                                <div class="slider-thumb"></div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </form>

        <!-- Transitions Toggle -->
        <form action="{{ route('transitions', ['id' => $setting->id]) }}" method="POST">
            @csrf
            <div class="flex items-start mt-4">
                <div
                    class="px-1.5 py-1 m-2 text-4xl {{ $setting->darkMode == true ? 'bg-gray-500' : 'bg-blue-500' }} rounded-full">
                    <div class="text-white fa-solid fa-arrows-left-right"></div>
                </div>
                <div class="flex flex-col flex-wrap">
                    <p class="font-bold">Transitions</p>
                    <p>Add smooth transition animations, even if it results slower system performance.</p>
                    <div class="flex items-center justify-between mt-2">
                        <label class="slider">
                            <input id="transition-slider" type="checkbox" name="action" class="sr-only" {{ $setting->transition ? 'checked' : '' }} onchange="this.form.submit()">
                            <div class="slider-track">
                                <div class="slider-thumb"></div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Toggle Button -->
<div id="toggle-container" class="fixed right-1 bottom-[45%] z-50">
    <button id="open-btn" title="Display Settings"
        class="{{ $setting->transition == true ? 'transition-transform duration-300 ease-in-out transform hover:scale-110' : '' }}  {{ $setting->darkMode == true ? 'bg-gray-500 hover:bg-gray-600' : 'bg-blue-400 hover:bg-blue-300' }} shadow-xl toggle-button px-[13px] py-2  text-white font-bold rounded-full shadow-lg">
        <i id="btn" class="fa-solid fa-arrow-left"></i>
    </button>
</div>

<script>

    document.getElementById('open-btn').addEventListener('click', function () {
        document.getElementById('sidebar-right').classList.toggle('translate-x-full');
        document.getElementById('open-btn').classList.toggle('mr-[260px]');
        document.getElementById('btn').classList.toggle('fa-arrow-left');
        document.getElementById('btn').classList.toggle('fa-arrow-right');
    });

</script>