<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Request Detail
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="mb-4">
            <span
              class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                @if ($r->status === 'draft') bg-gray-200 text-gray-800
                @elseif($r->status === 'submitted') bg-yellow-100 text-yellow-800
                @elseif($r->status === 'approved') bg-green-100 text-green-800
                @elseif($r->status === 'rejected') bg-red-100 text-red-800 @endif">
              {{ ucfirst($r->status) }}
            </span>
          </div>
          <h2 class="text-xl font-semibold mb-2">{{ $r->title }}</h2>
          <p class="text-sm text-gray-600 mb-4">Type: {{ ucfirst($r->request_type) }}</p>
          <p class="mb-4">{{ $r->description }}</p>

          @if ($r->attachment_path)
            <div class="mb-4">
              <a href="{{ Storage::url($r->attachment_path) }}" target="_blank"
                class="text-blue-600 underline">View / Download Attachment</a>
            </div>
          @endif

          <p class="text-sm text-gray-600">Created by: {{ $r->creator->name }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
          <h3 class="text-lg font-semibold mb-4">Activity Log</h3>
          <table class="min-w-full text-sm">
            <thead>
              <tr class="border-b text-gray-500">
                <th class="py-2 text-left">Action</th>
                <th class="py-2 text-left">User</th>
                <th class="py-2 text-left">Note</th>
                <th class="py-2 text-left">Time</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($r->logs as $log)
                <tr class="border-b">
                  <td class="py-2">{{ ucfirst($log->action) }}</td>
                  <td class="py-2">{{ $log->approver->name }}</td>
                  <td class="py-2">{{ $log->note ?? '-' }}</td>
                  <td class="py-2">{{ $log->created_at }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
