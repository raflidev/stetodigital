<x-app-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-6 py-3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="mt-4">
                    @foreach ($username as $nameuser)
                    <p>Patient Name : {{$nameuser->name}}</p>
                    @endforeach
                </div>
                <div class="mt-4">
                    <p>Filename : {{$namafile}}</p>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-6 py-3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h3 class="text-center">PCG Monitoring</h3>
                <div id='myDiv'>
                    <!-- Plotly chart will be drawn inside this DIV -->
                </div>
                <script>
                    let time = <?php echo json_encode($x); ?>;
                    let signal = <?php echo json_encode($y); ?>;
                    let a = time.match(/-?\d+(\.\d+)?/g);
                    let b = signal.match(/-?\d+(\.\d+)?/g);
                    var data = [{
                        x: a,
                        y: b,
                        type: 'Scatter'
                    }];
                    var layout = {
                        autosize: false,
                        width: 1168,
                        height: 325,
                        margin: {
                            l: 50,
                            r: 50,
                            b: 35,
                            t: 35,
                            pad: 4
                        },
                        xaxis: {
                            title: {
                                text: 'Time',
                            },
                        },
                        yaxis: {
                            title: {
                                text: 'Amplitude',
                            },
                        },
                    };

                    Plotly.newPlot('myDiv', data, layout);
                </script>
            </div>
        </div>
    </div>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-6 py-3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- <div class="ostat">
                    <h3 class="">Overall Status</h3>
                    @csrf
                    <p>AS : {{$as}}</p>
                    <p>MR : {{$mr}}</p>
                    <p>MS : {{$ms}}</p>
                    <p>MVP : {{$mvp}}</p>
                    <p>N : {{$n}}</p>
                </div> -->
                <div class="currstat">
                    <h3 class="">Current Status</h3>
                    @csrf
                    <p>AS : {{$as}}</p>
                    <p>MR : {{$mr}}</p>
                    <p>MS : {{$ms}}</p>
                    <p>MVP : {{$mvp}}</p>
                    <p>N : {{$n}}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>