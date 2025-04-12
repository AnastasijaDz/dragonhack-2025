<header
    class="px-8 min-h-20 bg-green-800 flex flex-row justify-between text-white font-bold z-10 shadow-xl sticky top-0"
    role="banner">
    <button class="flex items-center hover:bg-green-700 p-4 rounded-md" tabindex="-1">
        <img src="/svgs/white-logo.svg" alt="Logo">
    </button>

    <nav role="navigation" aria-label="Main Navigation">
        <ul class="flex flex-row items-center gap-6 h-full">
            <li class="h-full" tabindex="-1">
                <button class="flex items-center p-4 px-8 rounded-md hover:bg-green-700 h-full text-lg">
                    About us
                </button>
            </li>
            <li class="h-full" tabindex="-1">
                <button class="flex items-center p-4 px-8 rounded-md hover:bg-green-700 h-full text-lg">
                    Investments
                </button>
            </li>
            <li class="h-full" tabindex="-1">
                <button class="flex items-center p-4 px-8 rounded-md hover:bg-green-700 h-full text-lg">
                    FAQ
                </button>
            </li>
        </ul>
    </nav>

    <div class="relative">
        <button class="profile-info flex flex-row gap-6 items-center p-4 rounded-md hover:bg-green-700"
            aria-expanded="false" aria-controls="profile-menu" aria-haspopup="true" aria-label="Profile Menu"
            tabindex="-1">
            <img class="rounded-full" src="https://picsum.photos/id/237/64/64" />
            <span>Aleksa Sibinović</span>
            <svg aria-hidden="true" width="25" height="25" viewBox="0 0 25 25" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M17.7463 0H7.26612C2.71385 0 0 2.7125 0 7.2625V17.725C0 22.2875 2.71385 25 7.26612 25H17.7338C22.2861 25 25 22.2875 25 17.7375V7.2625C25.0125 2.7125 22.2986 0 17.7463 0ZM18.7593 19.0625H6.25312C5.74036 19.0625 5.31515 18.6375 5.31515 18.125C5.31515 17.6125 5.74036 17.1875 6.25312 17.1875H18.7593C19.2721 17.1875 19.6973 17.6125 19.6973 18.125C19.6973 18.6375 19.2721 19.0625 18.7593 19.0625ZM18.7593 13.4375H6.25312C5.74036 13.4375 5.31515 13.0125 5.31515 12.5C5.31515 11.9875 5.74036 11.5625 6.25312 11.5625H18.7593C19.2721 11.5625 19.6973 11.9875 19.6973 12.5C19.6973 13.0125 19.2721 13.4375 18.7593 13.4375ZM18.7593 7.8125H6.25312C5.74036 7.8125 5.31515 7.3875 5.31515 6.875C5.31515 6.3625 5.74036 5.9375 6.25312 5.9375H18.7593C19.2721 5.9375 19.6973 6.3625 19.6973 6.875C19.6973 7.3875 19.2721 7.8125 18.7593 7.8125Z"
                    fill="white" />
            </svg>
        </button>

        <ul id="profile-menu" role="menu" aria-label="Profile Menu"
            class="profile-menu-options flex-col absolute z-10 right-12 top-28 bg-white py-4 font-semibold text-black rounded-xl shadow-lg hidden opacity-0 transition-all duration-300 ease-in-out transform scale-95">
            <li class="w-full">
                <button class="w-full px-4 flex flex-row items-center hover:bg-gray-200 py-6 gap-4">
                    <div class="w-8 h-8">
                        <img class="w-full h-full" src="/svgs/card.svg">
                    </div>
                    <p>My Portfolio</p>
                </button>
            </li>
            <li class="w-full">
                <button class="w-full px-4 flex flex-row items-center hover:bg-gray-200 py-6 gap-4">
                    <div class="w-8 h-8">
                        <img class="w-full h-full fill-black" src="/svgs/dollar-square.svg">
                    </div>
                    <p>Manage Investments</p>
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
    </div>
</header>

<script>
    const profileInfoButton = document.querySelector('button.profile-info');

    const closeMenu = () => {
        // Handle ARIA
        profileInfoButton.setAttribute('aria-expanded', 'false');

        const profileMenuOptions = document.querySelector('ul.profile-menu-options');
        profileMenuOptions.classList.remove('opacity-100', 'scale-100');
        profileMenuOptions.classList.add('opacity-0', 'scale-95');

        // Wait for animation to finish before hiding
        setTimeout(() => {
            profileMenuOptions.classList.remove('flex');
            profileMenuOptions.classList.add('hidden');
        }, 300);
    };

    const openMenu = (profileMenuOptions) => {
        // Handle ARIA
        profileInfoButton.setAttribute('aria-expanded', 'true');

        // Show menu
        profileMenuOptions.classList.remove('hidden');
        profileMenuOptions.classList.add('flex');

        // Wait a tiny bit to trigger the animation
        setTimeout(() => {
            profileMenuOptions.classList.remove('opacity-0', 'scale-95');
            profileMenuOptions.classList.add('opacity-100', 'scale-100');
        }, 10);
    };

    const openMenuDropdown = () => {
        const profileMenuOptions = document.querySelector('ul.profile-menu-options');

        if (profileMenuOptions.classList.contains('hidden')) {
            openMenu(profileMenuOptions);
        } else {
            closeMenu();
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        profileInfoButton.addEventListener('click', openMenuDropdown);

        // Close the menu when clicking outside of it
        document.addEventListener('click', (event) => {
            const profileMenuOptions = document.querySelector('ul.profile-menu-options');
            if (!profileInfoButton.contains(event.target) && !profileMenuOptions.contains(event.target)) {
                closeMenu();
            }
        });

        // Close the menu when clicking on a menu item
        const menuItems = document.querySelectorAll('ul.profile-menu-options li button');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                closeMenu();
            });
        });

        // Close the menu when pressing the Escape key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeMenu();
            }
        });
    });
</script>