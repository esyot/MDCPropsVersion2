<style>
    .slider {
        position: relative;
        width: 60px;
        height: 32px;
    }

    .slider-track {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: background-color 0.3s;
        border-radius: 9999px;
    }

    .slider-thumb {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 28px;
        height: 28px;
        background-color: white;
        border-radius: 9999px;
        transition: transform 0.3s;
    }

    .slider input:checked+.slider-track {
        background-color: #2196F3;
    }

    .slider input:checked+.slider-track .slider-thumb {
        transform: translateX(26px);
    }
</style>
<div id="sidebar-right"
    class="shadow-md fixed top-0 right-0 w-64 bg-white transform translate-x-full z-50 h-full {{ $setting->transition ? 'transition-transform duration-[500ms] ease-in-out' : '' }} sm:w-56 md:w-64">
    <div class="p-4 flex flex-col flex-grow">
        <h2 class="text-xl sm:text-2xl font-bold">Display Settings</h2>

        <!-- Dark Mode Toggle -->
        <form action="{{ route('darkMode', ['id' => $setting->id]) }}" method="POST">
            @csrf
            <div class="flex items-start mt-4">
                <div
                    class="px-2.5 py-1 m-2 text-3xl {{ $setting->darkMode ? 'bg-gray-500' : 'bg-blue-500' }} rounded-full">
                    <div class="fa-solid fa-moon text-white"></div>
                </div>
                <div class="flex flex-col flex-wrap">
                    <p class="font-bold text-sm sm:text-base">Dark mode</p>
                    <p class="text-xs sm:text-sm">Modify App's appearance to minimize glare and give your eyes some
                        relief.</p>
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
                    class="px-1.5 py-1 m-2 text-3xl {{ $setting->darkMode ? 'bg-gray-500' : 'bg-blue-500' }} rounded-full">
                    <div class="text-white fa-solid fa-arrows-left-right"></div>
                </div>
                <div class="flex flex-col flex-wrap">
                    <p class="font-bold text-sm sm:text-base">Transitions</p>
                    <p class="text-xs sm:text-sm">Add smooth transition animations, even if it results slower system
                        performance.</p>
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
<div id="toggle-container" class="fixed right-1 bottom-[45%] z-40">
    <button id="open-btn" title="Display Settings"
        class="hover:opacity-50  rounded-full {{ $setting->darkMode ? 'bg-black text-white' : 'bg-white text-blue-500' }}  shadow-md  toggle-button font-bold rounded-full">
        <i id="btn" class=" fas fa-chevron-circle-left text-[40px]"></i>
    </button>
</div>

<script>
    const rightbar = document.getElementById('sidebar-right');
    const toggleBtn = document.getElementById('open-btn');
    const buttonIcon = document.getElementById('btn');

    toggleBtn.addEventListener('click', function () {
        rightbar.classList.toggle('translate-x-full');
        toggleBtn.classList.toggle('mr-[260px]');
        buttonIcon.classList.toggle('fa-chevron-circle-left');
        buttonIcon.classList.toggle('fa-chevron-circle-right');
    });

    function closeSidebar() {
        rightbar.classList.add('translate-x-full');
        toggleBtn.classList.remove('mr-[260px]');
        buttonIcon.classList.remove('fa-chevron-circle-right');
        buttonIcon.classList.add('fa-chevron-circle-left');
    }

    document.addEventListener('click', function (event) {
        // Check if the click is outside the sidebar and toggle button
        if (!rightbar.contains(event.target) && !toggleBtn.contains(event.target)) {
            closeSidebar();
        }
    });
</script>