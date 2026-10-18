<x-kasir-layout title="Kasir">
    <div x-data="posApp()" class="mx-auto max-w-5xl">
        <div class="grid gap-4 lg:grid-cols-[1fr_320px]">
            <div class="space-y-4">
                <div class="relative">
                    <input
                        type="search"
                        x-model="query"
                        @input.debounce.250ms="search()"
                        @click.outside="showResults = false"
                        placeholder="Cari barang..."
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    >

                    <div x-show="showResults && results.length" x-cloak class="absolute z-10 mt-1 w-full overflow-hidden rounded-md border border-gray-200 bg-white shadow-lg">
                        <template x-for="item in results" :key="item.id">
                            <button type="button" @click="addToCart(item)" class="flex w-full items-center justify-between px-3 py-2 text-left text-sm hover:bg-gray-50">
                                <span>
                                    <span class="font-medium text-gray-800" x-text="item.nama"></span>
                                    <span class="ml-2 text-xs text-gray-500" x-text="item.kategori || '-'"></span>
                                </span>
                                <span class="text-right">
                                    <span class="text-gray-600" x-text="rp(item.harga)"></span>
                                    <span class="ml-3 text-xs text-gray-400" x-text="'stok ' + item.stok"></span>
                                </span>
                            </button>
                        </template>
                    </div>

                    <p x-show="showResults && query.length >= 2 && !results.length && !loading" x-cloak class="mt-2 text-sm text-gray-500">
                        Barang tidak ditemukan.
                    </p>
                </div>

                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left font-medium text-gray-600">Barang</th>
                                <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600">Harga</th>
                                <th scope="col" class="px-4 py-3 text-center font-medium text-gray-600">Qty</th>
                                <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600">Subtotal</th>
                                <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-if="cart.length">
                                <template x-for="line in cart" :key="line.id">
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-800" x-text="line.nama"></td>
                                        <td class="px-4 py-3 text-right text-gray-600" x-text="rp(line.harga)"></td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button" @click="decreaseQty(line)" class="h-7 w-7 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50">-</button>
                                                <span class="w-6 text-center text-gray-800" x-text="line.qty"></span>
                                                <button type="button" @click="increaseQty(line)" class="h-7 w-7 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50">+</button>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-800" x-text="rp(line.harga * line.qty)"></td>
                                        <td class="px-4 py-3 text-right">
                                            <button type="button" @click="removeLine(line)" class="font-medium text-red-600 hover:text-red-800">Hapus</button>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                            <template x-if="!cart.length">
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500">
                                        Keranjang kosong. Cari barang untuk mulai berjualan.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="h-fit rounded-lg border border-gray-200 bg-white p-4">
                <div x-show="!paid">
                    <h2 class="text-sm font-semibold text-gray-800">Ringkasan</h2>

                    <dl class="mt-3 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Total</dt>
                            <dd class="font-semibold text-gray-800" x-text="rp(total)"></dd>
                        </div>
                    </dl>

                    <div class="mt-4">
                        <label for="bayar" class="block text-sm font-medium text-gray-700">Uang diterima</label>
                        <input
                            id="bayar"
                            type="number"
                            min="0"
                            x-model.number="bayar"
                            placeholder="0"
                            class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                        >
                    </div>

                    <div class="mt-3 flex justify-between text-sm">
                        <span class="text-gray-500">Kembalian</span>
                        <span class="font-semibold" :class="kembalian < 0 ? 'text-red-600' : 'text-gray-800'" x-text="rp(Math.max(kembalian, 0))"></span>
                    </div>

                    <p
                        x-show="bayar !== null && bayar !== '' && kembalian < 0"
                        x-cloak
                        class="mt-2 text-xs text-red-600"
                    >
                        Uang diterima kurang dari total belanja.
                    </p>

                    <button
                        type="button"
                        @click="pay()"
                        :disabled="!cart.length || submitting"
                        class="mt-4 w-full rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-300"
                    >
                        <span x-show="!submitting">Bayar</span>
                        <span x-show="submitting" x-cloak>Memproses...</span>
                    </button>

                    <p x-show="error" x-cloak class="mt-3 text-sm text-red-600" x-text="error"></p>
                </div>

                <div x-show="paid" x-cloak>
                    <h2 class="text-sm font-semibold text-green-800">Pembayaran berhasil</h2>

                    <dl class="mt-3 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Total</dt>
                            <dd class="font-semibold text-gray-800" x-text="rp(paid ? paid.total : 0)"></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Diterima</dt>
                            <dd class="text-gray-800" x-text="rp(paid ? paid.bayar : 0)"></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Kembalian</dt>
                            <dd class="font-semibold text-gray-800" x-text="rp(paid ? paid.kembalian : 0)"></dd>
                        </div>
                    </dl>

                    <a :href="paid ? paid.receipt_url : '#'" target="_blank" rel="noopener" class="mt-4 block w-full rounded-md bg-indigo-600 px-4 py-2.5 text-center text-sm font-medium text-white hover:bg-indigo-700">
                        Cetak struk
                    </a>

                    <button type="button" @click="startNew()" class="mt-2 w-full rounded-md border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Transaksi baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function posApp() {
            return {
                query: '',
                results: [],
                showResults: false,
                loading: false,
                cart: [],
                bayar: null,
                submitting: false,
                error: '',
                paid: null,

                rp(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value || 0);
                },

                async search() {
                    const keyword = this.query.trim();
                    if (keyword.length < 2) {
                        this.results = [];
                        this.showResults = false;
                        return;
                    }

                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('pos.search') }}?q=' + encodeURIComponent(keyword), {
                            headers: { 'Accept': 'application/json' },
                        });
                        const payload = await response.json();
                        this.results = payload.data;
                        this.showResults = true;
                    } finally {
                        this.loading = false;
                    }
                },

                addToCart(item) {
                    const line = this.cart.find((entry) => entry.id === item.id);
                    if (line) {
                        if (line.qty < item.stok) {
                            line.qty += 1;
                        }
                    } else {
                        this.cart.push({ ...item, qty: 1 });
                    }

                    this.query = '';
                    this.results = [];
                    this.showResults = false;
                },

                increaseQty(line) {
                    if (line.qty < line.stok) {
                        line.qty += 1;
                    }
                },

                decreaseQty(line) {
                    if (line.qty > 1) {
                        line.qty -= 1;
                    } else {
                        this.removeLine(line);
                    }
                },

                removeLine(line) {
                    this.cart = this.cart.filter((entry) => entry.id !== line.id);
                },

                get total() {
                    return this.cart.reduce((sum, line) => sum + line.harga * line.qty, 0);
                },

                get kembalian() {
                    return (this.bayar || 0) - this.total;
                },

                async pay() {
                    this.error = '';

                    if (!this.cart.length) {
                        this.error = 'Keranjang masih kosong.';
                        return;
                    }

                    if (this.kembalian < 0) {
                        this.error = 'Uang diterima kurang dari total belanja.';
                        return;
                    }

                    this.submitting = true;
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        const response = await fetch('{{ route('pos.pay') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token || '',
                            },
                            body: JSON.stringify({
                                items: this.cart.map((entry) => ({ id: entry.id, qty: entry.qty })),
                                bayar: this.bayar,
                            }),
                        });

                        const payload = await response.json();

                        if (!response.ok) {
                            const firstError = payload.errors ? Object.values(payload.errors)[0]?.[0] : null;
                            this.error = firstError || payload.message || 'Pembayaran gagal.';
                            return;
                        }

                        this.paid = payload.data;
                        this.cart = [];
                        this.bayar = null;
                    } catch (e) {
                        this.error = 'Gagal memproses pembayaran, coba lagi.';
                    } finally {
                        this.submitting = false;
                    }
                },

                startNew() {
                    this.paid = null;
                    this.error = '';
                    this.cart = [];
                    this.bayar = null;
                },
            };
        }
    </script>
</x-kasir-layout>