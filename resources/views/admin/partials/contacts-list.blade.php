@foreach ($users as $user)
       <p class="p-2 hover:bg-gray-300 cursor-pointer" onclick="handleUserClick('{{ $user->id }}', ' {{ $user->name }}');">
                 {{ $user->name }}
       </p>
@endforeach

<script>
       function handleUserClick(userId, userName) {

              document.getElementById('userId').value = userId;
              document.getElementById('userName').innerHTML = userName + '<i onclick="removeRecipient()" class="ml-1 fas fa-times-circle text-red-500 hover:opacity-50"></i>';
              document.getElementById('results').classList.add('hidden');
              document.getElementById('userNameInput').classList.toggle('hidden');
              document.getElementById('searchUserInput').classList.toggle('hidden');
       }
</script>


@if (count($users) == 0)

       <p class="p-2">No match found.</p>


@endif

@if ($value == '')

       <script>
                 document.getElementById('results').classList.add('hidden');
       </script>

@endif