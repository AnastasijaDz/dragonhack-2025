<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoStock</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="min-h-screen flex flex-col">

        <div class="px-8 min-h-20 bg-green-800 flex flex-row justify-between text-white font-bold z-10 shadow-xl">
            <button class="flex items-center hover:bg-green-700 p-4 rounded-md">
                EcoStock
            </button>

            <ul class="flex flex-row items-center gap-6">
                <li>
                    <button class="flex items-center p-4 rounded-md hover:bg-green-700 h-full">
                        About us
                    </button>
                </li>
                <li>
                    <button class="flex items-center p-4 rounded-md hover:bg-green-700 h-full">
                        Investments
                    </button>
                </li>
                <li>
                    <button class="flex items-center p-4 rounded-md hover:bg-green-700 h-full">
                        FAQ
                    </button>
                </li>
            </ul>

            <button class="profile-info flex flex-row gap-6 items-center p-4 rounded-md hover:bg-green-700">
                <img class="rounded-full" src="https://picsum.photos/id/237/64/64" />
                <span>Aleksa Sibinović</span>
                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M17.7463 0H7.26612C2.71385 0 0 2.7125 0 7.2625V17.725C0 22.2875 2.71385 25 7.26612 25H17.7338C22.2861 25 25 22.2875 25 17.7375V7.2625C25.0125 2.7125 22.2986 0 17.7463 0ZM18.7593 19.0625H6.25312C5.74036 19.0625 5.31515 18.6375 5.31515 18.125C5.31515 17.6125 5.74036 17.1875 6.25312 17.1875H18.7593C19.2721 17.1875 19.6973 17.6125 19.6973 18.125C19.6973 18.6375 19.2721 19.0625 18.7593 19.0625ZM18.7593 13.4375H6.25312C5.74036 13.4375 5.31515 13.0125 5.31515 12.5C5.31515 11.9875 5.74036 11.5625 6.25312 11.5625H18.7593C19.2721 11.5625 19.6973 11.9875 19.6973 12.5C19.6973 13.0125 19.2721 13.4375 18.7593 13.4375ZM18.7593 7.8125H6.25312C5.74036 7.8125 5.31515 7.3875 5.31515 6.875C5.31515 6.3625 5.74036 5.9375 6.25312 5.9375H18.7593C19.2721 5.9375 19.6973 6.3625 19.6973 6.875C19.6973 7.3875 19.2721 7.8125 18.7593 7.8125Z"
                        fill="white" />
                </svg>
            </button>
        </div>

        <div class="flex flex-col gap-16 relative">
                <ul class="profile-menu-options flex-col absolute z-10 right-12 top-4 bg-white py-4 font-semibold text-black rounded-xl shadow-lg hidden opacity-0 transition-all duration-300 ease-in-out transform scale-95">                <li class="w-full">
                    <button class="w-full px-4 flex flex-row items-center hover:bg-gray-200 py-6 gap-4">
                        <div class="w-8 h-8">
                            <img class="w-full h-full" src="/svgs/card.svg">
                        </div>
                        <p>My portfolio</p>
                    </button>
                </li>
                <li class="w-full">
                    <button class="w-full px-4 flex flex-row items-center hover:bg-gray-200 py-6 gap-4">
                        <div class="w-8 h-8">
                            <img class="w-full h-full" src="/svgs/settings.svg">
                        </div>
                        <p>Settings</p>
                    </button>
                </li>
                <li class="w-full">
                    <button class="w-full px-4 flex flex-row items-center hover:bg-gray-200 py-6 gap-4">
                        <div class="w-8 h-8">
                            <img class="w-full h-full" src="/svgs/logout.svg">
                        </div>
                        <p>Log out</p>
                    </button>
                </li>
            </ul>

            <div class="w-full relative">
                <img src="/images/main-page-image.jpg" alt="Main Page Image">
                <div class="top-[40%] left-[10%] flex flex-col absolute text-white">
                    <h1 class="text-8xl font-bold">EcoStock</h1>
                    <p class="text-6xl">Backing Farms, Building Futures.</p>
                </div>
            </div>

            <div class="w-full relative flex flex-row justify-center gap-10">
                <div class="w-[20%]">
                    <img src="/images/farmer-cartoon.png" alt="Farmer Cartoon">
                </div>
                <div class="flex flex-col w-[60%] gap-10 items-start justify-center">
                    <h2 class="text-5xl text-green-700 font-extrabold text-left">Eco-friendly investment</h2>
                    <p class="text-black font-bold text-xl pl-2">Looking for a best place to put your money to work? We got
                        you
                        covered! By partnering up with
                        local farmers who are experienced in their field, you are presented with an opportunity to
                        support Eco-friendly and sustainable projects.</p>
                </div>
            </div>

            <div class="flex items-center justify-center">
                <div class="w-[95%] relative flex flex-row justify-center gap-10 bg-green-700 p-12 rounded-2xl">
                    <div class="flex flex-col w-[60%] gap-10 items-end justify-center text-white">
                        <h2 class="text-5xl font-extrabold text-right">Focus on investing, not paperwork.</h2>
                        <p class="font-bold text-xl text-right pr-2">As an investor, your focus is on making money by smart investing.
                            We take care of the rest. On our “investments” page you can find available options with
                            detailed information, including the contacts of the project owners. When you are ready, you
                            can create a profile and start investing by buying shares of the project. </p>
                    </div>
                    <div class="w-[20%]">
                        <img class="rounded-lg" src="/images/investor-apple.png" alt="Farmer Cartoon">
                    </div>
                </div>
            </div>

            <div class="w-full relative flex flex-row justify-center gap-10">
                <div class="w-[20%]">
                    <img src="/images/apple-with-a-lock.png" alt="Farmer Cartoon">
                </div>
                <div class="flex flex-col w-[60%] gap-10 items-start justify-center">
                    <h2 class="text-5xl text-green-700 font-extrabold text-left">Your investment is safe with us.</h2>
                    <div class="flex flex-col gap-4 pl-2">
                        <p class="text-black font-bold text-xl">Every investment is securely tracked through blockchain
                            technology.
                            Your ownership in a project is represented by digital tokens,
                            ensuring full transparency and security.
                        </p>
                        <p class="text-black font-bold text-xl">
                            These tokens can be seamlessly transferred to other investors at any time, no need for complicated paperwork or new contract
                            signings. It’s a flexible, secure, and efficient system designed to give you full control over your investment.
                    </p>    
                    </div>                                                      
                </div>
            </div>

            <div class="border-t-[1px] border-gray-400 w-full flex flex-row p-8 justify-around">
                <div class="flex flex-row gap-20">
                    <div class="flex flex-col gap-2">
                        <span class="font-bold text-lg">Contact Us</span>
                        <span>support@ecostock.com</span>
                        <span>+386 1 234 567</span>
                        <span>Ljubljana, Slovenia</span>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="font-bold text-lg">Quick Links</span>
                        <a href="#" class="hover:text-green-700">About Us</a>
                        <a href="#" class="hover:text-green-700">Investments</a>
                        <a href="#" class="hover:text-green-700">FAQ</a>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="font-bold text-lg">Legal</span>
                        <a href="#" class="hover:text-green-700">Privacy Policy</a>
                        <a href="#" class="hover:text-green-700">Terms of Service</a>
                        <a href="#" class="hover:text-green-700">Cookie Policy</a>
                    </div>
                </div>
                <div class="flex flex-col gap-2 items-center justify-center">
                    <span class="text-gray-600">© 2025 EcoStock.</span>
                    <span class="text-gray-600">All rights reserved.</span>
                </div>
            </div>

            <div class="menu-open-overlay hidden opacity-0 transition-all duration-300 ease-in-out absolute w-full h-full top-0 bg-black bg-opacity-50"></div>
        </div>
    </div>
</body>

</html>

<script>
    const profileInfoButton = document.querySelector('button.profile-info');

    const openMenuDropdown = () => {
    const profileMenuOptions = document.querySelector('ul.profile-menu-options');
    const overlay = document.querySelector('div.menu-open-overlay');
    
    if (profileMenuOptions.classList.contains('hidden')) {
        // Show menu
        profileMenuOptions.classList.remove('hidden');
        profileMenuOptions.classList.add('flex');

        // Wait a tiny bit to trigger the animation
        setTimeout(() => {
            profileMenuOptions.classList.remove('opacity-0', 'scale-95');
            profileMenuOptions.classList.add('opacity-100', 'scale-100');
            
            overlay.classList.remove('hidden', 'opacity-0');
            overlay.classList.add('opacity-100');
        }, 10);
    } else {
        // Hide menu
        profileMenuOptions.classList.remove('opacity-100', 'scale-100');
        profileMenuOptions.classList.add('opacity-0', 'scale-95');
        
        overlay.classList.remove('opacity-100');
        overlay.classList.add('opacity-0');
        
        // Wait for animation to finish before hiding
        setTimeout(() => {
            profileMenuOptions.classList.remove('flex');
            profileMenuOptions.classList.add('hidden');
            
            overlay.classList.add('hidden');
        }, 300);
    }
};

    document.addEventListener('DOMContentLoaded', () => {
        profileInfoButton.addEventListener('click', openMenuDropdown);

        document.querySelector('div.menu-open-overlay').addEventListener('click', () => {
            const profileMenuOptions = document.querySelector('ul.profile-menu-options');
            profileMenuOptions.classList.remove('flex');
            profileMenuOptions.classList.add('hidden');

            document.querySelector('div.menu-open-overlay').classList.add('hidden');
        });
    });
</script>