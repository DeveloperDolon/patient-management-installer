@extends('dashboard-layout')

@section('content')
    <div class="mx-auto mt-10 max-w-3xl rounded-xl bg-white p-6 shadow-md">
        <h2 class="mb-6 text-2xl font-semibold">Add New Clinic</h2>
        <form
            action="{{ route('dashboard.clinics.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="grid grid-cols-1 gap-6 md:grid-cols-2"
        >
            @csrf
            <div>
                <label for="logo" class="block text-sm font-medium text-gray-700">Clinic Logo <span class="italic">(100x100)</span></label>

                <!-- Image Preview -->
                <div id="logoPreviewWrapper">
                    <img
                        id="logoPreview"
                        src="#"
                        alt="Logo Preview"
                        class="hidden h-24 w-24 rounded-md border border-gray-300 object-cover"
                    />
                </div>

                <!-- File Input -->
                <input
                    type="file"
                    name="logo"
                    id="logo"
                    accept="image/*"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                />
            </div>

            {{-- name --}}
            <div class="flex flex-col justify-end">
                <label for="clinic_name" class="block text-sm font-medium text-gray-700">Clinic Name</label>
                <input
                    type="text"
                    name="clinic_name"
                    id="name"
                    placeholder="Enter clinic name"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required
                />
            </div>

            {{-- address --}}
            <div>
                <label for="clinic_address" class="block text-sm font-medium text-gray-700">Clinic Address</label>
                <input
                    type="text"
                    name="clinic_address"
                    id="clinic_address"
                    placeholder="Ex: Shajahanpur 7/A, Motijhil, Dhaka."
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                />
            </div>

            {{-- Phone --}}
            <div>
                <label for="clinic_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input
                    type="text"
                    name="clinic_phone"
                    id="clinic_phone"
                    placeholder="Ex: 01751725042"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                />
            </div>

            {{-- email --}}
            <div>
                <label for="clinic_email" class="block text-sm font-medium text-gray-700">Clinic Email</label>
                <input
                    type="email"
                    name="clinic_email"
                    id="clinic_email"
                    placeholder="Ex: example@gmail.com"
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                />
            </div>

            <div>
                <label for="visit_time" class="block text-sm font-medium text-gray-700">Visit Time</label>
                <input
                    type="text"
                    name="visit_time"
                    id="visit_time"
                    placeholder="Ex: শনিবার থেকে শুক্রবার বিকাল ৫:০০ থেকে ১০:০০ পর্যন্ত "
                    class="focus:ring-opacity-50 mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                />
            </div>

            {{-- Submit Button --}}
            <div class="col-span-2 flex items-end justify-end">
                <button type="submit" class="rounded-md bg-blue-600 px-6 py-2 text-white transition hover:bg-blue-700">
                    <i class="fa fa-plus"></i>
                    Add Clinic
                </button>
            </div>
        </form>
    </div>
@endsection

<script>
    @section(section: 'script')
       $(document).ready(function () {
        $('#logo').change(function (e) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#logoPreview')
                    .attr('src', e.target.result)
                    .removeClass('hidden');
            };
            if (this.files && this.files[0]) {
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
    @endsection
</script>
