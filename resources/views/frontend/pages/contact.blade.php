@extends('layouts.front')

@section('meta')
    @include('frontend.partials.meta', ['pageKey' => 'contact'])
@endsection

@section('content')

    <!-- MAIN SECTION -->
    <section class="bg-white py-12 md:py-20 border-t border-gray-200">
        <div class="container mx-auto px-4 md:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start">

                <!-- LEFT SIDE: Contact Information (No Background) -->
                <div class="lg:col-span-6 flex flex-col gap-10 py-4 mt-10">

                   
                    <!-- 2. Head Office -->
                    <div class="flex items-start gap-5">
                        <div
                            class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 border border-gray-200">
                            <i class="fa-solid fa-building text-lg text-gray-700"></i>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="text-xl font-bold text-gray-900">Head Office</h4>
                            <p class="text-gray-500 text-base md:text-base">
                                {{ $setting->address ?? 'Sandwip Baban, 28/A-3, Toyenbee Circular Road, Motijheel C/A, Dhaka' }}
                            </p>
                        </div>
                    </div>

                    <!-- 3. Phone Number -->
                    <div class="flex items-start gap-5">
                        <div
                            class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 border border-gray-200">
                            <i class="fa-solid fa-phone text-lg text-gray-700"></i>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="text-xl font-bold text-gray-900">Phone Number</h4>
                            <p class="text-gray-500 text-base md:text-base">
                                {!! $setting->phone ?? '+880 1938-886333' !!}
                            </p>
                        </div>
                    </div>

                    <!-- 4. Email Us -->
                    <div class="flex items-start gap-5">
                        <div
                            class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 border border-gray-200">
                            <i class="fa-solid fa-envelope text-lg text-gray-700"></i>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="text-xl font-bold text-gray-900">Email us</h4>
                            <p class="text-gray-500 text-base md:text-base">
                                {{ $setting->email ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Need Help Box (Interior Card) -->
                    <div class="mt-4 bg-[#2C4798] p-8 rounded-[2rem] text-white shadow-xl">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-headset text-xl text-black"></i>
                            </div>
                            <div>
                                <h5 class="text-lg font-bold">Need help?</h5>
                                <p class=" text-base">Call our team</p>
                            </div>
                        </div>

                        <div class="text-xl md:text-xl font-bold mb-4 tracking-tight">
                            {{ $setting->phone ?? '-' }}
                        </div>

                        <div class="text-xs font-medium uppercase tracking-widest leading-loose">
                            Saturday - Friday <br>
                            <span class=" normal-case tracking-normal">09:00 AM - 10:00 PM</span>
                        </div>
                    </div>

                </div>

                <!-- RIGHT SIDE: Interest Form -->
                <div class="lg:col-span-6 md:mt-8">
                    <div class="bg-[#DFE8FF] p-7 md:p-10 rounded-[2.5rem] border border-blue-50 shadow-sm">
                        <h2 class="text-xl md:text-xl font-bold text-gray-800 leading-snug mb-8">
                            I am interested in this project.
                        </h2>

                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl text-base">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST" class="flex flex-col gap-5">
                            @csrf
                            <input type="text" name="name" placeholder="Name (required)" required
                                class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all"
                                value="{{ old('name') }}" />

                            <input type="email" name="email" placeholder="Email Address (required)" required
                                class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all"
                                value="{{ old('email') }}" />

                            <input type="tel" name="phone" placeholder="Phone Number (optional)"
                                class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all"
                                value="{{ old('phone') }}" />

                            <input type="text" name="designation" placeholder="Designation (optional)"
                                class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all"
                                value="{{ old('designation') }}" />

                            <!-- Category Selection -->
                            <div class="relative">
                                <label for="category_select" class="sr-only">Select Interest Category</label>
                                <select id="category_select" name="category_id" required
                                    class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all cursor-pointer appearance-none text-gray-500">
                                    <option value="" disabled selected>Select Interest Category *</option>

                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                    @endforeach
                                </select>

                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-600">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>

                            <textarea name="message" placeholder="What do you want to know? (optional)" rows="4"
                                class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all resize-none">{{ old('message') }}</textarea>

                            <button type="submit"
                                class="cursor-pointer w-full bg-[#2C4798] hover:bg-[#1e2d6b] text-white py-5 rounded-2xl font-bold text-lg flex items-center justify-center gap-3 transition-all shadow-lg shadow-[#2C4798]/20 active:scale-[0.98]">
                                <i class="fa-solid fa-paper-plane text-base"></i>
                                Submit Now
                            </button>
                        </form>

                        @if (session('success'))
                            <div class="mt-6 p-4 bg-green-100 text-green-700 rounded-xl text-center font-medium shadow-sm">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

