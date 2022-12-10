<x-app-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-6 py-3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="mt-4">
                    <p>Doctor or Nurse : Galih handoko</p>
                </div>
            </div>
        </div>
    </div>

    {{-- <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-6 py-3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h3 class="text-center">PCG Monitoring</h3>
                <div id='myDiv'>
                    <!-- Plotly chart will be drawn inside this DIV -->
                </div>
            </div>
        </div>
    </div> --}}

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-6 py-3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- <div class="ostat">
                    <h3 class="">Overall Status</h3>
                    @csrf
                    <p>AS : False</p>
                    <p>MR : False</p>
                    <p>MS : False</p>
                    <p>MVP : False</p>
                    <p>N : True</p>
                </div> -->
                <div class="currstat">
                    <h3 class="">Current Status</h3>
                    @csrf
                    <p>AS : False</p>
                    <p>MR : False</p>
                    <p>MS : False</p>
                    <p>MVP : False</p>
                    <p>N : True</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
