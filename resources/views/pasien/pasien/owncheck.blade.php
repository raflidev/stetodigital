<x-app-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-6 py-3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h3 class="text-center">Classification Offline</h3>
                <form action="{{url('upload_signal')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if ($message = Session::get('success'))
                    <div class="pl-28 alert alert-success">
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif

                    @if (count($errors) > 0)
                    <div class="pl-28 alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="pl-28">
                        <x-jet-label for="file" value="{{ __('Upload Signal Data') }}" />
                        <input id="file" class="p-4 border-t mr-0 border-b border-l text-black-800 border-blue-200 bg-white sm:rounded-lg" type="file" :value="old('file')" name="file" />
                        <button class="px-5 sm:rounded-lg bg-yellow-400 text-black-800 font-bold p-4 uppercase border-t border-b border-r" type="submit">
                            {{ __('Upload') }}
                        </button>
                    </div>
                </form>
                <!-- <form method="POST" action="{{url('run')}}"> -->
                    <form>
                    @csrf
                    <div class="py-5 pl-28">
                        <x-jet-label for='name' value="{{ __('Select Signal Data') }}" />
                        <select name="name" id="name" class="p-4 border-t mr-0 border-b border-l text-black-800 border-blue-200 bg-white sm:rounded-lg">
                            <option value=""> -- Select Data --</option>
                            @foreach($dataSignal as $data)
                            @if ($data->result == "")
                            <option value="{{ $data->name }}">{{ $data->name }}</option>
                            $else
                            @endif
                            @endforeach
                        </select>
                        <a href="/owncheck/result" class="px-5 sm:rounded-lg bg-yellow-400 text-black-800 font-bold p-4 uppercase border-t border-b border-r" type="submit">
                            {{ __('Run') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
