<div id="message-new" class="flex fixed inset-0 justify-center items-center bg-gray-800 bg-opacity-50 z-40 hidden">
    <div class="p-2 bg-white w-[400px] rounded">
        <form action="{{ route('messageNewSend') }}" method="POST">
            @csrf
            <div class="flex items-center justify-between ">
                <h1 class="text-xl font-bold px-2">
                    Send Message
                </h1>

                <button type="button" onclick="document.getElementById('message-new').classList.add('hidden')"
                    class="text-6xl flex font-thin">&times;</button>
            </div>

            <div class="px-2 py-2 space-y-2">
            <div class="flex flex-col items-center">
            <form hx-get="{{ route('searchContact') }}" hx-trigger="input" hx-target="#results" hx-swap="innerHTML"> 
            

            <div class="flex items-center p-2 space-x-2 bg-white border border-gray-300 rounded-full ">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" placeholder="Search contacts" class="w-full focus:outline-none">
       
            </div>
            </form>
            <div class="relative fixed inset-0 z-50" >
           
            </div>
            <div id="results" class="bg-white">

            @include('admin.partials.contacts-list')

    </div>
            </div>
              

            <!-- <div>
                    <input type="hidden" value="{{Auth::user()->name}}" name="sender_name">
                    <label for="">Recipient:</label>
                    <select name="receiver_name" id="" class="block p-2  border border-gray-300 rounded">
                        @foreach ($users as $user)
                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                        @endforeach

                    </select>

                </div> -->

                <div>
                    <label for="">Message:</label>
                    <textarea name="content" id="" placeholder="Input text here..."
                        class="block h-[300px] border border-gray-300 w-full"></textarea>
                </div>

            </div>

            <div class=" flex justify-end space-x-1 p-2">
                <button class="px-3 py-2 bg-blue-200 hover:bg-blue-300 rounded-full">
                    Send
                </button>

                <button type="button" onclick="document.getElementById('message-new').classList.add('hidden')"
                    class="px-3 py-2 bg-gray-100 hover:bg-gray-300 rounded-full">
                    Cancel
                </button>
            </div>

        </form>

    </div>
</div>