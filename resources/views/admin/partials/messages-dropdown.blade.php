<div id="messages-loader"
    class="rounded bg-gray-400 bg-opacity-50 absolute inset-0 flex items-center justify-center hidden">
    <img src="{{asset('asset/loader/loading.gif')}}" alt="Loading..." class="w-16 h-16">
</div>


<div class="p-2">
    <div class="flex justify-between items-center">
        <div class="py-2">
            <h1 id="title" class="text-2xl font-bold">Chats</h1>
        </div>


        <div class="flex">
            <div title="Options">
                <i class="fa-solid fa-ellipsis hover:bg-gray-300 px-[10px] py-[9px] rounded-full"></i>
            </div>
            <div title="See all in messages">
                <a href="{{ route('messages') }}" class=" font-medium  hover:underline py-2">

                    <i class="fas fa-expand-arrows-alt hover:bg-gray-300 px-[10px] py-[9px] rounded-full"></i>
                </a>

            </div>
            <div title="New message" onclick="document.getElementById('message-new').classList.remove('hidden')">
                <i class="fa-solid fa-edit hover:bg-gray-300 px-[10px] py-[9px] rounded-full"></i>

            </div>
        </div>

    </div>


    <form hx-get="{{ route('contacts') }}" hx-trigger="input" hx-swap="innerHTML" hx-target="#contact-list"
        class="flex justiify-around px-2 items-center bg-gray-200 rounded-full">

        <div class="p-2">
            <div class="fas fa-search text-black"></div>
        </div>

        <input type="text" name="searchValue" placeholder="Search contact"
            class="mr-8 focus:outline-none bg-transparent">
    </form>
</div>
<div class="p-2 overflow-y-auto custom-scrollbar">

    <ul id="contact-list" class="list-none">
        @include('admin.partials.contact-list')
    </ul>



</div>
<div class="flex justify-center bg-gray-200 w-full rounded-b-lg">
    <a href="{{ route('messages') }}" class="text-blue-500 font-medium  hover:underline py-2">See
        all in Messages</a>
</div>