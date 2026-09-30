@extends('layouts.front')

@section('content')
<div class="container mx-auto py-10 px-4 md:px-8 max-w-5xl">

    <!-- Header Section -->
    <div class="text-center max-w-3xl mx-auto mb-16">
        <div class="inline-flex items-center gap-2 bg-green-50 text-[#2ba351] px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
            <i class="fa-solid fa-circle-check"></i> Account Activated
        </div>
        <h1 class="text-xl md:text-4xl font-black text-gray-900 leading-tight mb-4 uppercase tracking-wide">
            Welcome to the Developer Portal!
        </h1>
        <p class="text-gray-600 text-base leading-relaxed">
            Congratulations! Your developer account is now active. Follow this step-by-step visual guide to set up your company profile and list your first real estate project on our platform.
        </p>
    </div>

    <!-- Step-by-Step Onboarding Timeline -->
    <div class="relative border-l-2 border-dashed border-gray-200 ml-4 md:ml-12 space-y-12 pb-6 select-none">
        
        <!-- Step 1: Complete Company Profile -->
        <div class="relative pl-8 md:pl-12">
            <!-- Floating Step Number Badge -->
            <div class="absolute left-[-21px] top-0 w-10 h-10 rounded-full bg-[#2ba351] text-white flex items-center justify-center font-black text-base shadow-md shadow-[#2ba351]/20">
                01
            </div>
            
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-6 items-start">
                <div class="w-14 h-14 rounded-2xl bg-green-50 text-[#2ba351] flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-address-card"></i>
                </div>
                <div class="flex-grow">
                    <h4 class="text-gray-800 font-extrabold text-base mb-1.5 uppercase">Set up Your Company Profile</h4>
                    <p class="text-gray-500 text-xs md:text-base leading-relaxed mb-4">
                        Navigate to "Company Profile" from your sidebar. Upload your official company logo, banner cover photo, trade license, office address, and social media links. A complete profile builds trust with buyers.
                    </p>
                    <a href="{{ route('admin.company-profile.edit') }}" class="inline-flex items-center gap-1.5 bg-[#2ba351] hover:bg-[#1a285a] text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">
                        Edit Profile <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Step 2: Navigate to Add New Project -->
        <div class="relative pl-8 md:pl-12">
            <div class="absolute left-[-21px] top-0 w-10 h-10 rounded-full bg-[#2ba351] text-white flex items-center justify-center font-black text-base shadow-md shadow-[#2ba351]/20">
                02
            </div>
            
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-6 items-start">
                <div class="w-14 h-14 rounded-2xl bg-green-50 text-[#2ba351] flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-circle-plus"></i>
                </div>
                <div class="flex-grow">
                    <h4 class="text-gray-800 font-extrabold text-base mb-1.5 uppercase">Create a New Project Listing</h4>
                    <p class="text-gray-500 text-xs md:text-base leading-relaxed">
                        Go to your dashboard menu, click on "Add New Project". You will be presented with a dynamic form where you can list all properties currently for sale or rent.
                    </p>
                </div>
            </div>
        </div>

        <!-- Step 3: Fill up Project Specifications -->
  <!-- Step 3: Fill up Project Specifications (Onboarding Guide Page) -->
        <div class="relative pl-8 md:pl-12">
            <div class="absolute left-[-21px] top-0 w-10 h-10 rounded-full bg-[#2ba351] text-white flex items-center justify-center font-black text-base shadow-md shadow-[#2ba351]/20">
                03
            </div>
            
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-6 items-start">
                <div class="w-14 h-14 rounded-2xl bg-green-50 text-[#2ba351] flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div class="flex-grow">
                    <h4 class="text-gray-800 font-extrabold text-base mb-1.5 uppercase">Input Property Details &amp; Specifications</h4>
                    <p class="text-gray-500 text-xs md:text-base leading-relaxed">
                        Fill in all basic fields including title, price, and location. <strong>You must select the correct "Category" (e.g. Apartments/Flats) and "Location" (e.g. Uttara/Gulshan) from the dropdowns</strong>, and input the specs like bedrooms and baths to display them beautifully on your public card.
                    </p>
                </div>
            </div>
        </div>

    <!-- Step 4: Add Floor Plans and Maps -->
        <div class="relative pl-8 md:pl-12">
            <div class="absolute left-[-21px] top-0 w-10 h-10 rounded-full bg-[#2ba351] text-white flex items-center justify-center font-black text-base shadow-md shadow-[#2ba351]/20">
                04
            </div>
            
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-6 items-start">
                <div class="w-14 h-14 rounded-2xl bg-green-50 text-[#2ba351] flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div class="flex-grow">
                    <h4 class="text-gray-800 font-extrabold text-base mb-1.5 uppercase">Upload Media, Floor Plans &amp; Maps</h4>
                    <p class="text-gray-500 text-xs md:text-base leading-relaxed">
                        Upload high-quality images and a video tour for your project. Don't forget to upload Floor Plans (under Description 1) and your Google Maps Embed Iframe URL. These will automatically activate the interactive floor plan sliders and Google Map iframe on your public details page.
                    </p>
                </div>
            </div>
        </div>

        <div class="relative pl-8 md:pl-12">
            <div class="absolute left-[-21px] top-0 w-10 h-10 rounded-full bg-[#2ba351] text-white flex items-center justify-center font-black text-base shadow-md shadow-[#2ba351]/20">
                05
            </div>
            
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-6 items-start">
                <div class="w-14 h-14 rounded-2xl bg-green-50 text-[#2ba351] flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-vr-cardboard"></i>
                </div>
                <div class="flex-grow">
                    <h4 class="text-gray-800 font-extrabold text-base mb-1.5 uppercase">Activate 360° Virtual Tour (100% Free)</h4>
                    <p class="text-gray-500 text-xs md:text-base leading-relaxed">
                        Capture a panoramic photo of your apartment on your mobile phone and upload it in the <strong>"360° Panorama Image (Virtual Tour)"</strong> field. Our website will automatically convert your flat panoramic image into a fully interactive 3D Virtual Tour for free! Buyers will be able to virtually walk through your property without visiting in person.
                    </p>
                </div>
            </div>
        </div>

        <!-- Step 6: Submit and Wait for Admin Approval -->
        <div class="relative pl-8 md:pl-12">
            <div class="absolute left-[-21px] top-0 w-10 h-10 rounded-full bg-[#2ba351] text-white flex items-center justify-center font-black text-base shadow-md shadow-[#2ba351]/20">
                06
            </div>
            
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-6 items-start">
                <div class="w-14 h-14 rounded-2xl bg-green-50 text-[#2ba351] flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div class="flex-grow">
                    <h4 class="text-gray-800 font-extrabold text-base mb-1.5 uppercase">Submit and Wait for Admin Approval</h4>
                    <p class="text-gray-500 text-xs md:text-base leading-relaxed">
                        After filling everything, click "Save". Your project status will start as "Pending". Our admin team will review and approve your property within 24 hours to publish it live on the main portal.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Final Call to Action Buttons -->
    <div class="mt-12 flex flex-col sm:flex-row justify-center items-center gap-4">
        <a href="{{route('home')}}" class="w-full sm:w-auto text-center bg-[#224194] hover:bg-[#152960] text-white px-10 py-4 rounded-2xl font-bold text-base transition-all shadow-lg shadow-blue-900/10 active:scale-95">
            Go to Dashboard
        </a>
        <a href="{{ route('admin.contents.create', 'project') }}" class="w-full sm:w-auto text-center bg-[#2ba351] hover:bg-[#1a285a] text-white px-10 py-4 rounded-2xl font-bold text-base transition-all shadow-lg shadow-green-900/10 active:scale-95">
            Add First Project Now
        </a>
    </div>

</div>
@endsection
