<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Request List
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      {{-- Header --}}
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
            @if (auth()->user()->isRequestor())
                My Requests
            @else
                All Requests
            @endif
        </h2>

        <div class="flex items-center gap-3">
          {{-- Admin Filter --}}
          @if (auth()->user()->isAdmin())
            <form method="GET" action="{{ route('requests.index') }}"
              class="flex items-center gap-2">
              <select name="show" onchange="this.form.submit()"
                class="border-gray-300 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500">
                <option value="">Active Requests</option>
                <option value="deleted" @selected(request('show') === 'deleted')>Deleted Requests</option>
              </select>
            </form>
          @endif

          {{-- New Request --}}
          <a href="{{ route('requests.create') }}"
            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors duration-200">
            New Request
          </a>
        </div>
      </div>

      {{-- Table --}}
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  #</th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Title</th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Type</th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status</th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Created</th>
                <th
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Action</th>
              </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
              @forelse($requests as $r)
                <tr
                  class="{{ $r->deleted_at ? 'bg-gray-50 text-gray-400' : 'hover:bg-gray-50 transition-colors duration-150' }}">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $r->id }}
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <a href="{{ route('requests.show', $r) }}"
                      class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                      {{ $r->title }}
                    </a>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ ucfirst($r->request_type) }}
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                        @if ($r->status === 'draft') bg-gray-200 text-gray-800
                        @elseif($r->status === 'submitted') bg-yellow-100 text-yellow-800
                        @elseif($r->status === 'approved') bg-green-100 text-green-800
                        @elseif($r->status === 'rejected') bg-red-100 text-red-800 @endif">
                      {{ ucfirst($r->status) }}
                    </span>
                    @if ($r->deleted_at)
                      <span class="ml-2 text-xs text-red-600">(Deleted)</span>
                    @endif
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ $r->created_at->format('d M Y') }}
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end items-center gap-3">

                      {{-- DRAFT ACTIONS --}}
                      @can('request.manage', $r)
                        @if (!$r->deleted_at && $r->status === 'draft')
                          {{-- Edit --}}
                          <a href="{{ route('requests.edit', $r) }}"
                            class="text-blue-600 hover:text-blue-800 hover:underline">
                            Edit
                          </a>

                          {{-- Submit Modal Trigger --}}
                          <button x-data
                            x-on:click.prevent="$dispatch('open-modal', 'submit-{{ $r->id }}')"
                            class="text-green-600 hover:text-green-800 hover:underline">
                            Submit
                          </button>

                          {{-- Submit Modal --}}
                          <x-modal name="submit-{{ $r->id }}" focusable>
                            <form method="POST" action="{{ route('requests.submit', $r) }}"
                              class="p-6">
                              @csrf
                              <h2 class="text-lg font-medium text-gray-900">Submit Request</h2>
                              <p class="mt-1 text-sm text-gray-600">
                                Are you sure you want to submit <strong>{{ $r->title }}</strong>?
                                Once submitted, it cannot be edited.
                              </p>

                              <div class="mt-6 flex justify-end">
                                <x-secondary-button
                                  x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                <x-primary-button class="ms-3">Submit</x-primary-button>
                              </div>
                            </form>
                          </x-modal>
                        @endif

                        {{-- Delete Modal Trigger --}}
                        @if (!$r->deleted_at)
                          <button x-data
                            x-on:click.prevent="$dispatch('open-modal', 'delete-{{ $r->id }}')"
                            class="text-red-600 hover:text-red-800 hover:underline">
                            Delete
                          </button>

                          {{-- Delete Modal --}}
                          <x-modal name="delete-{{ $r->id }}" focusable>
                            <form method="POST" action="{{ route('requests.destroy', $r) }}"
                              class="p-6">
                              @csrf
                              @method('DELETE')

                              <h2 class="text-lg font-medium text-gray-900">Delete Request</h2>
                              <p class="mt-1 text-sm text-gray-600">
                                This request will be moved to the Trash.
                                Do you really want to continue?
                              </p>

                              <div class="mt-6 flex justify-end">
                                <x-secondary-button
                                  x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                <x-danger-button class="ms-3">Delete</x-danger-button>
                              </div>
                            </form>
                          </x-modal>
                        @endif
                      @endcan

                      {{-- RESTORE (Admin only) --}}
                      @can('request.restore', $r)
                        <button x-data
                          x-on:click.prevent="$dispatch('open-modal', 'restore-{{ $r->id }}')"
                          class="text-yellow-600 hover:text-yellow-700 hover:underline">
                          Restore
                        </button>

                        <x-modal name="restore-{{ $r->id }}" focusable>
                          <form method="POST" action="{{ route('requests.restore', $r->id) }}"
                            class="p-6">
                            @csrf
                            <h2 class="text-lg font-medium text-gray-900">Restore Request</h2>
                            <p class="mt-1 text-sm text-gray-600">
                              Are you sure you want to restore <strong>{{ $r->title }}</strong>?
                            </p>

                            <div class="mt-6 flex justify-end">
                              <x-secondary-button
                                x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                              <x-primary-button class="ms-3">Restore</x-primary-button>
                            </div>
                          </form>
                        </x-modal>
                      @endcan

                      {{-- APPROVE / REJECT --}}
                      @can('request.approve', $r)
                        @if (!$r->deleted_at && $r->status === 'submitted')
                          {{-- Approve --}}
                          <button x-data
                            x-on:click.prevent="$dispatch('open-modal', 'approve-{{ $r->id }}')"
                            class="text-green-600 hover:text-green-800 hover:underline">
                            Approve
                          </button>

                          <x-modal name="approve-{{ $r->id }}" focusable>
                            <form method="POST" action="{{ route('approvals.approve', $r) }}"
                              class="p-6">
                              @csrf
                              <h2 class="text-lg font-medium text-gray-900">Approve Request</h2>
                              <p class="mt-1 text-sm text-gray-600">
                                Approve request <strong>{{ $r->title }}</strong> by
                                {{ $r->creator->name }}?
                              </p>
                              <div class="mt-6 flex justify-end">
                                <x-secondary-button
                                  x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                <x-primary-button class="ms-3">Approve</x-primary-button>
                              </div>
                            </form>
                          </x-modal>

                          {{-- Reject --}}
                          <button x-data
                            x-on:click.prevent="$dispatch('open-modal', 'reject-{{ $r->id }}')"
                            class="text-red-600 hover:text-red-800 hover:underline">
                            Reject
                          </button>

                          <x-modal name="reject-{{ $r->id }}" focusable>
                            <form method="POST" action="{{ route('approvals.reject', $r) }}"
                              class="p-6">
                              @csrf
                              <h2 class="text-lg font-medium text-gray-900">Reject Request</h2>
                              <p class="mt-1 text-sm text-gray-600">
                                Reject request <strong>{{ $r->title }}</strong>?
                              </p>
                              <div class="mt-4">
                                <x-input-label for="note" value="Reason" />
                                <x-text-input id="note" name="note" type="text"
                                  class="mt-1 block w-full" placeholder="Enter reason..."
                                  required />
                              </div>
                              <div class="mt-6 flex justify-end">
                                <x-secondary-button
                                  x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                <x-danger-button class="ms-3">Reject</x-danger-button>
                              </div>
                            </form>
                          </x-modal>
                        @endif
                      @endcan
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    <p class="text-sm">No requests found. Get started by creating a new one.</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Pagination --}}
      @if ($requests->hasPages())
        <div class="mt-6">
          {{ $requests->links() }}
        </div>
      @endif
    </div>
  </div>
</x-app-layout>
