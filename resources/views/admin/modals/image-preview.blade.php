<div id="image-preview-{{$message->id}}" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center z-50">
    <!-- Modal content -->
    <div class="bg-white p-2 rounded shadow-lg">
       <div class="flex items- justify-between m-1">

            <div>
                <h2 class="py-4 text-xl font-bold">Image Preview</h2>
            </div>
            
            <div class="hover:text-gray-200 text-2xl font-bold">
                <button onclick="document.getElementById('image-preview-{{$message->id}}').classList.toggle('hidden')">&times;</button>
            </div>

       </div>

       
       <div class="flex flex-wrap">
        @if($message->img != null)
            <img width="800" src="{{ asset('storage/images/' . $message->img) }}" alt="">
       @endif
       </div>
        
       
    </div>
</div>
