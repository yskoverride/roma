<x-admin-app-layout>
    <div class="bg-gray-900 py-8">
    <div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h2 class="text-2xl text-white font-bold mb-6">Customers List</h2>
        <p class="mt-2 text-sm text-white">A list of all your customers.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('super-admin.tenants.create') }}" class="block rounded-md bg-teal-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-teal-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-teal-600">Add New Customer</a>
    </div>
    </div>


    <table class="mt-6 w-full whitespace-nowrap text-left">
        <colgroup>
        <col class="w-full sm:w-4/12">
        <col class="lg:w-4/12">
        <col class="lg:w-2/12">
        <col class="lg:w-1/12">
        <col class="lg:w-1/12">
        </colgroup>
        <thead class="border-b border-white/10 text-sm leading-6 text-white">
        <tr>
            <th scope="col" class="py-2 pl-4 pr-8 font-semibold sm:pl-6 lg:pl-8">Customer</th>
            <th scope="col" class="hidden py-2 pl-0 pr-8 font-semibold sm:table-cell">Domain</th>
            <th scope="col" class="py-2 pl-0 pr-4 text-right font-semibold sm:pr-8 sm:text-left lg:pr-20">Status</th>
            <th scope="col" class="hidden py-2 pl-0 pr-8 font-semibold md:table-cell lg:pr-20">Last checked</th>
            <th scope="col" class="hidden py-2 pl-0 pr-4 text-right font-semibold sm:table-cell sm:pr-6 lg:pr-8">Last deployed</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
        @foreach($domains as $domain)
            <tr>
                <td class="py-4 pl-4 pr-8 sm:pl-6 lg:pl-8">
                <div class="flex items-center gap-x-4">
                    <div class="truncate text-sm font-medium leading-6 text-white">{{ $domain->tenant_id }}</div>
                </div>
                </td>
                <td class="hidden py-4 pl-0 pr-4 sm:table-cell sm:pr-8">
                <div class="flex gap-x-3">
                    <div class="font-mono text-sm leading-6 text-gray-400">
                        <a href="{{ 'http://'. $domain->domain }}" target="_blank" rel="noopener noreferrer">https://{{ $domain->domain }}</a>
                    </div>
                </div>
                </td>
                <td class="py-4 pl-0 pr-4 text-sm leading-6 sm:pr-8 lg:pr-20">
                <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                    <time class="text-gray-400 sm:hidden" datetime="2023-01-23T11:00">{{$domain->created_at->diffForHumans() }}</time>
                    <div class="flex-none rounded-full p-1 text-green-400 bg-green-400/10">
                    <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                    </div>
                    <div class="hidden text-white sm:block">Active</div>
                </div>
                </td>
                <td class="hidden py-4 pl-0 pr-8 text-sm leading-6 text-gray-400 md:table-cell lg:pr-20">25s</td>
                <td class="hidden py-4 pl-0 pr-4 text-right text-sm leading-6 text-gray-400 sm:table-cell sm:pr-6 lg:pr-8">
                <time datetime="2023-01-23T11:00">{{ $domain->created_at->diffForHumans()}}</time>
                </td>
            </tr>
        @endforeach
        
        </tbody>
    </table>

        <div class="text-white mt-2 p-2">
            {{ $domains->links() }}
        </div>
        
    </div>
</x-admin-app-layout>