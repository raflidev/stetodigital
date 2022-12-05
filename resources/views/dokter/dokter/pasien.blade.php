<x-app-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-6 py-3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h3 class="text-center">Patient Profile</h3>
                @foreach ($readpasien as $pasien)
                <div class="mt-4">
                    <p>Patient Name : {{$pasien->name}}</p>
                </div>
                <div class="mt-4">
                    <p>Gender : {{$pasien->gender}}</p>
                </div>
                <div class="mt-4">
                    <p>Address : {{$pasien->address}}</p>
                </div>
                <div class="mt-4">
                    <p>Phone Number : {{$pasien->phonenumber}}</p>
                </div>
                <div class="mt-4">
                    <p>Email : {{$pasien->email}}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</x-app-layout>