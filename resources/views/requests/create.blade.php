<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Create Request
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <form method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data"
        class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-medium mb-1">Title</label>
          <input type="text" name="title"
            class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200" required>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Description</label>
          <textarea name="description" rows="3"
            class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200"></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Request Type</label>
          <select name="request_type"
            class="w-full rounded border-gray-300 focus:ring focus:ring-blue-200" required>
            <option value="">-- choose --</option>
            @foreach (['leave', 'stationery', 'access', 'reimbursement'] as $t)
              <option value="{{ $t }}">{{ ucfirst($t) }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Attachment (.pdf, .jpg, .png)</label>
          <input type="file" name="attachment" class="block w-full text-sm text-gray-600">
        </div>

        <x-primary-button>Save Draft</x-primary-button>
      </form>
    </div>
  </div>
</x-app-layout>
