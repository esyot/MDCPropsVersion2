@foreach ($users as $user)
       <option value="{{ $user->name }}">{{ $user->name }}</option>
@endforeach