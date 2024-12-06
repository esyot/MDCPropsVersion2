<a href="{{ route('users') }}"
    class="flex m-2 flex-col bg-white border shadow-md w-[200px] rounded-xl cursor-pointer {{ $setting->transition ? 'transition-transform duration-300 ease-in-out hover:scale-90' : ''}} ">

    <div class="p-2">
        <div class="flex items-center justify-between">
            <span class="text-2xl font-bold">{{ $usersCount }}</span>
            <i class="fas fa-users text-blue-500"></i>
        </div>

        <h1 class="font-medium text-gray-500">Users</h1>
    </div>
    <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
        <h1 class="text-white">View More</h1>
        <i class="fas fa-arrow-right text-white"></i>
    </div>
</a>
<div class="flex m-2 flex-col  w-[200px]">
    <button
        class="bg-white border shadow-md rounded-xl cursor-pointer {{ $setting->transition ? 'transition-transform duration-300 ease-in-out hover:scale-90' : ''}} ">
        <div class="flex flex-col items-start p-2">


            <div class="flex w-full items-center justify-between">
                <span class="text-2xl font-bold">{{ $renteesCount }}</span>
                <i class="fas fa-people-group text-blue-500"></i>
            </div>
            <h1 class="font-medium text-gray-500">Rentees</h1>


        </div>

        <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
            <h1 class="text-white">View More</h1>
            <i class="fas fa-arrow-right text-white"></i>
        </div>
    </button>

</div>

<a href="{{ route('admin.properties') }}"
    class="flex m-2 flex-col bg-white border shadow-md w-[200px] rounded-xl cursor-pointer {{ $setting->transition ? 'transition-transform duration-300 ease-in-out hover:scale-90' : ''}} ">
    <div class="p-2">
        <div class="flex items-center justify-between">
            <span class="text-2xl font-bold">{{ $propertiesCount }}</span>
            <i class="fas fa-boxes text-blue-500"></i>
        </div>

        <h1 class="font-medium text-gray-500">Properties</h1>
    </div>
    <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
        <h1 class="text-white">View More</h1>
        <i class="fas fa-arrow-right text-white"></i>
    </div>
</a>

<a href="{{ route('admin.categories') }}"
    class="flex m-2 flex-col bg-white border shadow-md w-[200px] rounded-xl cursor-pointer {{ $setting->transition ? 'transition-transform duration-300 ease-in-out hover:scale-90' : ''}} ">
    <div class="p-2">
        <div class="flex items-center justify-between">
            <span class="text-2xl font-bold">{{ $categoriesCount }}</span>
            <i class="fas fa-sitemap text-blue-500"></i>
        </div>

        <h1 class="font-medium text-gray-500">Categories</h1>
    </div>
    <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
        <h1 class="text-white">View More</h1>
        <i class="fas fa-arrow-right text-white"></i>
    </div>
</a>
<a href="{{ route('users') }}"
    class="flex m-2 flex-col bg-white border shadow-md w-[200px] rounded-xl cursor-pointer {{ $setting->transition ? 'transition-transform duration-300 ease-in-out hover:scale-90' : ''}} ">
    <div class="p-2">
        <div class="flex items-center justify-between">
            <span class="text-2xl font-bold">{{ $superadminsCount }}</span>
            <i class="fas fa-user-cog text-blue-500"></i>
        </div>

        <h1 class="font-medium text-gray-500">Superadmins</h1>
    </div>
    <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
        <h1 class="text-white px-4">View More</h1>
        <i class="fas fa-arrow-right text-white"></i>
    </div>
</a>

<div
    class="flex m-2 flex-col bg-white border shadow-md w-[200px] rounded-xl cursor-pointer {{ $setting->transition ? 'transition-transform duration-300 ease-in-out hover:scale-90' : ''}} ">
    <div class="p-2">
        <div class="flex items-center justify-between">
            <span class="text-2xl font-bold">{{ $adminsCount }}</span>
            <i class="fas fa-user-tie text-blue-500"></i>
        </div>

        <h1 class="font-medium text-gray-500">Admins</h1>
    </div>
    <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
        <h1 class="text-white">View More</h1>
        <i class="fas fa-arrow-right text-white"></i>
    </div>
</div>

<div
    class="flex m-2 flex-col bg-white border shadow-md w-[200px] rounded-xl cursor-pointer {{ $setting->transition ? 'transition-transform duration-300 ease-in-out hover:scale-90' : ''}} ">
    <div class="p-2">
        <div class="flex items-center justify-between">
            <span class="text-2xl font-bold">{{ $staffsCount }}</span>
            <i class="fas fa-user text-blue-500"></i>
        </div>

        <h1 class="font-medium text-gray-500">Staffs</h1>
    </div>
    <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
        <h1 class="text-white">View More</h1>
        <i class="fas fa-arrow-right text-white"></i>
    </div>
</div>

<div
    class="flex m-2 flex-col bg-white border shadow-md w-[200px] rounded-xl cursor-pointer {{ $setting->transition ? 'transition-transform duration-300 ease-in-out hover:scale-90' : ''}} ">
    <div class="p-2">
        <div class="flex items-center justify-between">
            <span class="text-2xl font-bold">{{ $cashiersCount }}</span>
            <i class="fas fa-user-tag text-blue-500"></i>
        </div>

        <h1 class="font-medium text-gray-500">Cashiers</h1>
    </div>
    <div class="flex justify-between items-center p-2 bg-blue-500 rounded-b-xl">
        <h1 class="text-white">View More</h1>
        <i class="fas fa-arrow-right text-white"></i>
    </div>
</div>