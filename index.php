<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Fishing Hotspots (Local)</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom Styles for Theming and Animations -->
    <style>
        /* Base styles */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to bottom right, #0F766E, #164E63); /* Dark teal to deep blue-green */
            background-attachment: fixed;
        }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap');

        /* Animated Background Waves */
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.05); opacity: 0.7; }
        }
        .animate-pulse-subtle {
            animation: pulse 4s ease-in-out infinite alternate;
        }
        .animate-pulse-subtle.delay-1000 {
            animation-delay: 1s;
        }

        /* Message Box animation */
        #message-box {
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s ease-out, transform 0.3s ease-out;
            pointer-events: none; /* Allows clicks to pass through when hidden */
        }
        #message-box.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto; /* Re-enable clicks when shown */
        }

        /* Spinner animation */
        @keyframes spin {
          from { transform: rotate(0deg); }
          to { transform: rotate(360deg); }
        }
        .animate-spin {
          animation: spin 1s linear infinite;
        }

        /* Custom button styles to match the theme */
        .framer-button {
            padding: 0.75rem 1.5rem;
            border-radius: 9999px; /* Fully rounded */
            font-weight: 700; /* Bold */
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .framer-button-primary {
            background: linear-gradient(to right, #F97316, #FB923C); /* Orange to Amber */
            color: #ffffff;
        }
        .framer-button-primary:hover {
            box-shadow: 0 6px 15px rgba(249, 115, 22, 0.6);
            transform: translateY(-2px) scale(1.02);
        }
        .framer-button-primary:active {
            transform: translateY(0) scale(0.98);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
        }

        .framer-button-secondary {
            background: linear-gradient(to right, #3B82F6, #0EA5E9); /* Blue to Cyan */
            color: #ffffff;
        }
        .framer-button-secondary:hover {
            box-shadow: 0 6px 15px rgba(59, 130, 246, 0.6);
            transform: translateY(-2px) scale(1.02);
        }
        .framer-button-secondary:active {
            transform: translateY(0) scale(0.98);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
        }

        .framer-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        /* Custom input field focus styles */
        input:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.5); /* Emerald ring */
            border-color: #0EA5E9; /* Cyan border */
        }

        /* Scrollbar styles for overall list */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #4B5563; /* Dark gray track */
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #10B981; /* Emerald thumb */
            border-radius: 10px;
            border: 2px solid #4B5563;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #059669; /* Darker emerald on hover */
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center text-gray-100 p-4">
    <!-- Background animation for water effect -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-sky-700 to-blue-900 opacity-50 animate-pulse-subtle"></div>
        <div class="absolute bottom-0 right-0 w-full h-full bg-gradient-to-tl from-emerald-700 to-teal-900 opacity-50 animate-pulse-subtle delay-1000"></div>
    </div>

    <!-- Message Box -->
    <div id="message-box" class="fixed top-4 left-1/2 -translate-x-1/2 text-white py-2 px-4 rounded-lg shadow-lg z-50 transition-all duration-300 hidden">
        <!-- Message content will be inserted here by JavaScript -->
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-red-700 text-center">
            <p class="text-xl text-white mb-4">Are you sure you want to delete this spot?</p>
            <div class="flex justify-center space-x-4">
                <button id="confirm-delete-button" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                    Delete
                </button>
                <button id="cancel-delete-button" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <div class="relative z-10 bg-gray-900 bg-opacity-80 backdrop-blur-lg rounded-xl shadow-2xl p-6 sm:p-8 md:p-10 max-w-3xl w-full text-center border border-sky-600">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-300 to-sky-400 mb-6 drop-shadow-lg">
            My Fishing Hotspots
        </h1>

        <p id="user-id-display" class="text-sm text-gray-400 mb-4 break-words"></p>
        <p id="error-display" class="text-red-400 text-sm mb-4"></p>

        <!-- Add New Fishing Spot Form -->
        <h2 class="text-2xl font-semibold text-sky-300 mb-4">Reel In A New Spot</h2>
        <form id="add-spot-form" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <input
                type="text"
                id="spot-name-input"
                placeholder="Spot Name (e.g., 'Whispering Pines Cove')"
                class="col-span-full p-3 rounded-lg bg-gray-700 border border-sky-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-white placeholder-gray-400"
                required
            />
            <input
                type="number"
                step="any"
                id="latitude-input"
                placeholder="Latitude (e.g., 40.7128)"
                class="p-3 rounded-lg bg-gray-700 border border-sky-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-white placeholder-gray-400"
                required
            />
            <input
                type="number"
                step="any"
                id="longitude-input"
                placeholder="Longitude (e.g., -74.0060)"
                class="p-3 rounded-lg bg-gray-700 border border-sky-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-white placeholder-gray-400"
                required
            />
            <button
                type="button"
                id="get-location-button"
                class="col-span-full framer-button framer-button-secondary bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white font-bold py-2 px-6 rounded-full shadow-lg transition-all duration-300 ease-in-out transform hover:scale-105 active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center mx-auto mb-4"
            >
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>
                <span id="get-location-text">Get Current Location</span>
            </button>
            <button
                type="submit"
                id="add-spot-button"
                class="col-span-full framer-button framer-button-primary flex items-center justify-center mx-auto
                       bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700
                       text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300 ease-in-out
                       transform hover:scale-105 active:scale-95"
            >
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/><path d="M19 12h-2V7h-4v5H9V7H5l7-9 7 9zM12 3L2 12h3v8h5v-6h4v6h5v-8h3L12 3z"/></svg>
                Add Fishing Spot
            </button>
        </form>

        <!-- List of Fishing Spots -->
        <h2 class="text-2xl font-semibold text-sky-300 mb-4 mt-8">My Saved Catches</h2>
        <div id="loading-spots-indicator" class="flex justify-center items-center py-8 hidden">
            <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-b-4 border-teal-500"></div>
            <p class="ml-4 text-lg text-gray-400">Reeling in spots...</p>
        </div>
        <p id="no-spots-message" class="text-gray-400 text-lg hidden">No fishing spots saved yet. Add one above!</p>
        <ul id="fishing-spots-list" class="space-y-4 text-left custom-scrollbar max-h-96 overflow-y-auto">
            <!-- Fishing spots will be rendered here by JavaScript -->
        </ul>
      </div>

    <script type="module">
        // --- Global Variables (Application State) ---
        let userId = null; // This will now be managed by localStorage
        let currentDeleteSpotId = null; // To hold the ID of the spot to be deleted
        let isLoading = false; // Declared isLoading here
        let fishingSpots = []; // In-memory array to hold spots

        // Key for local storage
        const LOCAL_STORAGE_KEY = 'fishing_spots_data';
        const LOCAL_STORAGE_USER_ID_KEY = 'fishing_app_user_id';


        // --- DOM Elements ---
        const messageBox = document.getElementById('message-box');
        const userIdDisplay = document.getElementById('user-id-display');
        const errorDisplay = document.getElementById('error-display');
        const spotNameInput = document.getElementById('spot-name-input');
        const latitudeInput = document.getElementById('latitude-input');
        const longitudeInput = document.getElementById('longitude-input');
        const addSpotForm = document.getElementById('add-spot-form');
        const getLockButton = document.getElementById('get-location-button');
        const addSpotButton = document.getElementById('add-spot-button');
        const getLocationText = document.getElementById('get-location-text');
        const fishingSpotsList = document.getElementById('fishing-spots-list');
        const loadingSpotsIndicator = document.getElementById('loading-spots-indicator');
        const noSpotsMessage = document.getElementById('no-spots-message');
        const deleteConfirmationModal = document.getElementById('delete-confirmation-modal');
        const confirmDeleteButton = document.getElementById('confirm-delete-button');
        const cancelDeleteButton = document.getElementById('cancel-delete-button');

        // --- Utility Functions ---

        /**
         * Displays a temporary message to the user.
         * @param {string} msg - The message to display.
         * @param {'info'|'success'|'error'} type - Type of message for styling.
         */
        function showMessage(msg, type = 'info') {
            messageBox.textContent = msg;
            errorDisplay.textContent = ''; // Clear any previous errors

            // Remove all specific background classes before adding the new one
            messageBox.classList.remove('hidden', 'bg-red-700', 'bg-emerald-600', 'bg-sky-700');

            // Add new class based on message type
            if (type === 'error') {
                messageBox.classList.add('bg-red-700');
            } else if (type === 'success') {
                messageBox.classList.add('bg-emerald-600');
            } else if (type === 'info') {
                messageBox.classList.add('bg-sky-700');
            }

            messageBox.classList.add('show'); // Show for animation

            setTimeout(() => {
                messageBox.classList.remove('show'); // Hide for animation
                // After transition, truly hide the element
                messageBox.addEventListener('transitionend', function handler() {
                    messageBox.classList.add('hidden');
                    messageBox.removeEventListener('transitionend', handler);
                }, { once: true });
            }, 3000);
        }

        /**
         * Sets the loading state for UI elements.
         * @param {boolean} isLoadingState
         */
        function setLoading(isLoadingState) {
            isLoading = isLoadingState;
            getLockButton.disabled = isLoadingState;
            addSpotButton.disabled = isLoadingState;
            if (isLoadingState) {
                loadingSpotsIndicator.classList.remove('hidden');
            } else {
                loadingSpotsIndicator.classList.add('hidden');
            }
        }

        /**
         * Loads fishing spots from local storage.
         */
        function loadFishingSpots() {
            setLoading(true);
            try {
                const data = localStorage.getItem(LOCAL_STORAGE_KEY);
                if (data) {
                    fishingSpots = JSON.parse(data);
                    // Sort by timestamp if it exists, otherwise assume order of insertion
                    fishingSpots.sort((a, b) => (b.timestamp || 0) - (a.timestamp || 0));
                } else {
                    fishingSpots = [];
                }
                renderFishingSpots(fishingSpots);
            } catch (e) {
                console.error("Error loading from localStorage:", e);
                errorDisplay.textContent = "Error loading saved spots.";
                fishingSpots = []; // Clear corrupted data
            } finally {
                setLoading(false);
            }
        }

        /**
         * Saves fishing spots to local storage.
         */
        function saveFishingSpots() {
            try {
                localStorage.setItem(LOCAL_STORAGE_KEY, JSON.stringify(fishingSpots));
            } catch (e) {
                console.error("Error saving to localStorage:", e);
                errorDisplay.textContent = "Error saving spots to local storage. Storage might be full.";
            }
        }

        /**
         * Renders the list of fishing spots to the DOM.
         * @param {Array<Object>} spots - Array of fishing spot objects.
         */
        function renderFishingSpots(spots) {
            fishingSpotsList.innerHTML = ''; // Clear existing list

            if (spots.length === 0) {
                noSpotsMessage.classList.remove('hidden');
            } else {
                noSpotsMessage.classList.add('hidden');
                spots.forEach(spot => {
                    const listItem = document.createElement('li');
                    listItem.className = 'bg-gray-700 p-4 rounded-lg shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center border border-sky-600';
                    // Format timestamp if it exists
                    const displayTime = spot.timestamp ? new Date(spot.timestamp).toLocaleString() : 'N/A';
                    listItem.innerHTML = `
                        <div>
                            <h3 class="text-xl font-bold text-teal-300">${spot.name}</h3>
                            <p class="text-gray-300 text-sm flex items-center">
                                Lat: ${spot.latitude}, Lon: ${spot.longitude}
                                <button data-lat="${spot.latitude}" data-lon="${spot.longitude}" class="view-on-map-button ml-2 p-1 rounded-full bg-blue-500 hover:bg-blue-600 text-white transition duration-300" title="View on Map">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                                        <path fill="none" stroke="currentColor" strokeWidth="1" d="M12 9.5a.5.5 0 01-.5.5h-1a.5.5 0 01-.5-.5V8a.5.5 0 01.5-.5h1a.5.5 0 01.5.5v1.5z"/>
                                    </svg>
                                </button>
                            </p>
                            <p class="text-gray-400 text-xs">Added: ${displayTime}</p>
                        </div>
                        <button data-id="${spot.id}" class="delete-spot-button mt-3 sm:mt-0 bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded-full text-sm transition duration-300 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fillRule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm1 5a1 1 0 100 2h4a1 1 0 100-2H8z" clipRule="evenodd"></path></svg>
                            Delete
                        </button>
                    `;
                    fishingSpotsList.appendChild(listItem);
                });

                // Attach event listeners to newly created buttons
                document.querySelectorAll('.view-on-map-button').forEach(button => {
                    button.onclick = (e) => showSpotOnMap(e.currentTarget.dataset.lat, e.currentTarget.dataset.lon);
                });
                document.querySelectorAll('.delete-spot-button').forEach(button => {
                    button.onclick = (e) => confirmDelete(e.currentTarget.dataset.id);
                });
            }
        }

        // --- Event Handlers ---

        /**
         * Handles form submission to add a new fishing spot.
         * @param {Event} e - The submit event.
         */
        async function addFishingSpot(e) {
            e.preventDefault();
            errorDisplay.textContent = '';

            const spotName = spotNameInput.value.trim();
            const latitude = latitudeInput.value;
            const longitude = longitudeInput.value;

            if (!spotName || latitude === '' || longitude === '') {
                showMessage('Please fill in all fields.', 'error');
                return;
            }

            const latNum = parseFloat(latitude);
            const lonNum = parseFloat(longitude);

            if (isNaN(latNum) || latNum < -90 || latNum > 90) {
                showMessage('Latitude must be a number between -90 and 90.', 'error');
                return;
            }
            if (isNaN(lonNum) || lonNum < -180 || lonNum > 180) {
                showMessage('Longitude must be a number between -180 and 180.', 'error');
                return;
            }

            // Create a unique ID for the new spot
            const newSpot = {
                id: crypto.randomUUID(),
                name: spotName,
                latitude: latNum,
                longitude: lonNum,
                timestamp: Date.now(), // Use current timestamp for sorting
            };

            fishingSpots.unshift(newSpot); // Add to the beginning for latest first
            saveFishingSpots();
            renderFishingSpots(fishingSpots);
            showMessage('Fish On! Spot added successfully!', 'success');
            // Clear form fields
            spotNameInput.value = '';
            latitudeInput.value = '';
            longitudeInput.value = '';
        }

        /**
         * Prompts the user for deletion confirmation.
         * @param {string} spotId - The ID of the spot to delete.
         */
        function confirmDelete(spotId) {
            currentDeleteSpotId = spotId;
            deleteConfirmationModal.classList.remove('hidden');
        }

        /**
         * Handles the actual deletion of a fishing spot from local storage.
         */
        function deleteFishingSpot() {
            if (!currentDeleteSpotId) return; // Should not happen if modal is shown correctly
            errorDisplay.textContent = '';

            const initialLength = fishingSpots.length;
            fishingSpots = fishingSpots.filter(spot => spot.id !== currentDeleteSpotId);

            if (fishingSpots.length < initialLength) {
                saveFishingSpots();
                renderFishingSpots(fishingSpots);
                showMessage('Spot reeled in! Deleted successfully.', 'success');
            } else {
                showMessage('Failed to delete spot.', 'error');
            }
            currentDeleteSpotId = null; // Clear ID
            deleteConfirmationModal.classList.add('hidden'); // Hide modal
        }

        /**
         * Gets the current GPS location using the browser's Geolocation API.
         */
        function getCurrentLocation() {
            errorDisplay.textContent = '';
            showMessage('Casting line for location...', 'info');
            setLoading(true);
            getLocationText.textContent = 'Getting Location...'; // Update button text

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        latitudeInput.value = position.coords.latitude.toFixed(6);
                        longitudeInput.value = position.coords.longitude.toFixed(6);
                        showMessage('Location obtained successfully!', 'success');
                        setLoading(false);
                        getLocationText.textContent = 'Get Current Location'; // Revert button text
                    },
                    (geoError) => {
                        let errorMessage = 'Failed to get location.';
                        switch (geoError.code) {
                            case geoError.PERMISSION_DENIED:
                                errorMessage = 'Location permission denied. Please allow access in your browser settings.';
                                break;
                            case geoError.POSITION_UNAVAILABLE:
                                errorMessage = 'Location information is unavailable.';
                                break;
                            case geoError.TIMEOUT:
                                errorMessage = 'The request to get user location timed out.';
                                break;
                            case geoError.UNKNOWN_ERROR:
                                errorMessage = 'An unknown error occurred while getting location.';
                                break;
                            default:
                                errorMessage = `Error getting location: ${geoError.message}`;
                        }
                        showMessage(errorMessage, 'error');
                        setLoading(false);
                        getLocationText.textContent = 'Get Current Location'; // Revert button text
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            } else {
                showMessage('Geolocation is not supported by your browser.', 'error');
                setLoading(false);
                getLocationText.textContent = 'Get Current Location'; // Revert button text
            }
        }

        /**
         * Opens Google Maps to display a given latitude and longitude.
         * @param {number} lat - Latitude.
         * @param {number} lon - Longitude.
         */
        function showSpotOnMap(lat, lon) {
            const mapUrl = `https://www.google.com/maps/search/?api=1&query=${lat},${lon}`;
            window.open(mapUrl, '_blank');
        }

        // --- Initialization on DOMContentLoaded ---
        document.addEventListener('DOMContentLoaded', async () => {
            // Display loading indicator initially
            setLoading(true);

            // Get or create a unique user ID using localStorage
            let storedUserId = localStorage.getItem(LOCAL_STORAGE_USER_ID_KEY);

            if (!storedUserId) {
                storedUserId = crypto.randomUUID(); // Generate a new unique ID
                localStorage.setItem(LOCAL_STORAGE_USER_ID_KEY, storedUserId);
                console.log("Generated new user ID and stored in localStorage:", storedUserId);
            } else {
                console.log("Using existing user ID from localStorage:", storedUserId);
            }
            userId = storedUserId;
            userIdDisplay.innerHTML = `Angler ID (Local Only): <span class="font-mono text-emerald-400">${userId}</span>`;

            // Load existing fishing spots
            loadFishingSpots();

            // --- Attach Event Listeners ---
            addSpotForm.addEventListener('submit', addFishingSpot);
            getLockButton.addEventListener('click', getCurrentLocation);
            confirmDeleteButton.addEventListener('click', deleteFishingSpot);
            cancelDeleteButton.addEventListener('click', () => {
                currentDeleteSpotId = null;
                deleteConfirmationModal.classList.add('hidden');
            });
        });
    </script>
</body>
</html>
