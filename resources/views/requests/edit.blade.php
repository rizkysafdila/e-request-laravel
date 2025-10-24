<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Edit Request
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <form method="POST" action="{{ route('requests.update', $r) }}" enctype="multipart/form-data"
        class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-medium mb-1">Title</label>
          <input type="text" name="title" value="{{ old('title', $r->title) }}"
            class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200" required>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Description</label>
          <textarea name="description" rows="3"
            class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200">{{ old('description', $r->description) }}</textarea>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Request Type</label>
          <select name="request_type"
            class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200" required>
            @foreach (['leave', 'stationery', 'access', 'reimbursement'] as $t)
              <option value="{{ $t }}" @selected($r->request_type == $t)>{{ ucfirst($t) }}
              </option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Attachment</label>
          @if ($r->attachment_path)
            <p class="text-sm mb-2">Current: <a href="{{ Storage::url($r->attachment_path) }}"
                target="_blank" class="text-blue-600 underline">View</a></p>
          @endif
          <input type="file" name="attachment" class="block w-full text-sm text-gray-600">
        </div>

        <x-primary-button>Update</x-primary-button>
      </form>
    </div>
  </div>
</x-app-layout>
