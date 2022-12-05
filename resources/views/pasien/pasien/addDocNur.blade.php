<x-app-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-6 py-3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h3 class="text-center">Please choose a Doctor or Nurse</h3>
                <form method="POST" action="{{ route('pasien.pasiens.store') }}">
                    @csrf 
                    <div class="pl-28">
                        <!-- onchange="this.form.submit();" -->
                        <x-jet-label for='id' value="{{ __('Doctor or Nurse') }}" />
                        <select name="id" id="id" class="p-4 border-t mr-0 border-b border-l text-black-800 border-blue-200 bg-white sm:rounded-lg">
                            <option value=""> -- Select Doctor or Nurse --</option>
                            @foreach ($addDocNur as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <button class="px-5 sm:rounded-lg bg-yellow-400 text-black-800 font-bold p-4 uppercase border-t border-b border-r" type="submit">
                            {{ __('Add') }}
                        </button>
                        <br></br>
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-2 py-3 sm:rounded-lg" role="alert">
                                <span class="block sm:inline">
                                    <strong class="font-bold">Oops!</strong>
                                    Dobel Entri Data Pasien</span>
                                </div>
                            @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>