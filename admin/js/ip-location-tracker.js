class IPLocationTracker {
    constructor() {
        this.locationData = null;
    }

    async getLocationFromIP() {
        try {
            const response = await fetch('https://ipapi.co/json/');
            const data = await response.json();
            
            this.locationData = {
                latitude: data.latitude,
                longitude: data.longitude,
                city: data.city,
                country: data.country_name
            };
            
            return this.locationData;
        } catch (error) {
            console.error('Error fetching location from IP:', error);
            return null;
        }
    }

    getLocationData() {
        return this.locationData;
    }
}

// Create a global instance
const ipLocationTracker = new IPLocationTracker(); 