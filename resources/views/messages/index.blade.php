<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            I tuoi Messaggi
            @if($unreadCount > 0)
                <span class="ml-2 px-2 py-1 text-sm bg-red-100 text-red-700 rounded-full">{{ $unreadCount }} nuovi</span>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($messages->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-4">
                        @foreach($messages as $msg)
                            <div class="border rounded-lg overflow-hidden
                                {{ !$msg->is_read ? 'border-blue-300 bg-blue-50' : 'border-gray-200' }}
                                {{ $msg->replied_at ? 'border-l-4 border-l-green-500' : '' }}">

                                {{-- Header --}}
                                <div class="flex justify-between items-center p-4 pb-2">
                                    <div class="flex items-center space-x-3">
                                        @if(!$msg->is_read)
                                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        @endif
                                        <strong class="text-gray-900">{{ $msg->sender_name }}</strong>
                                        <span class="text-sm text-gray-500">·</span>
                                        <span class="text-sm text-gray-500">{{ $msg->car->title }}</span>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>

                                {{-- Email mittente --}}
                                <div class="px-4 pb-2">
                                    <span class="text-xs text-gray-500">{{ $msg->sender_email }}</span>
                                    @if($msg->replied_at)
                                        <span class="ml-2 text-xs text-green-600">· Risposto {{ $msg->replied_at->diffForHumans() }}</span>
                                    @endif
                                </div>

                                {{-- Messaggio --}}
                                <div class="px-4 pb-3">
                                    <p class="text-gray-700">{{ $msg->message }}</p>
                                </div>

                                {{-- Azioni --}}
                                <div class="px-4 py-3 bg-gray-50 flex flex-wrap gap-2">
                                    @if(!$msg->is_read)
                                        <form action="{{ route('messages.read', $msg) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                Segna come letto
                                            </button>
                                        </form>
                                    @endif

                                    <button type="button"
                                            @click="document.getElementById('reply-{{ $msg->id }}').classList.toggle('hidden')"
                                            class="text-sm text-green-600 hover:text-green-800 font-medium">
                                        Rispondi
                                    </button>

                                    <form action="{{ route('messages.destroy', $msg) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Eliminare questo messaggio?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">
                                            Elimina
                                        </button>
                                    </form>
                                </div>

                                {{-- Form risposta --}}
                                <div id="reply-{{ $msg->id }}" class="hidden border-t border-gray-200 p-4 bg-white">
                                    <form action="{{ route('messages.reply', $msg) }}" method="POST">
                                        @csrf
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Rispondi a {{ $msg->sender_name }} ({{ $msg->sender_email }}):
                                        </label>
                                        <textarea name="reply" rows="3" required maxlength="5000"
                                                  class="block w-full rounded-md border-gray-300 shadow-sm text-sm"></textarea>
                                        <button type="submit" class="mt-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                                            Invia Risposta
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    {{ $messages->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        Nessun messaggio ricevuto.
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Alpine.js per toggle reply form
        document.querySelectorAll('[data-reply-toggle]').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById(this.dataset.replyToggle).classList.toggle('hidden');
            });
        });
    </script>
    @endpush
</x-app-layout>
