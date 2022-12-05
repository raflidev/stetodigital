<x-app-layout onload=run()>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-6 py-3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h3 class="text-center">Classification Online</h3>
                <button onclick="run()" class="px-5 sm:rounded-lg bg-yellow-400 text-black-800 font-bold p-4 uppercase border-t border-b border-r" type="submit">
                    {{ __('Start Scanning') }}
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
    async function run() {
        console.log('Hello');
        await sleep(3000);
        console.log('World');
        alert('Finish scanning')
        window.location.href='/owncheck';
    }

</script>
