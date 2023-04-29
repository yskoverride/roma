<div class="lg:col-start-3 lg:row-end-1 px-12 py-6">
  <h2 class="sr-only">Summary</h2>
  <div class="rounded-lg bg-gray-50 shadow-sm ring-1 ring-gray-900/5">

    <dl class="flex flex-wrap">
      <div class="flex-auto pl-6 pt-6">
        <dt class="text-sm font-semibold leading-6 text-gray-900">Doctor</dt>
        <dd class="mt-1 text-base font-semibold leading-6 text-gray-900">{{ucwords($appointment->doctor->name) }}</dd>
      </div>
      <div class="flex-none self-end px-6 pt-4">
        <dt class="sr-only">Status</dt>
        <dd class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Paid</dd>
      </div>
      <div class="mt-6 flex w-full flex-none gap-x-4 border-t border-gray-900/5 px-6 pt-6">
        <dt class="flex-none">
          <span class="sr-only">Client</span>
          <svg class="h-6 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A6.483 6.483 0 0010 16.5a6.483 6.483 0 004.793-2.11A5.99 5.99 0 0010 12z" clip-rule="evenodd" />
          </svg>
        </dt>
        <dd class="text-sm font-medium leading-6 text-gray-900"> {{ ucwords($appointment->patient->name) }}</dd>
      </div>
      <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
        <dt class="flex-none">
          <span class="sr-only">Service</span>
          <svg class="h-6 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
          </svg>
        </dt>
        <dd class="text-sm leading-6 text-gray-500">
          <time datetime="{{ $appointment->date }}">{{ ucwords($appointment->service->name) }} ({{ $appointment->service->duration }} session mins)</time>
        </dd>
      </div>

      <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
        <dt class="flex-none">
          <span class="sr-only">Due date</span>
          <svg class="h-6 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M5.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H6a.75.75 0 01-.75-.75V12zM6 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H6zM7.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H8a.75.75 0 01-.75-.75V12zM8 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H8zM9.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V10zM10 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H10zM9.25 14a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H10a.75.75 0 01-.75-.75V14zM12 9.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V10a.75.75 0 00-.75-.75H12zM11.25 12a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H12a.75.75 0 01-.75-.75V12zM12 13.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V14a.75.75 0 00-.75-.75H12zM13.25 10a.75.75 0 01.75-.75h.01a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75H14a.75.75 0 01-.75-.75V10zM14 11.25a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 00.75-.75V12a.75.75 0 00-.75-.75H14z" />
            <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
          </svg>
        </dt>
        <dd class="text-sm leading-6 text-gray-500 {{ $appointment->isCancelled() ? 'line-through' : '' }}">
          <time datetime="{{ $appointment->date }}">{{ $appointment->date->format('D jS M Y') }} at {{ $appointment->start_time->format('g:i A') }} till {{ $appointment->end_time->format('g:i A') }}</time>
        </dd>
      </div>
      <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
        <dt class="flex-none">
          <span class="sr-only">Status</span>
          <svg class="h-6 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M2.5 4A1.5 1.5 0 001 5.5V6h18v-.5A1.5 1.5 0 0017.5 4h-15zM19 8.5H1v6A1.5 1.5 0 002.5 16h15a1.5 1.5 0 001.5-1.5v-6zM3 13.25a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zm4.75-.75a.75.75 0 000 1.5h3.5a.75.75 0 000-1.5h-3.5z" clip-rule="evenodd" />
          </svg>
        </dt>
        <dd class="text-sm leading-6 text-gray-500">Paid with MasterCard</dd>
      </div>
    </dl>
    <div class="mt-6 border-t border-gray-900/5 px-6 py-6 flex justify-between items-center">
      <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Download receipt <span aria-hidden="true">&rarr;</span></a>
      
      @if(!$appointment->isCancelled())
        <a  wire:click="cancelAppointment" href="#" class="flex flex-column justify-between text-sm font-semibold leading-6 text-gray-900">
        <svg class="h-6 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>  
        <span class="text-red-600 ml-2">Cancel Booking</span>
        </a>
      @endif

      @if($appointment->isCancelled())
        <p href="#" class="flex flex-column justify-between text-sm font-semibold leading-6 text-gray-900">
        <span class="text-red-500 ml-2 text-2xl">Cancelled</span>
        </p>
      @endif

    </div>
  </div>
</div>