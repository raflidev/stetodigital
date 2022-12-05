<x-app-layout>
    <div class="py-3">
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                <h3 class="text-center">Classification Offline</h3>
                                <div class="mt-4 ml-4">
                                    @foreach ($readpasien as $pasien)
                                    <p>Patient Name : {{$pasien->name}}</p>
                                    @endforeach
                                </div>

                                <div class="ml-4 py-5">
                                    <form action="{{url('uploadSignal/'.$user_id)}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @if ($message = Session::get('success'))
                                        <div class="alert alert-success">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @endif

                                        @if (count($errors) > 0)
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif

                                        <div>
                                            <x-jet-label for="file" value="{{ __('Upload Signal Data') }}" />
                                            <input id="file" class="p-4 border-t mr-0 border-b border-l text-black-800 border-blue-200 bg-white sm:rounded-lg" type="file" :value="old('file')" name="file" />
                                            <button class="px-5 sm:rounded-lg bg-yellow-400 text-black-800 font-bold p-4 uppercase border-t border-b border-r" type="submit">
                                                {{ __('Upload') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>


                                <thead class="py-4">
                                    <tr>
                                        <th scope="col" width="400" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Filename
                                        </th>
                                        <th scope="col" width="400" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Type
                                        </th>
                                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">

                                    @foreach ($readDataML as $data)
                                    <tr>
                                        <td scope="col" width="400" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{$data->name}}
                                        </td>
                                        <td scope="col" width="400" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">

                                        </td>
                                        <td scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="dropdown">
                                                <div class="dropdown"> 
                                                    @if ($data->result == "")
                                                    <button type="button" class="bg-gray-800 text-gray-0 font-semibold py-2 px-6 rounded inline-flex items-center"><a href="{{route('dokter.dokter.resultOffline', $data->id)}}">Run</a></button>
                                                    @else
                                                    <button type="button" class="bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded inline-flex items-center"><a href="{{route('dokter.dokter.result', $data->id)}}">Result</a></button>
                                                    @endif

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</x-app-layout>