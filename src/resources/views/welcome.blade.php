<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EkoStock</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="min-h-screen flex flex-col">

        <div class="px-8 min-h-20 bg-green-800 flex flex-row justify-between text-white font-bold">
            <button class="flex items-center hover:bg-green-700 p-4 rounded-md">
                EkoStock
            </button>

            <div class="flex flex-row items-center gap-6">
                <button class="flex items-center p-4 rounded-md hover:bg-green-700">
                    About us
                </button>
                <button class="flex items-center p-4 rounded-md hover:bg-green-700">
                    Investemnts
                </button>
                <button class="flex items-center p-4 rounded-md hover:bg-green-700">
                    ROI Calculator
                </button>
                <button class="flex items-center p-4 rounded-md hover:bg-green-700">
                    FAQ
                </button>
            </div>

            <button class="flex flex-row gap-6 items-center p-4 rounded-md hover:bg-green-700">
                <img class="rounded-full" src="https://picsum.photos/id/237/64/64" />
                <span>Aleksa Sibinović</span>
                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M17.7463 0H7.26612C2.71385 0 0 2.7125 0 7.2625V17.725C0 22.2875 2.71385 25 7.26612 25H17.7338C22.2861 25 25 22.2875 25 17.7375V7.2625C25.0125 2.7125 22.2986 0 17.7463 0ZM18.7593 19.0625H6.25312C5.74036 19.0625 5.31515 18.6375 5.31515 18.125C5.31515 17.6125 5.74036 17.1875 6.25312 17.1875H18.7593C19.2721 17.1875 19.6973 17.6125 19.6973 18.125C19.6973 18.6375 19.2721 19.0625 18.7593 19.0625ZM18.7593 13.4375H6.25312C5.74036 13.4375 5.31515 13.0125 5.31515 12.5C5.31515 11.9875 5.74036 11.5625 6.25312 11.5625H18.7593C19.2721 11.5625 19.6973 11.9875 19.6973 12.5C19.6973 13.0125 19.2721 13.4375 18.7593 13.4375ZM18.7593 7.8125H6.25312C5.74036 7.8125 5.31515 7.3875 5.31515 6.875C5.31515 6.3625 5.74036 5.9375 6.25312 5.9375H18.7593C19.2721 5.9375 19.6973 6.3625 19.6973 6.875C19.6973 7.3875 19.2721 7.8125 18.7593 7.8125Z"
                        fill="white" />
                </svg>
            </button>
        </div>

    </div>
</body>

</html>