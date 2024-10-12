<div class="flex justify-center items-center h-screen"> <!-- Changed h-full to h-screen -->
    <div class="flex flex-col items-center">
        <h1 class="text-2xl border-2 border-red-500 p-2 bg-white drop-shadow-md">
            You have not yet been assigned a category to manage.
        </h1>
        <div class="flex space-x-1 mt-2"> <!-- Added mt-2 for spacing -->
            <p>Please contact the administrator,</p>
            <button onclick="document.getElementById('message-new').classList.remove('hidden')"
                class="hover:underline text-blue-500">click here.</button>
        </div>
    </div>
</div>