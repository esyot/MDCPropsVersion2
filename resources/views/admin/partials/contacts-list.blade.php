@foreach ($users as $user)
       <p class="p-2 hover:bg-gray-300" onclick="handleUserClick('{{ $user->id }}', ' {{ $user->name }}');">
                 {{ $user->name }}
       </p>
@endforeach

<script>
       function handleUserClick(userId, userName) {

              document.getElementById('userId').value = userId;
              document.getElementById('userName').value = userName;
              document.getElementById('results').classList.add('hidden');
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